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
 * @package SqlDiff
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/sqldiff
 */
class SqlDiff_Database_Table_Column_MysqlTest extends PHPUnit_Framework_TestCase {
    /**
     * Column instance
     *
     * @var SqlDiff_Database_Table_Column_Mysql
     */
    protected $col = null;

    /**
     * Set up method
     */
    public function setUp() {
        $this->col = new SqlDiff_Database_Table_Column_Mysql();
    }

    /**
     * Tear down method
     */
    public function tearDown() {
        $this->col = null;
    }

    /**
     * Try to set and get the collation attribute
     */
    public function testSetGetCollation() {
        $collation = 'utf8_danish_ci';
        $this->col->setCollation($collation);
        $this->assertSame($collation, $this->col->getCollation());
    }

    /**
     * Try to set and get the charset attribute
     */
    public function testSetGetCharset() {
        $charset = 'ascii';
        $this->col->setCharset($charset);
        $this->assertSame($charset, $this->col->getCharset());
    }

    /**
     * Test the getDefinition method
     *
     * This test will test a lot of different columns to see if the generated SQL is correct
     */
    public function testGetDefinitionForNumerics() {
        $defs = array(
            array('name'          => 'id',
                  'type'          => 'BIGINT (20)',
                  'attribute'     => 'UNSIGNED',
                  'notNull'       => true,
                  'autoIncrement' => true,
                  'sql'           => '`id` BIGINT (20) UNSIGNED NOT NULL AUTO_INCREMENT'),
        );

        $this->_testDefs($defs);
    }

    public function testGetDefinitionForDateAndTime() {
        $defs = array(
            array('name'      => 'colName',
                  'type'      => 'TIMESTAMP',
                  'attribute' => 'ON UPDATE CURRENT_TIMESTAMP',
                  'notNull'   => true,
                  'sql'       => '`colName` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL'),
            array('name'    => 'colName',
                  'type'    => 'DATETIME',
                  'notNull' => true,
                  'default' => '0000-00-00 00:00:00',
                  'sql'     => '`colName` DATETIME NOT NULL DEFAULT \'0000-00-00 00:00:00\''),
        );

        $this->_testDefs($defs);
    }

    public function testGetDefinitionForStrings() {
        $defs = array(
            array('name'    => 'colName',
                  'type'    => 'VARCHAR (200)',
                  'charset' => 'ascii',
                  'collate' => 'ascii_general_ci',
                  'notNull' => true,
                  'sql'     => '`colName` VARCHAR (200) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL'),
        );

        $this->_testDefs($defs);
    }

    /**
     * Test diferent definitions of columns
     *
     * @param array $defs
     */
    protected function _testDefs(array $defs) {
        foreach ($defs as $def) {
            $col = new SqlDiff_Database_Table_Column_Mysql();
            $col->setName($def['name'])
                ->setType($def['type'])
                ->setNotNull($def['notNull']);

            if (isset($def['charset'])) {
                $col->setCharset($def['charset']);
            }

            if (isset($def['collate'])) {
                $col->setCollation($def['collate']);
            }

            if (isset($def['default'])) {
                $col->setDefault($def['default']);
            }

            if (isset($def['attribute'])) {
                $col->setAttribute($def['attribute']);
            }

            if (isset($def['autoIncrement'])) {
                $col->setAutoIncrement($def['autoIncrement']);
            }

            $sql = $col->getDefinition();
            $this->assertSame($def['sql'], $sql);
        }
    }
}