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
class SqlDiff_Database_Table_AbstractTest extends PHPUnit_Framework_TestCase {
    /**
     * Table instance
     *
     * @var SqlDiff_Database_Table_Abstract
     */
    protected $table = null;

    /**
     * Set up method
     */
    public function setUp() {
        $this->table = $this->getMockForAbstractClass('SqlDiff_Database_Table_Abstract');
    }

    /**
     * Tear down method
     */
    public function tearDown() {
        $this->table = null;
    }

    /**
     * Test the set and get methods for the "name" attribute
     */
    public function testSetGetName() {
        $name = $tableName = 'Name';
        $this->table->setName($name);
        $this->assertSame($name, $this->table->getName());
    }

    /**
     * Test the set and get methods for the "comment" attribute
     */
    public function testSetGetComment() {
        $comment = 'Comment';
        $this->table->setComment($comment);
        $this->assertSame($comment, $this->table->getcomment());
    }

    /**
     * Test the set and get methods for the "database" attribute
     */
    public function testSetGetDatabase() {
        $this->assertNull($this->table->getDatabase());
        $db = $this->getMockForAbstractClass('SqlDiff_Database_Abstract');
        $this->table->setDatabase($db);
        $this->assertSame($db, $this->table->getDatabase());
    }

    /**
     * Make sure the getNumColumns method returns the correct amount of columns
     */
    public function testGetNumColumns() {
        $this->assertSame(0, $this->table->getNumColumns());
        $col = $this->getMockForAbstractClass('SqlDiff_Database_Table_Column_Abstract');
        $this->table->addColumn($col);
        $this->assertSame(1, $this->table->getNumColumns());
    }

    /**
     * Make sure the getNumIndexes method returns the correct amount of indexes
     */
    public function testGetNumIndexes() {
        $this->assertSame(0, $this->table->getNumIndexes());
        $index = $this->getMockForAbstractClass('SqlDiff_Database_Table_Index_Abstract');
        $this->table->addIndex($index);
        $this->assertSame(1, $this->table->getNumIndexes());
    }

    public function testAddHasAndRemoveColumn() {
        $name = 'ColName';

        $col = $this->getMockForAbstractClass('SqlDiff_Database_Table_Column_Abstract');
        $col->setName($name);

        $this->table->addColumn($col);
        $this->assertTrue($this->table->hasColumn($col));
        $this->table->removeColumn($col);
        $this->assertFalse($this->table->hasColumn($col));

        $this->table->addColumn($col);
        $this->assertTrue($this->table->hasColumn($name));
        $this->table->removeColumn($name);
        $this->assertFalse($this->table->hasColumn($name));
    }

    public function testAddAndGetColumn() {
        $name = 'ColName';

        $col = $this->getMockForAbstractClass('SqlDiff_Database_Table_Column_Abstract');
        $col->setName($name);

        $this->table->addColumn($col);
        $this->assertSame($col, $this->table->getColumn($name));
    }

    public function testGetColumns() {
        $name1 = 'ColName1';
        $name2 = 'ColName2';

        $col1 = $this->getMockForAbstractClass('SqlDiff_Database_Table_Column_Abstract');
        $col1->setName($name1);

        $col2 = $this->getMockForAbstractClass('SqlDiff_Database_Table_Column_Abstract');
        $col2->setName($name2);

        $this->table->addColumn($col1)->addColumn($col2);
        $cols = $this->table->getColumns();
        $this->assertInternalType('array', $cols);
        $this->assertSame($col1, $cols[$name1]);
        $this->assertSame($col2, $cols[$name2]);
    }

    public function testGetIndexes() {
        $name1 = 'IndexName1';
        $name2 = 'IndexName2';

        $idx1 = $this->getMockForAbstractClass('SqlDiff_Database_Table_Index_Abstract');
        $idx1->setName($name1);

        $idx2 = $this->getMockForAbstractClass('SqlDiff_Database_Table_Index_Abstract');
        $idx2->setName($name2);

        $this->table->addIndex($idx1)->addIndex($idx2);
        $idxs = $this->table->getIndexes();
        $this->assertInternalType('array', $idxs);
        $this->assertSame($idx1, $idxs[$name1]);
        $this->assertSame($idx2, $idxs[$name2]);
    }

    public function testGetNonExistingColumn() {
        $this->assertNull($this->table->getColumn('nonExistingColumnName'));
    }

    public function testGetNonExistingIndex() {
        $this->assertNull($this->table->getIndex('nonExistingIndexName'));
    }

    public function testAddHasAndRemoveIndex() {
        $name = 'IndexName';

        $idx = $this->getMockForAbstractClass('SqlDiff_Database_Table_Index_Abstract');
        $idx->setName($name);

        $this->table->addIndex($idx);
        $this->assertTrue($this->table->hasIndex($idx));
        $this->table->removeIndex($idx);
        $this->assertFalse($this->table->hasIndex($idx));

        $this->table->addIndex($idx);
        $this->assertTrue($this->table->hasIndex($name));
        $this->table->removeIndex($name);
        $this->assertFalse($this->table->hasIndex($name));
    }

    public function testAddAndGetIndex() {
        $name = 'IndexName';

        $idx = $this->getMockForAbstractClass('SqlDiff_Database_Table_Index_Abstract');
        $idx->setName($name);

        $this->table->addIndex($idx);
        $this->assertSame($idx, $this->table->getIndex($name));
    }

    public function testAddColumns() {
        $col1 = $this->getMock('SqlDiff_Database_Table_Column_Mysql');
        $col1->expects($this->once())->method('getName')->will($this->returnValue('1'));

        $col2 = $this->getMock('SqlDiff_Database_Table_Column_Mysql');
        $col2->expects($this->once())->method('getName')->will($this->returnValue('2'));

        $cols = array(
            $col1,
            $col2,
        );

        $this->table->addColumns($cols);
        $this->assertSame(2, count($this->table->getColumns()));
    }

    public function testAddIndexes() {
        $index1 = $this->getMock('SqlDiff_Database_Table_Index_Mysql');
        $index1->expects($this->once())->method('getName')->will($this->returnValue('1'));

        $index2 = $this->getMock('SqlDiff_Database_Table_Index_Mysql');
        $index2->expects($this->once())->method('getName')->will($this->returnValue('2'));

        $indexes = array(
            $index1,
            $index2,
        );

        $this->table->addIndexes($indexes);
        $this->assertSame(2, count($this->table->getIndexes()));
    }

    public function testGetColumnByPosition() {
        $col1 = $this->getMock('SqlDiff_Database_Table_Column_Mysql');
        $col1->expects($this->any())->method('getName')->will($this->returnValue('1'));

        $col2 = $this->getMock('SqlDiff_Database_Table_Column_Mysql');
        $col2->expects($this->any())->method('getName')->will($this->returnValue('2'));

        $col3 = $this->getMock('SqlDiff_Database_Table_Column_Mysql');
        $col3->expects($this->any())->method('getName')->will($this->returnValue('3'));

        $col4 = $this->getMock('SqlDiff_Database_Table_Column_Mysql');
        $col4->expects($this->any())->method('getName')->will($this->returnValue('4'));

        $this->table->addColumns(array($col1, $col2, $col3, $col4));
        $this->assertSame($col1, $this->table->getColumnByPosition(0));
        $this->assertSame($col2, $this->table->getColumnByPosition(1));
        $this->assertSame($col3, $this->table->getColumnByPosition(2));
        $this->assertSame($col4, $this->table->getColumnByPosition(3));

        $this->table->removeColumn($col2);
        $this->assertSame($col3, $this->table->getColumnByPosition(1));
        $this->assertSame($col4, $this->table->getColumnByPosition(2));
    }

    public function testGetColumnPosition() {
        $col1 = $this->getMock('SqlDiff_Database_Table_Column_Mysql');
        $col1->expects($this->any())->method('getName')->will($this->returnValue('1'));

        $col2 = $this->getMock('SqlDiff_Database_Table_Column_Mysql');
        $col2->expects($this->any())->method('getName')->will($this->returnValue('2'));

        $col3 = $this->getMock('SqlDiff_Database_Table_Column_Mysql');
        $col3->expects($this->any())->method('getName')->will($this->returnValue('3'));

        $col4 = $this->getMock('SqlDiff_Database_Table_Column_Mysql');
        $col4->expects($this->any())->method('getName')->will($this->returnValue('4'));

        $this->table->addColumns(array($col1, $col2, $col3, $col4));
        $this->assertSame(0, $this->table->getColumnPosition($col1));
        $this->assertSame(1, $this->table->getColumnPosition($col2));
        $this->assertSame(2, $this->table->getColumnPosition($col3));
        $this->assertSame(3, $this->table->getColumnPosition($col4));

        $this->table->removeColumn($col2);
        $this->assertSame(0, $this->table->getColumnPosition($col1));
        $this->assertSame(1, $this->table->getColumnPosition($col3));
        $this->assertSame(2, $this->table->getColumnPosition($col4));
    }

    public function testGetColumnByUnexistingPosition() {
        $this->assertNull($this->table->getColumnByPosition(100));
    }

    public function testGetColumnPositionWithUnexistingColumn() {
        $this->assertNull($this->table->getColumnPosition('foobar'));
    }
}