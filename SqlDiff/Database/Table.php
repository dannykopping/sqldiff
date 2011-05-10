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

namespace SqlDiff\Database;

use SqlDiff\Database;
use SqlDiff\Database\Table\ColumnInterface;
use SqlDiff\Database\Table\IndexInterface;

/**
 * Class representing a MySQL index
 *
 * @package SqlDiff
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/sqldiff
 */
abstract class Table {
    /**
     * The database object this table belongs to
     *
     * @var Database 
     */
    private $database;

    /**
     * The name of the table
     *
     * @var string
     */
    private $name;

    /**
     * Table comment
     *
     * @var string
     */
    private $comment;

    /**
     * Columns in the table
     *
     * @var array An array of SqlDiff_Database_Table_Column_Abstract objects
     */
    private $columns = array();

    /**
     * Indexes in the table
     *
     * @var array An array of SqlDiff_Database_Table_Index_Abstract objects
     */
    private $indexes = array();

    /**
     * Counter used for column positions
     *
     * @var int
     */
    private $position = 0;

    /**
     * Array holding positions for the different columns
     *
     * @var array
     */
    private $columnPositions = array();

    /**
     * Set the database object
     *
     * @param SqlDiff\DatabaseInterface $database
     * @return SqlDiff\Database\Table 
     */
    public function setDatabase(Database $database) {
        $this->database = $database;

        return $this;
    }

    /**
     * Get the database object
     *
     * @return SqlDiff\DatabaseInterface 
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
     * @return SqlDiff\Database\Table 
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
     * @return SqlDiff\Database\Table
     */
    public function setComment($comment) {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Add a column
     *
     * @param SqlDiff\Database\Table\ColumnInterface $column
     * @return SqlDiff\Database\Table 
     */
    public function addColumn(ColumnInterface $column) {
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
     * @return SqlDiff\Database\Table\ColumnIntterface|null
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
     * @param string|SqlDiff\Database\Table\ColumnInterface $column Either a column name or a
     *                                                              column object
     * @return int|null
     */
    public function getColumnPosition($column) {
        if ($column instanceof ColumnInterface) {
            $column = $column->getName();
        }

        $pos = array_search($column, $this->columnPositions);

        return $pos !== false ? $pos : null;
    }

    /**
     * Add an array of columns
     *
     * @param array $columns An array of SqlDiff\Database\Table\ColumnInterface objects
     * @return SqlDiff\Database\Table 
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
     * @param string|SqlDiff\Database\Table\ColumnInterface $column Either a column name or a
     *                                                              column object
     * @return SqlDiff\Database\Table 
     */
    public function removeColumn($column) {
        if ($column instanceof ColumnInterface) {
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
     * @return array An array of SqlDiff\Database\Table\ColumnInterface objects
     */
    public function getColumns() {
        return $this->columns;
    }

    /**
     * Fetch a single column
     *
     * @param string $name
     * @return null|SqlDiff\Database\Table\ColumnInterface 
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
     * @param string|SqlDiff\Database\Table\ColumnInterface $column Either a column name or a
     *                                                              column object
     * @return boolean
     */
    public function hasColumn($column) {
        if ($column instanceof ColumnInterface) {
            $column = $column->getName();
        }

        return isset($this->columns[$column]);
    }

    /**
     * Add an index
     *
     * @param SqlDiff\Database\Table\IndexInterface $index
     * @return SqlDiff\Database\Table 
     */
    public function addIndex(IndexInterface $index) {
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
     * @param string|SqlDiff\Database\Table\IndexInterface $index Either an index name or an
     *                                                            index object
     * @return SqlDiff\Database\Table 
     */
    public function removeIndex($index) {
        if ($index instanceof IndexInterface) {
            $index = $index->getName();
        }

        unset($this->indexes[$index]);

        return $this;
    }

    /**
     * Get all indexes
     *
     * @return array Returns an array of SqlDiff\Database\Table\IndexInterface objects
     */
    public function getIndexes() {
        return $this->indexes;
    }

    /**
     * Fetch a single index
     *
     * @param string $index
     * @return null|SqlDiff\Database\Table
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
     * @param string|SqlDiff\Database\Table\IndexInterface $index Either an index name or an
     *                                                            index object
     * @return boolean
     */
    public function hasIndex($index) {
        if ($index instanceof IndexInterface) {
            $index = $index->getName();
        }

        return isset($this->indexes[$index]);
    }
}
