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

/**
 * Class representing a MySQL index
 *
 * @package SqlDiff
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/sqldiff
 */
abstract class SqlDiff_Database_Table_Abstract {
    /**
     * The database object this table belongs to
     *
     * @var SqlDiff_Database_Abstract
     */
    protected $database = null;

    /**
     * The name of the table
     *
     * @var string
     */
    protected $name = null;

    /**
     * Table comment
     *
     * @var string
     */
    protected $comment = null;

    /**
     * Columns in the table
     *
     * @var array An array of SqlDiff_Database_Table_Column_Abstract objects
     */
    protected $columns = array();

    /**
     * Indexes in the table
     *
     * @var array An array of SqlDiff_Database_Table_Index_Abstract objects
     */
    protected $indexes = array();

    /**
     * Counter used for column positions
     *
     * @var int
     */
    protected $position = 0;

    /**
     * Array holding positions for the different columns
     *
     * @var array
     */
    protected $columnPositions = array();

    /**
     * Set the database object
     *
     * @param SqlDiff_Database_Abstract $database
     * @return SqlDiff_Database_Table_Abstract
     */
    public function setDatabase(SqlDiff_Database_Abstract $database) {
        $this->database = $database;

        return $this;
    }

    /**
     * Get the database object
     *
     * @return SqlDiff_Database_Abstract
     */
    public function getDatabase() {
        return $this->database;
    }

    /**
     * Get the number of columns
     *
     * @return int
     */
    public function getNumColumns() {
        return count($this->columns);
    }

    /**
     * Get the number of indexes
     *
     * @return int
     */
    public function getNumIndexes() {
        return count($this->indexes);
    }

    /**
     * Get the name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set the name
     *
     * @param string $name
     * @return SqlDiff_Database_Table_Abstract
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the table comment
     *
     * @return string
     */
    public function getComment() {
        return $this->comment;
    }

    /**
     * Set a table comment
     *
     * @param string $comment
     * @return SqlDiff_Database_Table_Abstract
     */
    public function setComment($comment) {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Add a column
     *
     * @param SqlDiff_Database_Table_Column_Abstract $column
     * @return SqlDiff_Database_Table_Abstract
     */
    public function addColumn(SqlDiff_Database_Table_Column_Abstract $column) {
        $column->setTable($this);
        $colName = $column->getName();
        $this->columns[$colName] = $column;
        $this->columnPositions[$this->position++] = $colName;

        return $this;
    }

    /**
     * Get a column based on a position
     *
     * @param int $position The position to fetch (0-based index)
     * @return SqlDiff_Database_Table_Column_Abstract|null
     */
    public function getColumnByPosition($position) {
        if (!isset($this->columnPositions[$position]) || !isset($this->columns[$this->columnPositions[$position]])) {
            return null;
        }

        return $this->columns[$this->columnPositions[$position]];
    }

    /**
     * Get the position of the column (0-based index)
     *
     * @param string|SqlDiff_Database_Table_Column_Abstract $column Either a column name or a
     *                                                              column object
     * @return int|null
     */
    public function getColumnPosition($column) {
        if ($column instanceof SqlDiff_Database_Table_Column_Abstract) {
            $column = $column->getName();
        }

        $pos = array_search($column, $this->columnPositions);

        return $pos !== false ? $pos : null;
    }

    /**
     * Add an array of columns
     *
     * @param array $columns An array of SqlDiff_Database_Table_Column_Abstract objects
     * @return SqlDiff_Database_Table_Abstract
     */
    public function addColumns(array $columns) {
        foreach ($columns as $column) {
            $this->addColumn($column);
        }

        return $this;
    }

    /**
     * Remove a column
     *
     * @param string|SqlDiff_Database_Table_Column_Abstract $column Either a column name or a
     *                                                              column object
     * @return SqlDiff_Database_Table_Abstract
     */
    public function removeColumn($column) {
        if ($column instanceof SqlDiff_Database_Table_Column_Abstract) {
            $column = $column->getName();
        }

        // Remove the column
        unset($this->columns[$column]);

        // Find the position, remove it, and fix the rest of the array
        $position = array_search($column, $this->columnPositions);
        array_splice($this->columnPositions, $position, 1);
        $this->position--;

        return $this;
    }

    /**
     * Fetch all columns
     *
     * @return array An array of SqlDiff_Database_Table_Column_Abstract objects
     */
    public function getColumns() {
        return $this->columns;
    }

    /**
     * Fetch a single column
     *
     * @param string $name
     * @return null|SqlDiff_Database_Table_Column_Abstract
     */
    public function getColumn($name) {
        if (empty($this->columns[$name])) {
            return null;
        }

        return $this->columns[$name];
    }


    /**
     * See if this table has a specific column
     *
     * @param string|SqlDiff_Database_Table_Column_Abstract $column Either a column name or a
     *                                                                  column object
     * @return boolean
     */
    public function hasColumn($column) {
        if ($column instanceof SqlDiff_Database_Table_Column_Abstract) {
            $column = $column->getName();
        }

        return isset($this->columns[$column]);
    }

    /**
     * Add an index
     *
     * @param SqlDiff_Database_Table_Index_Abstract $index
     * @return SqlDiff_Database_Table_Abstract
     */
    public function addIndex(SqlDiff_Database_Table_Index_Abstract $index) {
        $index->setTable($this);
        $name = $index->getName();

        if (empty($name)) {
            $name = 'PK';
        }

        $this->indexes[$name] = $index;

        return $this;
    }

    /**
     * Add indexes
     *
     * @param array $indexes An array of SqlDiff_Database_Table_Index_Abstract objects
     * @return SqlDiff_Database_Table_Abstract
     */
    public function addIndexes(array $indexes) {
        foreach ($indexes as $index) {
            $this->addIndex($index);
        }

        return $this;
    }

    /**
     * Remove an index
     *
     * @param string|SqlDiff_Database_Table_Index_Abstract $index Either an index name or an
     *                                                                index object
     * @return SqlDiff_Database_Table_Abstract
     */
    public function removeIndex($index) {
        if ($index instanceof SqlDiff_Database_Table_Index_Abstract) {
            $index = $index->getName();
        }

        unset($this->indexes[$index]);

        return $this;
    }

    /**
     * Get all indexes
     *
     * @return array Returns an array of SqlDiff_Database_Table_Index_Abstract objects
     */
    public function getIndexes() {
        return $this->indexes;
    }

    /**
     * Fetch a single index
     *
     * @param string $index
     * @return null|SqlDiff_Database_Table_Index_Abstract
     */
    public function getIndex($index) {
        if (empty($this->indexes[$index])) {
            return null;
        }

        return $this->indexes[$index];
    }

    /**
     * See if this table has a specific index
     *
     * @param string|SqlDiff_Database_Table_Index_Abstract $index Either an index name or an
     *                                                                index object
     * @return boolean
     */
    public function hasIndex($index) {
        if ($index instanceof SqlDiff_Database_Table_Index_Abstract) {
            $index = $index->getName();
        }

        return isset($this->indexes[$index]);
    }

    /**
     * Syntax for creating a table
     *
     * @return string
     */
    abstract public function getCreateTableSql();

    /**
     * Syntax for dropping a table
     *
     * @return string
     */
    abstract public function getDropTableSql();

    /**
     * Syntax for adding a column to the table
     *
     * @param SqlDiff_Database_Table_Column_Abstract $column
     * @return string
     */
    abstract public function getAddColumnSql(SqlDiff_Database_Table_Column_Abstract $column);

    /**
     * Syntax for changing a column
     *
     * @param SqlDiff_Database_Table_Column_Abstract $column
     * @return string
     */
    abstract public function getChangeColumnSql(SqlDiff_Database_Table_Column_Abstract $column);

    /**
     * Syntax for dropping a column
     *
     * @param SqlDiff_Database_Table_Column_Abstract $column
     * @return string
     */
    abstract public function getDropColumnSql(SqlDiff_Database_Table_Column_Abstract $column);

    /**
     * Syntax for adding an index
     *
     * @param SqlDiff_Database_Table_Index_Abstract $index
     * @return string
     */
    abstract public function getAddIndexSql(SqlDiff_Database_Table_Index_Abstract $index);

    /**
     * Syntax for changing an index
     *
     * @param SqlDiff_Database_Table_Index_Abstract $index
     * @return string
     */
    abstract public function getChangeIndexSql(SqlDiff_Database_Table_Index_Abstract $index);

    /**
     * Syntax for dropping an index
     *
     * @param SqlDiff_Database_Table_Index_Abstract $index
     * @return string
     */
    abstract public function getDropIndexSql(SqlDiff_Database_Table_Index_Abstract $index);
}