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
 * @subpackage Mysql
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/sqldiff
 */

namespace SqlDiff\Mysql;

use SqlDiff\Exception;
use SqlDiff\Util\DatabaseUtil;
use PDO;
use SqlDiff\Database as AbstractDatabase;
use SqlDiff\DatabaseInterface;

/**
 * Class representing a MySQL database
 *
 * @package SqlDiff
 * @subpackage Mysql
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/sqldiff
 */
class Database extends AbstractDatabase implements DatabaseInterface {
    /**
     * Populate the database related metadata
     *
     * @param \SimpleXMLElement $xml The root element of the dump file
     * @param array $filter Filter to use when including/excluding tables
     */
    public function populateDatabase(\SimpleXMLElement $xml, array $filter) {
        // Set name of the database
        $this->setName((string) $xml->database['name']);

        foreach ($xml->database->table_structure as $tableXmlNode) {
            $tableName = (string) $tableXmlNode['name'];

            if (
                (!empty($filter['include']) && !isset($filter['include'][$tableName])) ||
                (!empty($filter['exclude']) && isset($filter['exclude'][$tableName]))
            ) {
                continue;
            }

            $table = $this->createTable($tableXmlNode);
            $this->addTable($table);
        }
    }

    /**
     * Create a table object
     *
     * @param \SimpleXMLElement $xml A node describing a single table
     * @return SqlDiff\Mysql\Table A new table object
     */
    public function createTable(\SimpleXMLElement $xml) {
        $table = new Table();

        $table->setName((string) $xml['name'])
              ->setEngine((string) $xml->options['Engine'])
              ->setAutoIncrement((int) (string) $xml->options['Auto_increment'])
              ->setCollation((string) $xml->options['Collation'])
              ->setComment((string) $xml->options['Comment']);

        // Manage all fields in this table
        foreach ($xml->field as $fieldXmlNode) {
            $field = $this->createTableField($fieldXmlNode);
            $table->addColumn($field);
        }

        // Manage all keys in this table
        foreach ($xml->key as $keyXmlNode) {
            $key = $this->createTableKey($keyXmlNode, $table);

            // Only add key if we have a positive result
            if ($key) {
                $table->addIndex($key);
            }
        }

        return $table;
    }

    /**
     * Create a table field
     *
     * @param \SimpleXMLElement $xml A node describing a single field
     * @return SqlDiff\MysqlColumn A new field object
     */
    public function createTableField(\SimpleXMLElement $xml) {
        $field = new Column();

        $field->setName((string) $xml['Field'])
              ->setType((string) $xml['Type'])
              ->setKey((string) $xml['key'])
              ->setNotNull(((string) $xml['Null'] === 'NO'))
              ->setAutoIncrement(((string) $xml['Extra'] === 'auto_increment'));

        $default = (string) $xml['Default'];
        $field->setDefault($default !== '' ? $default : null);

        return $field;
    }

    /**
     * Create a table key
     *
     * Since the xml dump from mysqldump creates several <key ... /> tags for compound keys we need
     * to make sure we don't add them all. Therefore this method can return false if the table
     * instance already has an index with the same name as the one currently being created.
     *
     * @param \SimpleXMLElement $xml A node describing a single key
     * @param SqlDiff\Mysql\Table $table The table this key will be added to
     * @return SqlDiff\Mysql\Index|boolean Returns an Index instance or false if the key already exists
     */
    public function createTableKey(\SimpleXMLElement $xml, Table $table) {
        // Fetch the keys already attached to the table this key might be added to
        $keys    = $table->getIndexes();
        $keyName = (string) $xml['Key_name'];

        // Fetch the field object the key belongs to
        $field = $table->getColumn((string) $xml['Column_name']);

        // The key already exists in the table. Update the key with another field, and return false
        if (isset($keys[$keyName])) {
            $keys[$keyName]->addField($field);

            return false;
		}

		$key = new Index();
        $key->setName($keyName)
            ->addField($field);

        // Set the correct key type
        if ($keyName === 'PRIMARY') {
            $key->setType(Index::PRIMARY_KEY);
		} else if (DatabaseUtil::isForeignKey($table, $keyName)) {
			$key->setType(Index::FOREIGN_KEY);
        } else if ((string) $xml['Non_unique'] === '0') {
            $key->setType(Index::UNIQUE);
        } else {
            $key->setType(Index::KEY);
        }

        return $key;
    }

    /**
     * @see SqlDiff\DatabaseInterface::parseDump()
     */
    public function parseDump($filePath, array $filter) {
        // Clear the error buffer and Suppress errors
        libxml_clear_errors();
        libxml_use_internal_errors(true);

        $xml = simplexml_load_file($filePath);

        if (!$xml) {
            throw new Exception('According to SimpleXML ' . $filePath . ' is not a valid XML file.');
        }

        if ($xml->getName() !== 'mysqldump') {
            throw new Exception('The XML file does not seem to come from mysqldump.');
        }

        // Add tables, fields and keys to this instance
        $this->populateDatabase($xml, $filter);
    }
}
