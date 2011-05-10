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

use SqlDiff\Database\Table\ColumnInterface;
use SqlDiff\Database\Table\IndexInterface;
 
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
}
