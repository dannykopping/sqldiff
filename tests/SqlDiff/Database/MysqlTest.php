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
class SqlDiff_Database_MysqlTest extends PHPUnit_Framework_TestCase {
    /**
     * Database instance
     *
     * @var SqlDiff_Database_Mysql
     */
    protected $database = null;

    /**
     * Setup method
     */
    public function setUp() {
        $this->database = new SqlDiff_Database_Mysql();
    }

    /**
     * Teardown method
     */
    public function tearDown() {
        $this->database = null;
    }

    /**
     * Try to parse a complete dump file
     */
    public function testParseCompleteDump() {
        $path = SQLDIFF_FILES . '/compound_key.xml';
        $this->database->parseDump($path);

        $this->assertSame('test', $this->database->getName());
        $this->assertSame(1, $this->database->getNumTables());
        $this->assertTrue($this->database->hasTable('test'));

        $table = $this->database->getTable('test');
        $this->assertSame(4, $table->getNumColumns());
        $this->assertSame(3, $table->getNumIndexes());

        $id = $table->getColumn('id');
        $name = $table->getColumn('name');
        $password = $table->getColumn('password');
        $username = $table->getColumn('username');

        // Test the field types
        $this->assertSame('int(10) unsigned', $id->getType());
        $this->assertSame('varchar(100)', $name->getType());
        $this->assertSame('char(32)', $password->getType());
        $this->assertSame('varchar(20)', $username->getType());

        $this->assertTrue($id->getNotNull());
        $this->assertTrue($name->getNotNull());
        $this->assertTrue($password->getNotNull());
        $this->assertFalse($username->getNotNull());

        $this->assertTrue($id->getAutoIncrement());
        $this->assertFalse($name->getAutoIncrement());
        $this->assertFalse($password->getAutoIncrement());
        $this->assertFalse($username->getAutoIncrement());
    }

    /**
     * @expectedException SqlDiff_Exception
     */
    public function testParseDumpWithInvalidXmlFile() {
        $this->database->parseDump(__FILE__);
    }

    /**
     * @expectedException SqlDiff_Exception
     */
    public function testParseDumpWithInvalidNamespace() {
        $path = SQLDIFF_FILES . '/missing_namespace.xml';
        $this->database->parseDump($path);
    }
}