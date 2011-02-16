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
 */

/**
 * Class representing a MySQL index
 *
 * @package SqlDiff
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 */
class SqlDiff_Database_Table_Mysql extends SqlDiff_Database_Table_Abstract {
    /**
     * Table engine
     *
     * @var string
     */
    protected $engine = null;

    /**
     * Auto increment start value
     *
     * @var int
     */
    protected $autoIncrement = null;

    /**
     * Default charset
     *
     * @var string
     */
    protected $defaultCharset = null;

    /**
     * Collation
     *
     * @var string
     */
    protected $collation = null;

    /**
     * Checksum option
     *
     * @var boolean
     */
    protected $checksum = null;

    /**
     * Delay key write option
     *
     * @var boolean
     */
    protected $delayKeyWrite = null;

    /**
     * Fixed row format option
     *
     * @var boolean
     */
    protected $fixedRowFormat = null;

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
     * @return SqlDiff_Table
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
     * @return SqlDiff_Database_Table_Mysql
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
     * @return SqlDiff_Database_Table_Mysql
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
     * @return SqlDiff_Database_Table_Mysql
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
     * @return SqlDiff_Database_Table_Mysql
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
     * @return SqlDiff_Database_Table_Mysql
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
     * @return SqlDiff_Database_Table_Mysql
     */
    public function setFixedRowFormat($flag) {
        $this->fixedRowFormat = (bool) $flag;

        return $this;
    }

    /**
     * Get DROP TABLE syntax
     *
     * @return string
     */
    public function getDropTableSql() {
        return sprintf('DROP TABLE `%s`', $this->getName());
    }

    /**
     * Get CREATE TABLE syntax
     *
     * @return string
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
            $extra[] = 'CHEKCSUM=1';
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
     * Syntax for adding a column to the table
     *
     * @param SqlDiff_Database_Table_Column_Abstract $column
     * @return string
     */
    public function getAddColumnSql(SqlDiff_Database_Table_Column_Abstract $column) {
        $definition = (string) $column;

        // If the column has AUTO INCREMENT, we need to append PRIMARY KEY to the statement for it
        // to be a valid MySQL statement
        if ($column->getAutoIncrement()) {
            $definition .= ' PRIMARY KEY';
            $indexes = $column->getTable()->getIndexes();

            // Remove the PRIMARY KEY index from the table since it will be added in this statement
            foreach ($indexes as $index) {
                if ($index->getType() === 'PRIMARY KEY') {
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
     * Syntax for changing a column
     *
     * @param SqlDiff_Database_Table_Column_Abstract $column
     * @return string
     */
    public function getChangeColumnSql(SqlDiff_Database_Table_Column_Abstract $column) {
        return sprintf('ALTER TABLE `%s` CHANGE `%s` %s;', $this->getName(), $column->getName(), $column);
    }

    /**
     * Syntax for dropping a column
     *
     * @param SqlDiff_Database_Table_Column_Abstract $column
     * @return string
     */
    public function getDropColumnSql(SqlDiff_Database_Table_Column_Abstract $column) {
        return sprintf('ALTER TABLE `%s` DROP `%s`;', $this->getName(), $column->getName());
    }

    /**
     * Syntax for adding an index
     *
     * @param SqlDiff_Database_Table_Index_Abstract $index
     * @return string
     */
    public function getAddIndexSql(SqlDiff_Database_Table_Index_Abstract $index) {
        return sprintf('ALTER TABLE `%s` ADD %s;', $this->getName(), $index);
    }

    /**
     * Syntax for changing an index
     *
     * @param SqlDiff_Database_Table_Index_Abstract $index
     * @return string
     */
    public function getChangeIndexSql(SqlDiff_Database_Table_Index_Abstract $index) {
        return sprintf('ALTER TABLE `%s` DROP INDEX `%s`, ADD %s;', $this->getName(), $index->getName(), $index);
    }

    /**
     * Syntax for dropping an index
     *
     * @param SqlDiff_Database_Table_Index_Abstract $index
     * @return string
     */
    public function getDropIndexSql(SqlDiff_Database_Table_Index_Abstract $index) {
        if ($index->getType() === SqlDiff_Database_Table_Index_Mysql::PRIMARY_KEY) {
            $name = 'PRIMARY KEY';
        } else {
            $name = 'INDEX `' . $index->getName() . '`';
        }

        return sprintf('ALTER TABLE `%s` DROP %s;', $this->getName(), $name);
    }
}