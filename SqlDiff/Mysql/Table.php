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
 * @subpackage Mysql
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/sqldiff
 */

namespace SqlDiff\Mysql;

use SqlDiff\Database\Table as AbstractTable;
use SqlDiff\Util\DatabaseUtil;
use SqlDiff\Database\TableInterface;
use SqlDiff\Database\Table\ColumnInterface;
use SqlDiff\Database\Table\IndexInterface;
use SqlDiff\TextUI\Formatter;

/**
 * Class representing a MySQL table
 *
 * @package SqlDiff
 * @subpackage Mysql
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/sqldiff
 */
class Table extends AbstractTable implements TableInterface {
    /**
     * Table engine
     *
     * @var string
     */
    private $engine;

    /**
     * Auto increment start value
     *
     * @var int
     */
    private $autoIncrement;

    /**
     * Default charset
     *
     * @var string
     */
    private $defaultCharset;

    /**
     * Collation
     *
     * @var string
     */
    private $collation;

    /**
     * Checksum option
     *
     * @var boolean
     */
    private $checksum;

    /**
     * Delay key write option
     *
     * @var boolean
     */
    private $delayKeyWrite;

    /**
     * Fixed row format option
     *
     * @var boolean
     */
    private $fixedRowFormat;

    /**
     * Get the engine
     *
     * @return string
     */
    public function getEngine() {
        return $this->engine;
    }

    /**
     * Set the engine
     *
     * @param string $engine
     * @return SqlDiff\Mysql\Table
     */
    public function setEngine($engine) {
        $this->engine = $engine;

        return $this;
    }

    /**
     * Get the auto increment value
     *
     * @return int
     */
    public function getAutoIncrement() {
        return $this->autoIncrement;
    }

    /**
     * Set the auto increment value
     *
     * @param int $autoIncrement
     * @return SqlDiff\Mysql\Table
     */
    public function setAutoIncrement($autoIncrement) {
        $this->autoIncrement = $autoIncrement;

        return $this;
    }

    /**
     * Get deafult charset of table
     *
     * @return string
     */
    public function getDefaultCharset() {
        return $this->defaultCharset;
    }

    /**
     * Set default charset of table
     *
     * @param string $defaultCharset
     * @return SqlDiff\Mysql\Table
     */
    public function setDefaultCharset($defaultCharset) {
        $this->defaultCharset = $defaultCharset;

        return $this;
    }

    /**
     * Get table collation
     *
     * @return string
     */
    public function getCollation() {
        return $this->collation;
    }

    /**
     * Set table collation
     *
     * @param string $collation
     * @return SqlDiff\Mysql\Table
     */
    public function setCollation($collation) {
        $this->collation = $collation;

        return $this;
    }

    /**
     * Get the checksum option
     *
     * @return boolean
     */
    public function getChecksum() {
        return $this->checksum;
    }

    /**
     * Set the checksum option
     *
     * @param boolean $flag
     * @return SqlDiff\Mysql\Table
     */
    public function setChecksum($flag) {
        $this->checksum = (bool) $flag;

        return $this;
    }

    /**
     * Get the delay key write option
     *
     * @return boolean
     */
    public function getDelayKeyWrite() {
        return $this->delayKeyWrite;
    }

    /**
     * Set the delay key write option
     *
     * @param boolean $flag
     * @return SqlDiff\Mysql\Table
     */
    public function setDelayKeyWrite($flag) {
        $this->delayKeyWrite = (bool) $flag;

        return $this;
    }

    /**
     * Get the fixed row format option
     *
     * @return boolean
     */
    public function getFixedRowFormat() {
        return $this->fixedRowFormat;
    }

    /**
     * Set the fixed row format option
     *
     * @param boolean $flag
     * @return SqlDiff\Mysql\Table
     */
    public function setFixedRowFormat($flag) {
        $this->fixedRowFormat = (bool) $flag;

        return $this;
    }

    /**
     * @see SqlDiff\Database\TableInterface::getDropTableSql()
     */
    public function getDropTableSql() {
        return sprintf('DROP TABLE `%s`;', $this->getName());
    }

    /**
     * @see SqlDiff\Database\TableInterface::getCreateTableSql()
     */
    public function getCreateTableSql() {
        $fields = array();
        $extra  = array();

        foreach ($this->getColumns() as $column) {
            $fields[] = $column->getDefinition();
        }

        foreach ($this->getIndexes() as $index) {
            $fields[] = $index->getDefinition();
        }

        $fields = implode(',' . PHP_EOL, $fields);

        if ($this->getEngine()) {
            $extra[] = 'ENGINE=' . $this->getEngine();
        }

        if ($this->getAutoIncrement()) {
            $extra[] = 'AUTO_INCREMENT=' . $this->getAutoIncrement();
        }

        if ($this->getDefaultCharset()) {
            $extra[] = 'DEFAULT CHARSET=' . $this->getDefaultCharset();
        }

        if ($this->getCollation()) {
            $extra[] = 'COLLATE=' . $this->getCollation();
        }

        if ($this->getComment()) {
            $extra[] = 'COMMENT=\'' . $this->getComment() . '\'';
        }

        if ($this->getChecksum() === true) {
            $extra[] = 'CHECKSUM=1';
        }

        if ($this->getDelayKeyWrite() === true) {
            $extra[] = 'DELAY_KEY_WRITE=1';
        }

        if ($this->getFixedRowFormat() === true) {
            $extra[] = 'ROW_FORMAT=FIXED';
        }

        $extra = implode(' ', $extra);

        // Initialize statement
        $createTable = 'CREATE TABLE `%s` (' . PHP_EOL . '%s' . PHP_EOL . ') %s;';

        return trim(sprintf($createTable, $this->getName(), $fields, $extra));
    }

    /**
     * @see SqlDiff\Database\TableInterface::getAddColumnSql()
     */
    public function getAddColumnSql(ColumnInterface $column) {
        $definition = $column->getDefinition();

        // If the column has AUTO INCREMENT, we need to append PRIMARY KEY to the statement for it
        // to be a valid MySQL statement
        if ($column->getAutoIncrement()) {
            $definition .= ' PRIMARY KEY';
            $indexes = $column->getTable()->getIndexes();

            // Remove the PRIMARY KEY index from the table since it will be added in this statement
            foreach ($indexes as $index) {
                if ($index->getType() === Index::PRIMARY_KEY) {
                    $column->getTable()->removeIndex($index);
                    break;
                }
            }
        }

        // If the column position is not at the end of the table it should be inserted at the
        // correct position
        $prev = $column->getPreviousColumn();
        $position = '';

        if ($prev) {
            // If there is a column before this one, add "AFTER <name>"
            $position = sprintf(' AFTER `%s`', $prev->getName());
        } else if ($column->getPosition() === 0) {
            // If this column is first in the table, add "FIRST"
            $position = ' FIRST';
        }

        return sprintf('ALTER TABLE `%s` ADD %s%s;', $this->getName(), $definition, $position);
    }

    /**
     * @see SqlDiff\Database\TableInterface::getChangeColumnSql()
     */
    public function getChangeColumnSql(ColumnInterface $column) {
        return sprintf('ALTER TABLE `%s` CHANGE `%s` %s;', $this->getName(), $column->getName(), $column->getDefinition());
    }

    /**
     * @see SqlDiff\Database\TableInterface::getDropColumnSql()
     */
    public function getDropColumnSql(ColumnInterface $column) {
        return sprintf('ALTER TABLE `%s` DROP `%s`;', $this->getName(), $column->getName());
    }

    /**
     * @see SqlDiff\Database\TableInterface::getAddIndexSql()
     */
    public function getAddIndexSql(IndexInterface $index) {
		if($index->getType() == Index::FOREIGN_KEY)
			return DatabaseUtil::getFKAlterStatement($index->getTable(), $index->getName());

        return sprintf('ALTER TABLE `%s` ADD %s;', $this->getName(), $index->getDefinition());
    }

    /**
     * @see SqlDiff\Database\TableInterface::getChangeIndexSql()
     */
    public function getChangeIndexSql(IndexInterface $index) {
        return sprintf('ALTER TABLE `%s` DROP INDEX `%s`, ADD %s;', $this->getName(), $index->getName(), $index->getDefinition());
    }

    /**
     * @see SqlDiff\Database\TableInterface::getDropIndexSql()
     */
    public function getDropIndexSql(IndexInterface $index) {
        if ($index->getType() === Index::PRIMARY_KEY) {
            $name = 'PRIMARY KEY';
        } else {
            $name = 'INDEX `' . $index->getName() . '`';
        }

        return sprintf('ALTER TABLE `%s` DROP %s;', $this->getName(), $name);
    }

    /**
     * @see SqlDiff\Database\TableInterface::getExtraQueries()
     */
    public function getExtraQueries(TableInterface $table) {
        $queries = array();
        $formatter = $this->getDatabase()->getCommand()->getFormatter();

        // See if the engines are the same
        if ($this->getEngine() !== $table->getEngine()) {
            $queries[] = $formatter->format("ALTER TABLE `" . $this->getName() . "` ENGINE = " . $table->getEngine(), Formatter::CHANGE);
        }

        return $queries;
    }
}
