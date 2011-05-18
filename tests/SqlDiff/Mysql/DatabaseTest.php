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

namespace SqlDiff\Mysql;

/**
 * @package SqlDiff
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/sqldiff
 */
class DatabaseTest extends \PHPUnit_Framework_TestCase {
    /**
     * Database instance
     *
     * @var SqlDiff\Mysql\Database
     */
    private $database;

    /**
     * Setup method
     */
    public function setUp() {
        $this->database = new Database();
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
        $this->database->parseDump($path, array('include' => array(), 'exclude' => array()));

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
     * @expectedException SqlDiff\Exception
     */
    public function testParseDumpWithInvalidXmlFile() {
        $this->database->parseDump(__FILE__, array('include' => array(), 'exclude' => array()));
    }

    /**
     * @expectedException SqlDiff\Exception
     */
    public function testParseDumpWithInvalidNamespace() {
        $path = SQLDIFF_FILES . '/missing_namespace.xml';
        $this->database->parseDump($path, array('include' => array(), 'exclude' => array()));
    }

    /**
     * Missing default values
     * If a field has a default value of '0' it is not included in the generated statement.
     * https://github.com/christeredvartsen/sqldiff/issues/2
     */
    public function testIssue2() {
        $xml = simplexml_load_string('<field Field="age" Type="int(11)" Null="NO" Key="" Default="0" Extra="" />');
        $field = $this->database->createTableField($xml);
        $this->assertSame('0', $field->getDefault());
    }

    /**
     * Test the include/exclude funtionality
     */
    public function testPopulateDatabaseUsingFilters() {
        $xml = simplexml_load_file(SQLDIFF_FILES . '/filter_test.xml');
        $db = clone $this->database;
        $db->populateDatabase($xml, array('include' => array(), 'exclude' => array()));
        $this->assertSame(4, $db->getNumTables(), 'Expected 4 tables, got ' . $db->getNumTables());

        $db = clone $this->database;
        $db->populateDatabase($xml, array('include' => array('a' => true), 'exclude' => array()));
        $this->assertSame(1, $db->getNumTables(), 'Expected 1 table, got ' . $db->getNumTables());

        $db = clone $this->database;
        $db->populateDatabase($xml, array('include' => array(), 'exclude' => array('a' => true)));
        $this->assertSame(3, $db->getNumTables(), 'Expected 3 tables, got ' . $db->getNumTables());
    }

    /**
     * Table options are not registered
     * The table options (auto_increment value, table engine and so forth) are not registered when
     * parsing the xml.
     * https://github.com/christeredvartsen/sqldiff/issues/3
     */
    public function testIssue3() {
        $this->database->parseDump(SQLDIFF_FILES . '/sqldiff_source.xml', array('include' => array(), 'exclude' => array()));
        $table = $this->database->getTable('user');

        $this->assertSame('MyISAM', $table->getEngine());
        $this->assertSame(1, $table->getAutoIncrement());
        $this->assertSame('utf8_general_ci', $table->getCollation());
        $this->assertSame('User table', $table->getComment());
    }
}
