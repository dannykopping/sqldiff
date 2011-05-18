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

/**
 * @package SqlDiff
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/sqldiff
 */
class MysqlTest extends \PHPUnit_Framework_TestCase {
    /**
     * Index instance
     *
     * @var SqlDiff\Database\Table\Index\Mysql
     */
    private $index;

    /**
     * Set up method
     */
    public function setUp() {
        $this->index = new Mysql();
    }

    /**
     * Tear down method
     */
    public function tearDown() {
        $this->index = null;
    }

    /**
     * Try to set and get the type attribute
     */
    public function testSetGetType() {
        $type = Mysql::PRIMARY_KEY;
        $this->index->setType($type);
        $this->assertSame($type, $this->index->getType());
    }

    /**
     * Try to set an invalid type
     *
     * @expectedException SqlDiff\Exception
     */
    public function testSetInvalidType() {
        $type = 'invalid type';
        $this->index->setType($type);
    }

    /**
     * Try to set and get the name attribute
     */
    public function testGetName() {
        $this->assertNull($this->index->getName());
        $name = 'IndexName';
        $this->index->setName($name);
        $this->assertSame($name, $this->index->getName());
    }

    /**
     * Try to get the name attribute when we are dealing with a primary key
     */
    public function testGetNameWhenTypeIsPrimaryKey() {
        $this->index->setType(Mysql::PRIMARY_KEY);
        $this->assertSame(Mysql::PRIMARY_KEY_NAME, $this->index->getName());
    }

    public function testGetDefinitionForFulltextIndex() {
        $indexName  = 'indexName';
        $columnName = 'columnName';

        $this->index->setName($indexName);

        $col1 = $this->getMock('SqlDiff\\Database\\Table\\Column\\Mysql', array('getName'));
        $col1->expects($this->any())->method('getName')->will($this->returnValue($columnName));

        $this->index->setFields(array($col1));
        $this->index->setType(Mysql::FULLTEXT);

        $this->assertSame('FULLTEXT KEY `' . $indexName . '` (`' . $columnName . '`)', $this->index->getDefinition());

        $this->index->setType(Mysql::PRIMARY_KEY);
        $this->assertSame('PRIMARY KEY (`' . $columnName . '`)', $this->index->getDefinition());

        $this->index->setType(Mysql::UNIQUE);
        $this->assertSame('UNIQUE KEY `' . $indexName . '` (`' . $columnName . '`)', $this->index->getDefinition());

        $this->index->setType(Mysql::KEY);
        $this->assertSame('KEY `' . $indexName . '` (`' . $columnName . '`)', $this->index->getDefinition());

        $column2Name = 'column2Name';
        $column3Name = 'column3Name';

        $col2 = $this->getMock('SqlDiff\\Database\\Table\\Column\\Mysql', array('getName'));
        $col2->expects($this->any())->method('getName')->will($this->returnValue($column2Name));

        $col3 = $this->getMock('SqlDiff\\Database\\Table\\Column\\Mysql', array('getName'));
        $col3->expects($this->any())->method('getName')->will($this->returnValue($column3Name));

        $this->index->addFields(array($col2, $col3))->setType(Mysql::UNIQUE);

        $this->assertSame('UNIQUE KEY `indexName` (`columnName`, `column2Name`, `column3Name`)', $this->index->getDefinition());
    }
}
