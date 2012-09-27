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
 * @subpackage Interfaces
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/sqldiff
 */

namespace SqlDiff\Database\Table;

use SqlDiff\Database\TableInterface;

/**
 * Interface for a table column
 *
 * @package SqlDiff
 * @subpackage Interfaces
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/sqldiff
 */
interface ColumnInterface {
    /**
     * Get the definition of the column
     *
     * @return string
     */
    function getDefinition();
    
    /**
     * Set the table instance
     *
     * @param SqlDiff\Database\TableInterface $table
     * @return SqlDiff\Database\Table\ColumnInterface
     */
    function setTable(TableInterface $table);
        
    /**
     * Get the table instance
     *
     * @return SqlDiff\Database\TableInterface
     */
    function getTable();

    /**
     * Set the type
     *
     * @param string $type
     * @return SqlDiff\Database\Table\ColumnInterface
     */
    function setType($type);

    /**
     * Get the type attribute
     *
     * @return string
     */
    function getType();

    /**
     * Set the attribute
     *
     * @param string $attribute
     * @return SqlDiff\Database\Table\ColumnInterface
     */
    function setAttribute($attribute);

    /**
     * Get the attribute
     *
     * @return string
     */
    function getAttribute();

    /**
     * Get the not null flag
     *
     * @return string
     */
    function getNotNull();

    /**
     * Set the not null flag
     *
     * @param boolean $flag
     * @return SqlDiff\Database\Table\ColumnInterface
     */
    function setNotNull($flag);

    /**
     * Get the default value
     *
     * @return string
     */
    function getDefault();

    /**
     * Set the default value
     *
     * @param string $default
     * @return SqlDiff\Database\Table\ColumnInterface
     */
    function setDefault($default);

    /**
     * Get the auto increment flag
     *
     * @return boolean
     */
    function getAutoIncrement();

    /**
     * Set the auto increment flag
     *
     * @param boolean $flag
     * @return SqlDiff\Database\Table\ColumnInterface
     */
    function setAutoIncrement($flag);

    /**
     * Get the table comment
     *
     * @return string
     */
    function getComment();

    /**
     * Set the table comment
     *
     * @param string $comment
     * @return SqlDiff\Database\Table\ColumnInterface
     */
    function setComment($comment);

    /**
     * Get the column name
     *
     * @return string
     */
    function getName();

    /**
     * Set the column name
     *
     * @param string $name
     * @return SqlDiff\Database\Table\ColumnInterface
     */
    function setName($name);

    /**
     * Get the key
     *
     * @return string
     */
    function getKey();

    /**
     * Set the key
     *
     * @param string $key
     * @return SqlDiff\Database\Table\ColumnInterface
     */
    function setKey($key);

    /**
     * Get the position of this column in the current table
     *
     * @return int
     */
    function getPosition();

    /**
     * Get the previous column (if it exists)
     *
     * @return SqlDiff\Database\Table\ColumnInterface|null
     */
    function getPreviousColumn();

    /**
     * Get the next column (if it exists)
     *
     * @return SqlDiff\Database\Table\ColumnInterface|null
     */
    function getNextColumn();
}
