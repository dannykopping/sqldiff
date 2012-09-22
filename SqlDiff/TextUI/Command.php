<?php
/**
 * SqlDiff
 *
 * Copyright (c) 2011 Christer Edvartsen <cogo@starzinger.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * * The above copyright notice and this permission notice shall be included in
 *   all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * @package SqlDiff
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/sqldiff
 */

namespace SqlDiff\TextUI;

use SqlDiff\Database;
use SqlDiff\DatabaseInterface;
use SqlDiff\Exception;
use SqlDiff\Version;

/**
 * Main command class
 *
 * @package SqlDiff
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/sqldiff
 */
class Command {
    /**
     * Source file path
     *
     * @var string
     */
    private $source;

    /**
     * Target file path
     *
     * @var string
     */
    private $target;

    /**
     * Filter tables
     *
     * @var array
     */
    private $filter = array(
        'include' => array(),
        'exclude' => array(),
    );

    /**
     * Valid options
     *
     * @var array
     */
    private $options = array(
        'help'           => null,
        'version'        => null,
        'version-number' => null,
        'database-type'  => null,
        'mirror'         => null,
        'only-sql'       => null,
        'colors'         => null,
        'include'        => null,
        'exclude'        => null,
    );

    /**
     * Default values for options
     *
     * @var array
     */
    private $defaults = array(
        'database-type' => Database::MYSQL,
        'mirror'        => false,
        'only-sql'      => false,
        'colors'        => false,
        'include'       => null,
        'exclude'       => null,
    );

    /**
     * Formatter used to output text to the terminal
     *
     * @var SqlDiff\TextUI\Formatter
     */
    private $formatter;

    /**
     * Write a message
     *
     * @param string $message The message to write
     * @param boolean $exit Wether or not to exit after the message has been written
     * @param int $exitCode The code to exit with
     */
    private function writeMessage($message, $exit = false, $exitCode = 0) {
        print($message . PHP_EOL);

        if ($exit) {
            exit($exitCode);
        }
    }

    /**
     * The main method called from the sqldiff script
     */
    static public function main() {
        set_exception_handler('SqlDiff\\Exception::handle');

        $argv = $_SERVER['argv'];
        array_shift($argv);

        $self = new static();
        $self->validateArguments($argv);

        exit($self->run());
    }

    /**
     * Validate arguments and options from the command line
     *
     * @param array $argv The arguments from the command line
     * @param boolean $exit Set this to false if you don't want the script to be killed
     * @throws SqlDiff\Exception
     */
    public function validateArguments(array $argv = array()) {
        $argc = count($argv);

        if (!$argc) {
            $argc++;
            $argv[] = '--help';
        }

        for ($i = 0; $i < $argc; $i++) {
            if (substr($argv[$i], 0, 2) === '--') {
                $arg = substr($argv[$i], 2);

                switch ($arg) {
                    case 'mirror':
                        $this->options['mirror'] = true;
                        break;
                    case 'colors':
                        $this->options['colors'] = true;
                        break;
                    case 'only-sql':
                        $this->options['only-sql'] = true;
                        break;
                    case 'help':
                        $this->writeMessage($this->getHelp(), true);
                    case 'version':
                        $this->writeMessage(Version::getVersionString(), true);
                    case 'version-number':
                        $this->writeMessage(Version::getVersionNumber(), true);
                    case 'database-type':
                        if (!isset($argv[++$i])) {
                            throw new Exception('--database-type missing argument');
                        }

                        $this->options[$arg] = $argv[$i];
                        break;
                    case 'include':
                    case 'exclude':
                        if (!isset($argv[++$i])) {
                            throw new Exception('--' . $arg . ' missing argument');
                        }

                        $this->filter[$arg] = array_flip(explode(',', $argv[$i]));
                        break;
                    default:
                        throw new Exception('Unknown option: --' . $arg);
                }
            }
        }

        // Fetch target and source (which should be the last two arguments)
        $this->target = array_pop($argv);
        $this->source = array_pop($argv);

        // Create the formatter
        $this->formatter = new Formatter();

        if ($this->options['colors']) {
            $this->formatter->enable();
        }

        // See if the arguments points to a file
        foreach (array($this->source, $this->target) as $arg) {
            if (!is_readable($arg)) {
                throw new Exception('Could not open file for reading: ' . $arg);
            }
        }
    }

    /**
     * Get a database object based on a file
     *
     * @param string $type The type of the database
     * @param string $filePath The path to the dump file
     * @return SqlDiff\DatabaseInterface
     */
    private function getDatabaseObject($type, $filePath) {
        // Create a new database
        $database = Database::factory($type, $this->filter);
        $database->setCommand($this)
                 ->parseDump($filePath, $this->filter);

        return $database;
    }

    /**
     * Main run method
     *
     * This method parses the two dump files and generates objects that represent the two different
     * databases.
     */
    public function run() {
        // Get type of database
        $type = $this->options['database-type'] ?: $this->defaults['database-type'];

        // Create the two database instances
        $source = $this->getDatabaseObject($type, $this->source);
        $target = $this->getDatabaseObject($type, $this->target);

        // Initialize array that will hold all queries
        $queries = array();
        $prepend = 'Run the following queries to add information to <target>:';

        $queries = $this->getDiffQueries($source, $target);

        if ($this->options['mirror']) {
            $prepend = 'Run the following queries to make <target> the same as <source>:';
            $queries = array_merge($queries, $this->getMirrorQueries($target, $source));
        }

        if (!empty($queries)) {
            // Convert queries to a string
            $queries = implode(PHP_EOL, $queries);

            if ($this->options['only-sql']) {
                $output = $queries;
            } else {
                $output = Version::getVersionString() . PHP_EOL .
                          $prepend . PHP_EOL .
                          str_repeat('=', 80) . PHP_EOL .
                          $queries . PHP_EOL .
                          str_repeat('=', 80);
            }

            $this->writeMessage($output, true);
        }

        exit(0);
    }

    /**
     * Method to return the queries needed to update <target> to be like the <source> database
     *
     * @param SqlDiff\DatabaseInterface $source The source database
     * @param SqlDiff\DatabaseInterface $target The target database
     * @return array Returns an array of formatted queries
     */
    private function getDiffQueries($source, $target) {
        $queries = array();

		// add options
		$queries[] = "SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';\n\n";

        foreach ($source->getTables() as $tableName => $sourceTable) {
            if (!$target->hasTable($sourceTable)) {
                $queries[] = $this->formatter->format($sourceTable->getCreateTableSql(), Formatter::ADD);
            } else if ($sourceTable->getCreateTableSql() !== $target->getTable($tableName)->getCreateTableSql()) {
                $targetTable = $target->getTable($tableName);

                foreach ($sourceTable->getColumns() as $columnName => $column) {
                    if (!$targetTable->hasColumn($column)) {
                        $queries[] = $this->formatter->format($targetTable->getAddColumnSql($column), Formatter::ADD);
                    } else if ((string) $column !== (string) $targetTable->getColumn($columnName)) {
                        $queries[] = $this->formatter->format($targetTable->getChangeColumnSql($column), Formatter::CHANGE);
                    }
                }

                foreach ($sourceTable->getIndexes() as $indexName => $index) {
                    if (!$targetTable->hasIndex($index)) {
                        $queries[] = $this->formatter->format($targetTable->getAddIndexSql($index), Formatter::ADD);
                    } else if ((string) $index !== (string) $targetTable->getIndex($indexName)) {
                        $queries[] = $this->formatter->format($targetTable->getChangeIndexSql($index), Formatter::CHANGE);
                    }
                }

                $queries = array_merge($queries, $targetTable->getExtraQueries($sourceTable));
            }
        }

		// unset options
		$queries[] = "\n\nSET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;";

        return $queries;
    }

    /**
     * Generate queries that drops fields and indexes from target that does not exist in the source
     * database
     *
     * @param SqlDiff\DatabaseInterface $target The target database
     * @param SqlDiff\DatabaseInterface $source The source database
     * @return array An array of formatted queries
     */
    private function getMirrorQueries($target, $source) {
        $queries = array();

        foreach ($target->getTables() as $tableName => $targetTable) {
            if (!$source->hasTable($targetTable)) {
                $queries[] = $this->formatter->format($targetTable->getDropTableSql(), Formatter::DELETE);
            } else if ($targetTable->getCreateTableSql() !== $source->getTable($tableName)->getCreateTableSql()) {
                $sourceTable = $source->getTable($tableName);

                foreach ($targetTable->getIndexes() as $indexName => $index) {
                    if (!$sourceTable->hasIndex($index)) {
                        $queries[] = $this->formatter->format($targetTable->getDropIndexSql($index), Formatter::DELETE);
                    }
                }

                foreach ($targetTable->getColumns() as $columnName => $column) {
                    if (!$sourceTable->hasColumn($column)) {
                        $queries[] = $this->formatter->format($targetTable->getDropColumnSql($column), Formatter::DELETE);
                    }
                }
            }
        }

        return $queries;
    }

    /**
     * Display the help text
     */
    public function getHelp() {
        return Version::getVersionString() . '
Usage: sqldiff [options] <source> <target>

  where <source> and <target> are paths to files containing the structure of a
  database (typically created by mysqldump or a similar tool). Both source and
  target must be of the same database type.

  Supported database types and dump formats:
    MySQL:
      XML generated with mysqldump (with the -X or --xml option)

  Filtering:
    The --include and --exclude options can be used to filter which tables you
    want to diff. --include works as a whitelist, and --exclude works as a
    blacklist.

Options:

  --database-type <type> The database type. The only supported database is
                         MySQL (which is the default)
  --only-sql             Only display queries
  --mirror               Add SQL to drop tables, columns and indexes in the
                         <target> database that is not present in the <source>
                         database

  --colors               Use colors in output to differentiate the  generated
                         statements

  --include <name(s)>    Comma separated list of tables to include. If used
                         alone all other tables will be excluded. Can be
                         combined with the --exclude option
  --exclude <name(s)>    Comma separated list of tables to exclude. If used
                         alone all other tables will be included. Can be
                         combined with the --include option

  --version              Print the version
  --version-number       Print the version number only
  --help                 Print this information';
    }

    /**
     * Set the formatter
     *
     * @param SqlDiff\TextUI\Formatter $formatter
     * @return SqlDiff\TextUI\Command
     */
    public function setFormatter(Formatter $formatter) {
        $this->formatter = $formatter;

        return $this;
    }

    /**
     * Get the formatter
     *
     * @return SqlDiff\TextUI\Formatter
     */
    public function getFormatter() {
        return $this->formatter;
    }
}
