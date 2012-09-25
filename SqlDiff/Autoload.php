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

namespace SqlDiff;

/**
 * Autoloader used by SqlDiff
 *
 * @package SqlDiff
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/sqldiff
 */
class Autoload {
    /**
     * SqlDiff classes
     *
     * @var array
     */
    static public $classes = array(
        'sqldiff\\autoload' => '/Autoload.php',
        'sqldiff\\database' => '/Database.php',
        'sqldiff\\database\\table' => '/Database/Table.php',
        'sqldiff\\database\\table\\column' => '/Database/Table/Column.php',
        'sqldiff\\database\\table\\columninterface' => '/Database/Table/ColumnInterface.php',
        'sqldiff\\database\\table\\index' => '/Database/Table/Index.php',
        'sqldiff\\database\\table\\indexinterface' => '/Database/Table/IndexInterface.php',
        'sqldiff\\database\\tableinterface' => '/Database/TableInterface.php',
        'sqldiff\\databaseinterface' => '/DatabaseInterface.php',
        'sqldiff\\exception' => '/Exception.php',
        'sqldiff\\mysql\\column' => '/Mysql/Column.php',
        'sqldiff\\mysql\\database' => '/Mysql/Database.php',
        'sqldiff\\mysql\\index' => '/Mysql/Index.php',
        'sqldiff\\mysql\\table' => '/Mysql/Table.php',
        'sqldiff\\textui\\command' => '/TextUI/Command.php',
        'sqldiff\\textui\\formatter' => '/TextUI/Formatter.php',
        'sqldiff\\util\\databaseutil' => '/Util/DatabaseUtil.php',
        'sqldiff\\version' => '/Version.php'
    );

    /**
     * Load a class
     *
     * @param string $class The name of the class to load
     */
    static public function load($class) {
        $className = strtolower($class);

        if (isset(static::$classes[$className])) {
            require __DIR__ . static::$classes[$className];
        }
    }
}

spl_autoload_register('SqlDiff\\Autoload::load');
