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

namespace SqlDiff;

use SqlDiff\TextUI\Command; 
use SqlDiff\Database\TableInterface; 

/**
 * Abstract database class that database implementations can extend
 *
 * @package SqlDiff
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/sqldiff
 */
abstract class Database {
    /**#@+
     * Supported databases
     *
     * @var string
     */
    const MYSQL = 'mysql';
    /**#@-*/

    /**
     * Tables in this database
     *
     * @var array Array of SqlDiff\Database\TableInterface objects
     */
    private $tables = array();

    /**
     * Name of the database
     *
     * @var string
     */
    private $name;

    /**
     * The originating command object
     *
     * @var SqlDiff\TextUI\Command
     */
    private $command;

    /**
     * Set the name of the database
     *
     * @param string $name
     * @return SqlDiff\Database
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the name of the database
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set the current command
     *
     * @param SqlDiff\TextUI\Command $command
     * @return SqlDiff\Database
     */
    public function setCommand(Command $command) {
        $this->command = $command;

        return $this;
    }

    /**
     * Get the command
     *
     * @return SqlDiff\TextUI\Command
     */
    public function getCommand() {
        return $this->command;
    }

    /**
     * Get the number of tables in this database
     *
     * @return int
     */
    public function getNumTables() {
        return count($this->tables);
    }

    /**
     * Get all tables
     *
     * @return array Returns an array of SqlDiff\Database\TableInterface objects
     */
    public function getTables() {
        return $this->tables;
    }

    /**
     * Get a single table based on its name
     *
     * @param string $name
     * @return null|SqlDiff\Database\TableInterface
     */
    public function getTable($name) {
        if (!isset($this->tables[$name])) {
            return null;
        }

        return $this->tables[$name];
    }

    /**
     * Add a table to the database
     *
     * @param SqlDiff\Database\TableInterface $table
     * @return SqlDiff\Database
     */
    public function addTable(TableInterface $table) {
        $table->setDatabase($this);
        $this->tables[$table->getName()] = $table;

        return $this;
    }

    /**
     * Remove a table from the database
     *
     * @param string|SqlDiff\Database\TableInterface $table Either the name of the table or a valid 
     *                                                      table instance.
     * @throws SqlDiff\Exception
     * @return SqlDiff\Database
     */
    public function removeTable($table) {
        if ($table instanceof TableInterface) {
            $table = $table->getName();
        }

        // Remove table
        unset($this->tables[$table]);

        return $this;
    }

    /**
     * See if the database has a specific table
     *
     * @param string|SqlDiff\Database\TableInterface $table Either the name of the table or a valid 
     *                                                      table instance.
     * @return boolean
     */
    public function hasTable($table) {
        if ($table instanceof TableInterface) {
            $table = $table->getName();
        }

        return isset($this->tables[$table]);
    }

    /**
     * Factory method
     *
     * This method is used to create a supported database instances.
     *
     * @param string $type One of the constants defined in this class.
     * @return SqlDiff\DatabaseInterface
     */
    static public function factory($type = self::MYSQL) {
        // Fix the type
        $type = ucfirst(strtolower($type));

        // Generate class name
        $className = 'SqlDiff\\Database\\' . $type;

        // Return new object
        return new $className($type);
    }
}
