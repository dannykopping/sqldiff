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

namespace SqlDiff\Database\Table\Index;

use SqlDiff\Exception;
use SqlDiff\Database\Table\Index;
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
class Mysql extends Index implements IndexInterface {
    /**#@+
     * Different keys
     *
     * @var string
     */
    const FULLTEXT    = 'FULLTEXT KEY';
    const PRIMARY_KEY = 'PRIMARY KEY';
    const UNIQUE      = 'UNIQUE KEY';
    const KEY         = 'KEY';
    /**#@-*/

    /**
     * The name used for primary keys
     *
     * @var string
     */
    const PRIMARY_KEY_NAME = 'PK';

    /**
     * Set the type
     *
     * @param string $type
     * @throws SqlDiff\Exception
     * @return SqlDiff\Database\Table\Index\Mysql
     */
    public function setType($type) {
        switch ($type) {
            case self::PRIMARY_KEY:
            case self::FULLTEXT:
            case self::UNIQUE:
            case self::KEY:
                break;
            default:
                throw new Exception('Unknown index type: ' . $type);
        }

        $this->type = $type;

        return $this;
    }

    /**
     * Fetch the name of this index
     *
     * @return string
     */
    public function getName() {
        $name = parent::getName();

        if (empty($name) && $this->getType() === self::PRIMARY_KEY) {
            return self::PRIMARY_KEY_NAME;
        }

        return $name;
    }

    /**
     * @see SqlDiff\Database\Table\IndexInterface::getDefinition()
     */
    public function getDefinition() {
        $ret = $this->getType();

        if ($ret !== self::PRIMARY_KEY && $this->getName()) {
            $ret .= ' `' . $this->getName() . '`';
        }

        $fieldNames = array();

        foreach ($this->getFields() as $field) {
            $fieldNames[] = $field->getName();
        }

        $ret .= ' (`' . implode('`, `', $fieldNames) . '`)';

        return $ret;
    }
}
