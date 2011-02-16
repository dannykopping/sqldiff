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
abstract class SqlDiff_Database_Abstract {
    /**
     * Tables in this database
     *
     * @var array Array of SqlDiff_Database_Table_Abstract objects
     */
    protected $tables = array();

    /**
     * Name of the database
     *
     * @var string
     */
    protected $name = null;

    /**
     * Set the name of the database
     *
     * @param string $name
     * @return SqlDiff_Database_Abstract
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
     * @return array Returns an array of SqlDiff_Database_Table_Abstract objects
     */
    public function getTables() {
        return $this->tables;
    }

    /**
     * Get a single table based on its name
     *
     * @param string $name
     * @return null|SqlDiff_Database_Table_Abstract
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
     * @param SqlDiff_Database_Table_Abstract $table
     * @return SqlDiff_Database_Abstract
     */
    public function addTable(SqlDiff_Database_Table_Abstract $table) {
        $table->setDatabase($this);
        $this->tables[$table->getName()] = $table;

        return $this;
    }

    /**
     * Remove a table from the database
     *
     * @param string|SqlDiff_Database_Table_Abstract $table Either the name of the table or a
     *                                                      valid table object
     * @throws SqlDiff_Exception
     * @return SqlDiff_Database_Abstract
     */
    public function removeTable($table) {
        if ($table instanceof SqlDiff_Database_Table_Abstract) {
            $table = $table->getName();
        }

        // Remove table
        unset($this->tables[$table]);

        return $this;
    }

    /**
     * See if the database has a specific table
     *
     * @param string|SqlDiff_Database_Table_Abstract $table Either the name of the table or a
     *                                                          valid table object
     */
    public function hasTable($table) {
        if ($table instanceof SqlDiff_Database_Table_Abstract) {
            $table = $table->getName();
        }

        return isset($this->tables[$table]);
    }

    /**
     * Parse a dump file
     *
     * If anything goes wrong, throw an SqlDiff_Exception
     *
     * @param string $filePath Path to the dump file
     * @throws SqlDiff_Exception
     */
    abstract public function parseDump($filePath);
}