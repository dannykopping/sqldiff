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
abstract class SqlDiff_Database_Table_Column_Abstract {
    /**
     * The table object this column belongs to
     *
     * @var SqlDiff_Database_Table_Abstract
     */
    protected $table = null;

    /**
     * Type of column (CHAR, INT, ...)
     *
     * @var string
     */
    protected $type = null;

    /**
     * >ttributes to the column
     *
     * @var string
     */
    protected $attribute = null;

    /**
     * Name of the column
     *
     * @var string
     */
    protected $name = null;

    /**
     * Wether or not the column can be null
     *
     * @var boolean
     */
    protected $notNull = null;

    /**
     * Default value
     *
     * @var string
     */
    protected $default = null;

    /**
     * Wether or not the field is an auto increment
     *
     * @var boolean
     */
    protected $autoIncrement = null;

    /**
     * The column key
     *
     * @var string
     */
    protected $key = null;

    /**
     * Set the table object
     *
     * @param SqlDiff_Database_Table_Abstract $table
     */
    public function setTable(SqlDiff_Database_Table_Abstract $table) {
        $this->table = $table;

        return $this;
    }

    /**
     * Get the table object
     *
     * @return SqlDiff_Database_Table_Abstract
     */
    public function getTable() {
        return $this->table;
    }

    /**
     * Set the type
     *
     * @param string $type
     * @return SqlDiff_Database_Table_Column_Abstract
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the type attribute
     *
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set the attribute
     *
     * @param string $attribute
     * @return SqlDiff_Database_Table_Column_Abstract
     */
    public function setAttribute($attribute) {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * Get the attribute
     *
     * @return string
     */
    public function getAttribute() {
        return $this->attribute;
    }

    /**
     * Get the not null flag
     *
     * @return string
     */
    public function getNotNull() {
        return $this->notNull;
    }

    /**
     * Set the not null flag
     *
     * @param boolean $flag
     * @return SqlDiff_Database_Table_Column_Mysql
     */
    public function setNotNull($flag) {
        $this->notNull = (bool) $flag;

        return $this;
    }

    /**
     * Get the default value
     *
     * @return string
     */
    public function getDefault() {
        return $this->default;
    }

    /**
     * Set the default value
     *
     * @param string $default
     * @return SqlDiff_Database_Table_Column_Mysql
     */
    public function setDefault($default) {
        $this->default = $default;

        return $this;
    }

    /**
     * Get the auto increment flag
     *
     * @return boolean
     */
    public function getAutoIncrement() {
        return $this->autoIncrement;
    }

    /**
     * Set the auto increment flag
     *
     * @param boolean $flag
     * @return SqlDiff_Database_Table_Column_Mysql
     */
    public function setAutoIncrement($flag) {
        $this->autoIncrement = (bool) $flag;

        return $this;
    }

    /**
     * Get the column name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set the column name
     *
     * @param string $name
     * @return SqlDiff_Database_Table_Column_Mysql
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the key
     *
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * Set the key
     *
     * @param string $key
     * @return SqlDiff_Database_Table_Column_Abstract
     */
    public function setKey($key) {
        $this->key = $key;

        return $this;
    }

    /**
     * Get the position of this column in the current table
     *
     * @return int
     */
    public function getPosition() {
        return $this->getTable()->getColumnPosition($this);
    }

    /**
     * Get the previous column (if it exists)
     *
     * @return SqlDiff_Database_Table_Column_Abstract|null
     */
    public function getPreviousColumn() {
        $curPos = $this->getTable()->getColumnPosition($this);

        // If this is the first column, return null
        if (!$curPos) {
            return null;
        }

        return $this->getTable()->getColumnByPosition($curPos - 1);
    }

    /**
     * Get the next column (if it exists)
     *
     * @return SqlDiff_Database_Table_Column_Abstract|null
     */
    public function getNextColumn() {
        $curPos  = $this->getTable()->getColumnPosition($this);
        $numCols = $this->getTable()->getNumColumns();

        // If this is the first column, return null
        if ($curPos < ($numCols - 1)) {
            return $this->getTable()->getColumnByPosition($curPos + 1);
        }

        return null;
    }

    /**
     * The magic to string method is a proxy to the implemention of getDefinition
     *
     * @return string
     */
    public function __toString() {
        return $this->getDefinition();
    }

    /**
     * Get the definition of the column
     *
     * @return string
     */
    abstract public function getDefinition();
}