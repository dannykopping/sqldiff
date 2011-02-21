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
class SqlDiff_Database_AbstractTest extends PHPUnit_Framework_TestCase {
    /**
     * Database instance
     *
     * @var SqlDiff_Database_Abstract
     */
    protected $db = null;

    /**
     * Set up method
     */
    public function setUp() {
        $this->db = $this->getMockForAbstractClass('SqlDiff_Database_Abstract');
        $this->db->expects($this->any())->method('foo')->will($this->returnValue('1'));
    }

    /**
     * Tear down method
     */
    public function tearDown() {
        $this->db = null;
    }

    /**
     * Test the set and get methods for the "name" attribute
     */
    public function testSetGetName() {
        $name = 'dbName';

        $this->db->setName($name);
        $this->assertSame($name, $this->db->getName());
    }

    /**
     * Try to fetch number of tables added when none have been added yet
     */
    public function testGetNumTablesWithNoTablesAdded() {
        $this->assertSame(0, $this->db->getNumTables(), 'Expected 0 tables, got ' . $this->db->getNumTables());
    }

    /**
     * Add some tables, then check the getNumTables method
     */
    public function testAddTableThenGetNumTables() {
        $table = $this->getMockForAbstractClass('SqlDiff_Database_Table_Abstract');
        $this->db->addTable($table);
        $this->assertSame(1, $this->db->getNumTables(), 'Expected 1 table, got ' . $this->db->getNumTables());
        $this->db->removeTable($table);
        $this->assertSame(0, $this->db->getNumTables(), 'Expected 0 tables, got ' . $this->db->getNumTables());
    }

    /**
     * Try to remove a table using its name as argument to the removeTable method
     */
    public function testRemoveTableUsingNameAsArgument() {
        $table = $this->getMockForAbstractClass('SqlDiff_Database_Table_Abstract');
        $tableName = 'Name';
        $table->setName($tableName);
        $this->db->addTable($table);
        $this->assertSame(1, $this->db->getNumTables(), 'Expected 1 table, got ' . $this->db->getNumTables());
        $this->db->removeTable($tableName);
        $this->assertSame(0, $this->db->getNumTables(), 'Expected 0 tables, got ' . $this->db->getNumTables());
    }

    /**
     * Test the hasTable method
     */
    public function testHasTable() {
        $table = $this->getMockForAbstractClass('SqlDiff_Database_Table_Abstract');
        $tableName = 'Name';
        $table->setName($tableName);

        $this->assertFalse($this->db->hasTable($tableName));
        $this->assertFalse($this->db->hasTable($table));

        $this->db->addTable($table);

        $this->assertTrue($this->db->hasTable($tableName));
        $this->assertTrue($this->db->hasTable($table));
    }

    /**
     * Try to add a table then get it back using the table name
     */
    public function testAddAndGetTable() {
        $table = $this->getMockForAbstractClass('SqlDiff_Database_Table_Abstract');
        $tableName = 'Name';
        $table->setName($tableName);
        $this->db->addTable($table);

        $this->assertSame($table, $this->db->getTable($tableName));
    }

    /**
     * Try to get a table that does not exist
     */
    public function testGetTableThatDoesNotExist() {
        $this->assertNull($this->db->getTable('foobar'));
    }

    /**
     * Try to fetch all tables
     */
    public function testGetTables() {
        $tables = $this->db->getTables();
        $this->assertInternalType('array', $tables);
    }

    public function testSetGetCommand() {
        $command = $this->getMock('SqlDiff_TextUI_Command');
        $this->db->setCommand($command);
        $this->assertSame($command, $this->db->getCommand());
    }
}