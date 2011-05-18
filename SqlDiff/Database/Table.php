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
use SqlDiff\DatabaseInterface;
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
     * @see SqlDiff\Database\TableInterface::setDatabase()
     */
    public function setDatabase(DatabaseInterface $database) {
        $this->database = $database;

        return $this;
    }

    /**
     * @see SqlDiff\Database\TableInterface::getDatabase()
     */
    public function getDatabase() {
        return $this->database;
    }

    /**
     * @see SqlDiff\Database\TableInterface::getNumColumns()
     */
    public function getNumColumns() {
        return count($this->columns);
    }

    /**
     * @see SqlDiff\Database\TableInterface::getNumIndexes()
     */
    public function getNumIndexes() {
        return count($this->indexes);
    }

    /**
     * @see SqlDiff\Database\TableInterface::getName()
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @see SqlDiff\Database\TableInterface::setName()
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * @see SqlDiff\Database\TableInterface::getComment()
     */
    public function getComment() {
        return $this->comment;
    }

    /**
     * @see SqlDiff\Database\TableInterface::setComment()
     */
    public function setComment($comment) {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @see SqlDiff\Database\TableInterface::addColumn()
     */
    public function addColumn(ColumnInterface $column) {
        $column->setTable($this);
        $colName = $column->getName();
        $this->columns[$colName] = $column;
        $this->columnPositions[$this->position++] = $colName;

        return $this;
    }

    /**
     * @see SqlDiff\Database\TableInterface::getColumnByPosition()
     */
    public function getColumnByPosition($position) {
        if (!isset($this->columnPositions[$position]) || !isset($this->columns[$this->columnPositions[$position]])) {
            return null;
        }

        return $this->columns[$this->columnPositions[$position]];
    }

    /**
     * @see SqlDiff\Database\TableInterface::getColumnPosition()
     */
    public function getColumnPosition($column) {
        if ($column instanceof ColumnInterface) {
            $column = $column->getName();
        }

        $pos = array_search($column, $this->columnPositions);

        return $pos !== false ? $pos : null;
    }

    /**
     * @see SqlDiff\Database\TableInterface::addColumn()
     */
    public function addColumns(array $columns) {
        foreach ($columns as $column) {
            $this->addColumn($column);
        }

        return $this;
    }

    /**
     * @see SqlDiff\Database\TableInterface::removeColumn()
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
     * @see SqlDiff\Database\TableInterface::getColumns()
     */
    public function getColumns() {
        return $this->columns;
    }

    /**
     * @see SqlDiff\Database\TableInterface::getColumn()
     */
    public function getColumn($name) {
        if (empty($this->columns[$name])) {
            return null;
        }

        return $this->columns[$name];
    }


    /**
     * @see SqlDiff\Database\TableInterface::hasColumn()
     */
    public function hasColumn($column) {
        if ($column instanceof ColumnInterface) {
            $column = $column->getName();
        }

        return isset($this->columns[$column]);
    }

    /**
     * @see SqlDiff\Database\TableInterface::addIndex()
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
     * @see SqlDiff\Database\TableInterface::addIndexes()
     */
    public function addIndexes(array $indexes) {
        foreach ($indexes as $index) {
            $this->addIndex($index);
        }

        return $this;
    }

    /**
     * @see SqlDiff\Database\TableInterface::removeIndex()
     */
    public function removeIndex($index) {
        if ($index instanceof IndexInterface) {
            $index = $index->getName();
        }

        unset($this->indexes[$index]);

        return $this;
    }

    /**
     * @see SqlDiff\Database\TableInterface::getIndexes()
     */
    public function getIndexes() {
        return $this->indexes;
    }

    /**
     * @see SqlDiff\Database\TableInterface::getIndex()
     */
    public function getIndex($index) {
        if (empty($this->indexes[$index])) {
            return null;
        }

        return $this->indexes[$index];
    }

    /**
     * @see SqlDiff\Database\TableInterface::hasIndex()
     */
    public function hasIndex($index) {
        if ($index instanceof IndexInterface) {
            $index = $index->getName();
        }

        return isset($this->indexes[$index]);
    }
}
