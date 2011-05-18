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

use SqlDiff\DatabaseInterface;
use SqlDiff\Database\TableInterface;
use SqlDiff\Database\Table\ColumnInterface;
use SqlDiff\Database\Table\IndexInterface;
 
/**
 * Database table interface 
 *
 * @package SqlDiff
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/sqldiff
 */
interface TableInterface {
    /**
     * Syntax for creating a table
     *
     * @return string
     */
    function getCreateTableSql();

    /**
     * Syntax for dropping a table
     *
     * @return string
     */
    function getDropTableSql();

    /**
     * Syntax for adding a column to the table
     *
     * @param SqlDiff\Database\Table\ColumnInterface $column
     * @return string
     */
    function getAddColumnSql(ColumnInterface $column);

    /**
     * Syntax for changing a column
     *
     * @param SqlDiff\Database\Table\ColumnInterface $column
     * @return string
     */
    function getChangeColumnSql(ColumnInterface $column);

    /**
     * Syntax for dropping a column
     *
     * @param SqlDiff\Database\Table\ColumnInterface $column
     * @return string
     */
    function getDropColumnSql(ColumnInterface $column);

    /**
     * Syntax for adding an index
     *
     * @param SqlDiff\Database\Table\IndexInterface $index
     * @return string
     */
    function getAddIndexSql(IndexInterface $index);

    /**
     * Syntax for changing an index
     *
     * @param SqlDiff\Database\Table\IndexInterface $index
     * @return string
     */
    function getChangeIndexSql(IndexInterface $index);

    /**
     * Syntax for dropping an index
     *
     * @param SqlDiff\Database\Table\IndexInterface $index
     * @return string
     */
    function getDropIndexSql(IndexInterface $index);

    /**
     * Get remaning implementation specific queries
     *
     * @param SqlDiff\Database\TableInterface $table
     * @return array Returns an array of pre-formatted queries
     */
    function getExtraQueries(TableInterface $table);

    /**
     * Set the database object
     *
     * @param SqlDiff\DatabaseInterface $database
     * @return SqlDiff\Database\TableInterface
     */
    function setDatabase(DatabaseInterface $database);

    /**
     * Get the database object
     *
     * @return SqlDiff\DatabaseInterface 
     */
    function getDatabase();

    /**
     * Get the number of columns
     *
     * @return int
     */
    function getNumColumns();

    /**
     * Get the number of indexes
     *
     * @return int
     */
    function getNumIndexes();

    /**
     * Get the name
     *
     * @return string
     */
    function getName();

    /**
     * Set the name
     *
     * @param string $name
     * @return SqlDiff\Database\TableInterface
     */
    function setName($name);

    /**
     * Get the table comment
     *
     * @return string
     */
    function getComment();

    /**
     * Set a table comment
     *
     * @param string $comment
     * @return SqlDiff\Database\TableInterface
     */
    function setComment($comment);

    /**
     * Add a column
     *
     * @param SqlDiff\Database\Table\ColumnInterface $column
     * @return SqlDiff\Database\TableInterface
     */
    function addColumn(ColumnInterface $column);

    /**
     * Get a column based on a position
     *
     * @param int $position The position to fetch (0-based index)
     * @return SqlDiff\Database\Table\ColumnInterface|null
     */
    function getColumnByPosition($position);

    /**
     * Get the position of the column (0-based index)
     *
     * @param string|SqlDiff\Database\Table\ColumnInterface $column Either a column name or a
     *                                                              column object
     * @return int|null
     */
    function getColumnPosition($column);

    /**
     * Add an array of columns
     *
     * @param array $columns An array of SqlDiff\Database\Table\ColumnInterface objects
     * @return SqlDiff\Database\TableInterface
     */
    function addColumns(array $columns);

    /**
     * Remove a column
     *
     * @param string|SqlDiff\Database\Table\ColumnInterface $column Either a column name or a
     *                                                              column object
     * @return SqlDiff\Database\Table 
     */
    function removeColumn($column);

    /**
     * Fetch all columns
     *
     * @return array An array of SqlDiff\Database\Table\ColumnInterface objects
     */
    function getColumns();

    /**
     * Fetch a single column
     *
     * @param string $name
     * @return null|SqlDiff\Database\Table\ColumnInterface 
     */
    function getColumn($name);

    /**
     * See if this table has a specific column
     *
     * @param string|SqlDiff\Database\Table\ColumnInterface $column Either a column name or a
     *                                                              column object
     * @return boolean
     */
    function hasColumn($column);

    /**
     * Add an index
     *
     * @param SqlDiff\Database\Table\IndexInterface $index
     * @return SqlDiff\Database\TableInterface 
     */
    function addIndex(IndexInterface $index);

    /**
     * Add indexes
     *
     * @param array $indexes An array of SqlDiff_Database_Table_Index_Abstract objects
     * @return SqlDiff\Database\TableInterface 
     */
    function addIndexes(array $indexes);

    /**
     * Remove an index
     *
     * @param string|SqlDiff\Database\Table\IndexInterface $index Either an index name or an
     *                                                            index object
     * @return SqlDiff\Database\TableInterface 
     */
    function removeIndex($index);

    /**
     * Get all indexes
     *
     * @return array Returns an array of SqlDiff\Database\Table\IndexInterface objects
     */
    function getIndexes();

    /**
     * Fetch a single index
     *
     * @param string $index
     * @return null|SqlDiff\Database\Table\IndexInterface
     */
    function getIndex($index);

    /**
     * See if this table has a specific index
     *
     * @param string|SqlDiff\Database\Table\IndexInterface $index Either an index name or an
     *                                                            index object
     * @return boolean
     */
    function hasIndex($index);
}
