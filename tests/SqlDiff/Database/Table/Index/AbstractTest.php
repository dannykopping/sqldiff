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
 * @package SqlDiff
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 */
class SqlDiff_Database_Table_Index_AbstractTest extends PHPUnit_Framework_TestCase {
    /**
     * Index instance
     *
     * @var SqlDiff_Database_Table_Index_Abstract
     */
    public $index = null;

    /**
     * Set up method
     */
    public function setUp() {
        $this->index = $this->getMockForAbstractClass('SqlDiff_Database_Table_Index_Abstract');
    }

    /**
     * Tear down method
     */
    public function tearDown() {
        $this->index = null;
    }

    public function testSetGetTable() {
        $table = $this->getMockForAbstractClass('SqlDiff_Database_Table_Abstract');
        $this->index->setTable($table);
        $this->assertSame($table, $this->index->getTable());
    }

    public function testSetGetName() {
        $name = 'IndexName';
        $this->index->setName($name);
        $this->assertSame($name, $this->index->getName());
    }

    public function testSetGetType() {
        $type = 'mysql';
        $this->index->setType($type);
        $this->assertSame($type, $this->index->getType());
    }

    public function testSetGetFields() {
        $cols = array(
            $this->getMockForAbstractClass('SqlDiff_Database_Table_Abstract'),
            $this->getMockForAbstractClass('SqlDiff_Database_Table_Abstract'),
            $this->getMockForAbstractClass('SqlDiff_Database_Table_Abstract'),
        );
        $this->index->setFields($cols);
        $this->assertSame($cols, $this->index->getFields());
    }

    /**
     * Test the magic to string method
     */
    public function testMagicToStringMethod() {
        $this->index->expects($this->once())->method('getDefinition')->will($this->returnValue('Some value'));

        // Cast to string to make sure the getDefinition method is called once
        (string) $this->index;
    }
}