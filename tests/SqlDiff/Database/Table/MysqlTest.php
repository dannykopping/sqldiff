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

namespace SqlDiff\Database\Table;

use SqlDiff\Database\Table\Index\Mysql as MysqlIndex;

/**
 * @package SqlDiff
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/sqldiff
 */
class MysqlTest extends \PHPUnit_Framework_TestCase {
    /**
     * Table instance
     *
     * @var SqlDiff\Database\Table\Mysql
     */
    private $table;

    /**
     * Set up method
     */
    public function setUp() {
        $this->table = new Mysql();
    }

    /**
     * Tear down method
     */
    public function tearDown() {
        $this->table = null;
    }

    /**
     * Try to set and get the engine attribute
     */
    public function testSetGetEngine() {
        $engine = 'MyISAM';
        $this->table->setEngine($engine);
        $this->assertSame($engine, $this->table->getEngine());
    }

    /**
     * Try to set and get the auto increment value
     */
    public function testSetGetAutoIncrement() {
        $increment = 123;
        $this->table->setAutoIncrement($increment);
        $this->assertSame($increment, $this->table->getAutoIncrement());
    }

    /**
     * Try to set and get the deafault charset attribute
     */
    public function testSetGetDefaultCharset() {
        $charset = 'UTF-8';
        $this->table->setDefaultCharset($charset);
        $this->assertSame($charset, $this->table->getDefaultCharset());
    }

    /**
     * Try to set and get the collation attribute
     */
    public function testSetGetCollation() {
        $collation = 'utf8_danish_ci';
        $this->table->setCollation($collation);
        $this->assertSame($collation, $this->table->getCollation());
    }

    /**
     * Try to set and get the checksum option
     */
    public function testSetGetChecksum() {
        $this->assertNull($this->table->getChecksum());
        $this->table->setChecksum(true);
        $this->assertTrue($this->table->getChecksum());
        $this->table->setChecksum(false);
        $this->assertFalse($this->table->getChecksum());
    }

    /**
     * Try to set and get the delay key write option
     */
    public function testSetGetDelayKeyWrite() {
        $this->assertNull($this->table->getDelayKeyWrite());
        $this->table->setDelayKeyWrite(true);
        $this->assertTrue($this->table->getDelayKeyWrite());
        $this->table->setDelayKeyWrite(false);
        $this->assertFalse($this->table->getDelayKeyWrite());
    }

    /**
     * Try to set and get the fixed row format option
     */
    public function testSetGetFixedRowFormat() {
        $this->assertNull($this->table->getFixedRowFormat());
        $this->table->setFixedRowFormat(true);
        $this->assertTrue($this->table->getFixedRowFormat());
        $this->table->setFixedRowFormat(false);
        $this->assertFalse($this->table->getFixedRowFormat());
    }

    /**
     * Get a CREATE TABLE query with all options set
     */
    public function testGetCreateTableSqlWithAllOptions() {
        $tableName      = 'tableName';
        $tableEngine    = 'MyISAM';
        $autoIncrement  = 123;
        $charset        = 'UTF-8';
        $collation      = 'utf8_danish_ci';
        $comment        = 'Table comment';
        $checksum       = true;
        $delayKeyWrite  = true;
        $fixedRowFormat = true;

        $this->table->setName($tableName)
                    ->setEngine($tableEngine)
                    ->setAutoIncrement($autoIncrement)
                    ->setDefaultCharset($charset)
                    ->setCollation($collation)
                    ->setComment($comment)
                    ->setChecksum($checksum)
                    ->setDelayKeyWrite($delayKeyWrite)
                    ->setFixedRowFormat($fixedRowFormat);

        // Add some columns
        $id = $this->getMock('SqlDiff\\Database\\Table\\Column\\Mysql', array('getName', 'getDefinition'));
        $id->expects($this->once())->method('getName')->will($this->returnValue('id'));
        $id->expects($this->once())->method('getDefinition')->will($this->returnValue('`id` INT UNSIGNED NOT NULL AUTO_INCREMENT'));

        $name = $this->getMock('SqlDiff\\Database\\Table\\Column\\Mysql', array('getName', 'getDefinition'));
        $name->expects($this->once())->method('getName')->will($this->returnValue('name'));
        $name->expects($this->once())->method('getDefinition')->will($this->returnValue('`name` VARCHAR (100) NOT NULL'));

        $email = $this->getMock('SqlDiff\\Database\\Table\\Column\\Mysql', array('getName', 'getDefinition'));
        $email->expects($this->once())->method('getName')->will($this->returnValue('email'));
        $email->expects($this->once())->method('getDefinition')->will($this->returnValue('`email` VARCHAR (200) NOT NULL'));

        $idIndex = $this->getMock('SqlDiff\\Database\\Table\\Index\\Mysql', array('getName', 'getDefinition'));
        $idIndex->expects($this->once())->method('getName')->will($this->returnValue('PK'));
        $idIndex->expects($this->once())->method('getDefinition')->will($this->returnValue('PRIMARY KEY `id`'));

        $nameIndex = $this->getMock('SqlDiff\\Database\\Table\\Index\\Mysql', array('getName', 'getDefinition'));
        $nameIndex->expects($this->once())->method('getName')->will($this->returnValue('name'));
        $nameIndex->expects($this->once())->method('getDefinition')->will($this->returnValue('KEY `name` (`name`)'));

        $emailIndex = $this->getMock('SqlDiff\\Database\\Table\\Index\\Mysql', array('getName', 'getDefinition'));
        $emailIndex->expects($this->once())->method('getName')->will($this->returnValue('email'));
        $emailIndex->expects($this->once())->method('getDefinition')->will($this->returnValue('UNIQUE KEY `email` (`email`)'));

        $this->table->addColumns(array($id, $name, $email))->addIndexes(array($idIndex, $nameIndex, $emailIndex));

        $sql = $this->table->getCreateTableSql();

        $expectedSql = "CREATE TABLE `tableName` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
`name` VARCHAR (100) NOT NULL,
`email` VARCHAR (200) NOT NULL,
PRIMARY KEY `id`,
KEY `name` (`name`),
UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=123 DEFAULT CHARSET=UTF-8 COLLATE=utf8_danish_ci COMMENT='Table comment' CHEKCSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=FIXED;";

        $this->assertSame($expectedSql, $sql);
    }

    /**
     * Get an ALTER TABLE statement
     */
    public function testGetAddColumnSql() {
        $this->table->setName('tableName');

        $col1 = $this->getMock('SqlDiff\\Database\\Table\\Column\\Mysql', array('getDefinition', 'getName'));
        $col1->expects($this->any())->method('getName')->will($this->returnValue('name'));
        $col1->expects($this->any())->method('getDefinition')->will($this->returnValue('`name` VARCHAR (100) NOT NULL'));

        $col2 = $this->getMock('SqlDiff\\Database\\Table\\Column\\Mysql', array('getDefinition', 'getPreviousColumn', 'getName'));
        $col2->expects($this->any())->method('getName')->will($this->returnValue('password'));
        $col2->expects($this->any())->method('getDefinition')->will($this->returnValue('`password` VARCHAR (32) NOT NULL'));
        $col2->expects($this->any())->method('getPreviousColumn')->will($this->returnValue($col1));

        $this->table->addColumns(array($col1, $col2));

        $this->assertSame("ALTER TABLE `tableName` ADD `name` VARCHAR (100) NOT NULL FIRST;", $this->table->getAddColumnSql($col1));
        $this->assertSame("ALTER TABLE `tableName` ADD `password` VARCHAR (32) NOT NULL AFTER `name`;", $this->table->getAddColumnSql($col2));
    }

    public function testGetAddColumnSqlWhenColumnIsAutoIncrement() {
        $index = $this->getMock('SqlDiff\\Database\\Table\\Index\\Mysql', array('getType', 'getName'));
        $index->expects($this->any())->method('getName')->will($this->returnValue(''));
        $index->expects($this->any())->method('getType')->will($this->returnValue(MysqlIndex::PRIMARY_KEY));

        $sourceTable = $this->getMock('SqlDiff\\Database\\Table\\Mysql', array('getIndexes', 'removeIndex'));
        $sourceTable->expects($this->once())->method('getIndexes')->will($this->returnValue(array($index)));
        $sourceTable->expects($this->once())->method('removeIndex')->with($index);

        $col = $this->getMock('SqlDiff\\Database\\Table\\Column\\Mysql', array('getName', 'getDefinition', 'getAutoIncrement', 'getTable'));
        $col->expects($this->any())->method('getAutoIncrement')->will($this->returnValue(true));
        $col->expects($this->any())->method('getTable')->will($this->returnValue($sourceTable));
        $col->expects($this->any())->method('getName')->will($this->returnValue('id'));
        $col->expects($this->any())->method('getDefinition')->will($this->returnValue('`id` int(11) NOT NULL AUTO_INCREMENT'));

        $this->table->setName('target');
        $this->assertSame(
            'ALTER TABLE `target` ADD `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;',
            $this->table->getAddColumnSql($col)
        );
    }

    /**
     * Get an ALTER TABLE statement
     */
    public function testGetChangeColumnSql() {
        $this->table->setName('tableName');

        $col = $this->getMock('SqlDiff\\Database\\Table\\Column\\Mysql', array('getName', 'getDefinition'));
        $col->expects($this->once())->method('getDefinition')->will($this->returnValue('`name` VARCHAR (100) NOT NULL'));
        $col->expects($this->once())->method('getName')->will($this->returnValue('name'));

        $sql = $this->table->getChangeColumnSql($col);
        $expectedSql = "ALTER TABLE `tableName` CHANGE `name` `name` VARCHAR (100) NOT NULL;";

        $this->assertSame($expectedSql, $sql);
    }

    /**
     * Get an ALTER TABLE statement
     */
    public function testGetDropColumnSql() {
        $this->table->setName('tableName');

        $col = $this->getMock('SqlDiff\\Database\\Table\\Column\\Mysql', array('getName'));
        $col->expects($this->once())->method('getName')->will($this->returnValue('name'));

        $sql = $this->table->getDropColumnSql($col);
        $expectedSql = "ALTER TABLE `tableName` DROP `name`;";

        $this->assertSame($expectedSql, $sql);
    }

    /**
     * Get an ALTER TABLE statement
     */
    public function testGetAddIndexSql() {
        $this->table->setName('tableName');

        $index = $this->getMock('SqlDiff\\Database\\Table\\Index\\Mysql', array('getDefinition'));
        $index->expects($this->once())->method('getDefinition')->will($this->returnValue('UNIQUE (`name`)'));

        $sql = $this->table->getAddIndexSql($index);
        $expectedSql = "ALTER TABLE `tableName` ADD UNIQUE (`name`);";

        $this->assertSame($expectedSql, $sql);
    }

    /**
     * Get an ALTER TABLE statement
     */
    public function testGetChangeIndexSql() {
        $this->table->setName('tableName');

        $index = $this->getMock('SqlDiff\\Database\\Table\\Index\\Mysql', array('getName', 'getDefinition'));
        $index->expects($this->once())->method('getName')->will($this->returnValue('name'));
        $index->expects($this->once())->method('getDefinition')->will($this->returnValue('UNIQUE (`name`)'));

        $sql = $this->table->getChangeIndexSql($index);
        $expectedSql = "ALTER TABLE `tableName` DROP INDEX `name`, ADD UNIQUE (`name`);";

        $this->assertSame($expectedSql, $sql);
    }

    /**
     * Get an ALTER TABLE statement
     */
    public function testGetDropIndexSql() {
        $this->table->setName('tableName');

        $index = $this->getMock('SqlDiff\\Database\\Table\\Index\\Mysql', array('getName', 'getType'));
        $index->expects($this->once())->method('getName')->will($this->returnValue('name'));
        $index->expects($this->once())->method('getType')->will($this->returnValue(MysqlIndex::KEY));

        $sql = $this->table->getDropIndexSql($index);
        $expectedSql = "ALTER TABLE `tableName` DROP INDEX `name`;";

        $this->assertSame($expectedSql, $sql);
    }

    /**
     * Get an ALTER TABLE statement
     */
    public function testGetDropIndexSqlWhenIndexIsPrimaryKey() {
        $this->table->setName('tableName');

        $index = $this->getMock('SqlDiff\\Database\\Table\\Index\\Mysql', array('getType'));
        $index->expects($this->once())->method('getType')->will($this->returnValue(MysqlIndex::PRIMARY_KEY));

        $sql = $this->table->getDropIndexSql($index);
        $expectedSql = "ALTER TABLE `tableName` DROP PRIMARY KEY;";

        $this->assertSame($expectedSql, $sql);
    }

    /**
     * Test the DROP TABLE syntax
     */
    public function testGetDropTableSql() {
        $name = 'tableName';
        $this->table->setName($name);
        $sql = $this->table->getDropTableSql();
        $this->assertSame(sprintf('DROP TABLE `%s`', $name), $sql);
    }

    /**
     * Try to get extra queries specific to MySQL
     */
    public function testGetExtraQueries() {
        $formatter = $this->getMock('SqlDiff\\TextUI\\Formatter', array('format'));
        $formatter->expects($this->once())->method('format')->will($this->returnArgument(0));

        $command = $this->getMock('SqlDiff\\TextUI\\Command', array('getFormatter'));
        $command->expects($this->once())->method('getFormatter')->will($this->returnValue($formatter));

        $db = $this->getMock('SqlDiff\\Database\\Mysql', array('getCommand'));
        $db->expects($this->once())->method('getCommand')->will($this->returnValue($command));

        $this->table->setEngine('InnoDB')->setName('user')->setDatabase($db);
        $target = $this->getMock('SqlDiff\\Database\\Table\\Mysql', array('getEngine'));
        $target->expects($this->exactly(2))->method('getEngine')->will($this->returnValue('MyISAM'));

        $this->assertSame(array('ALTER TABLE `user` ENGINE = MyISAM'), $this->table->getExtraQueries($target));
    }
}
