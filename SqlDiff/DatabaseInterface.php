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

namespace SqlDiff;

use SqlDiff\TextUI\Command;
use SqlDiff\Database\TableInterface;

/**
 * Interface for database classes
 *
 * @package SqlDiff
 * @subpackage Interfaces
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/sqldiff
 */
interface DatabaseInterface {
    /**
     * Parse a dump file
     *
     * If anything goes wrong, throw an SqlDiff\Exception
     *
     * @param string $filePath Path to the dump file
     * @param array $filter Array with 'include' and 'exclude' keys that both are arrays of tables
     *                      to include/exclude
     * @throws SqlDiff\Exception
     */
    function parseDump($filePath, array $filter);

    /**
     * Set the name of the database
     *
     * @param string $name
     * @return SqlDiff\DatabaseInterface
     */
    function setName($name);

    /**
     * Get the name of the database
     *
     * @return string
     */
    function getName();

    /**
     * Set the current command
     *
     * @param SqlDiff\TextUI\Command $command
     * @return SqlDiff\DatabaseInterface
     */
    function setCommand(Command $command);

    /**
     * Get the command
     *
     * @return SqlDiff\TextUI\Command
     */
    function getCommand();

    /**
     * Get the number of tables in this database
     *
     * @return int
     */
    function getNumTables();

    /**
     * Get all tables
     *
     * @return array Returns an array of SqlDiff\Database\TableInterface objects
     */
    function getTables();

    /**
     * Get a single table based on its name
     *
     * @param string $name
     * @return null|SqlDiff\Database\TableInterface
     */
    function getTable($name);

    /**
     * Add a table to the database
     *
     * @param SqlDiff\Database\TableInterface $table
     * @return SqlDiff\DatabaseInterface
     */
    function addTable(TableInterface $table);

    /**
     * Remove a table from the database
     *
     * @param string|SqlDiff\Database\TableInterface $table Either the name of the table or a valid 
     *                                                      table instance.
     * @throws SqlDiff\Exception
     * @return SqlDiff\DatabaseInterface
     */
    function removeTable($table);

    /**
     * See if the database has a specific table
     *
     * @param string|SqlDiff\Database\TableInterface $table Either the name of the table or a valid 
     *                                                      table instance.
     * @return boolean
     */
    function hasTable($table);
}
