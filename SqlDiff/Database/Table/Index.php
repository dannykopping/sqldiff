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
abstract class SqlDiff_Database_Table_Index_Abstract {
    /**
     * The table this index belongs to
     *
     * @var SqlDiff_Database_Table_Abstract
     */
    protected $table = null;

    /**
     * The name of the index
     *
     * @var string
     */
    protected $name = null;

    /**
     * The type of the index
     *
     * @var string
     */
    protected $type = null;

    /**
     * The fields this index is composed of
     *
     * @var array An array of SqlDiff_Database_Table_Column_Abstract objects
     */
    protected $fields = array();

    /**
     * Get the table attribute
     *
     * @return SqlDiff_Database_Table_Abstract
     */
    public function getTable() {
        return $this->table;
    }

    /**
     * Set the table attribute
     *
     * @param SqlDiff_Database_Table_Abstract $table
     * @return SqlDiff_Database_Table_Index_Abstract
     */
    public function setTable(SqlDiff_Database_Table_Abstract $table) {
        $this->table = $table;

        return $this;
    }

    /**
     * Get the name attribute
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set the name attribute
     *
     * @param string $name
     * @return SqlDiff_Database_Table_Index_Abstract
     */
    public function setName($name) {
        $this->name = $name;

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
     * Set the type attribute
     *
     * @param string $type
     * @return SqlDiff_Database_Table_Index_Abstract
     */
    public function setType($type) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the fields attribute
     *
     * @return string
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * Set the fields attribute
     *
     * @param string $fields
     * @return SqlDiff_Database_Table_Index_Abstract
     */
    public function setFields(array $fields) {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Add a single field
     *
     * @param SqlDiff_Database_Table_Column_Abstract $field
     * @return SqlDiff_Database_Table_Index_Abstract
     */
    public function addField(SqlDiff_Database_Table_Column_Abstract $field) {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * Add several fields
     *
     * @param array $fields Array of SqlDiff_Database_Table_Column_Abstract objects
     * @return SqlDiff_Database_Table_Index_Abstract
     */
    public function addFields(array $fields) {
        foreach ($fields as $field) {
            $this->addField($field);
        }

        return $this;
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
     * Get the definition of this index
     *
     * @return string
     */
    abstract public function getDefinition();
}