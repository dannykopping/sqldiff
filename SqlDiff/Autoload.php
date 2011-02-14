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
 * Autoloader used by SqlDiff
 *
 * @package SqlDiff
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 */
class SqlDiff_Autoload {
    /**
     * SqlDiff classes
     *
     * @var array
     */
    static public $classes = array(
        'sqldiff_autoload' => '/Autoload.php',
        'sqldiff_database' => '/Database.php',
        'sqldiff_database_abstract' => '/Database/Abstract.php',
        'sqldiff_database_mysql' => '/Database/Mysql.php',
        'sqldiff_database_table_abstract' => '/Database/Table/Abstract.php',
        'sqldiff_database_table_column_abstract' => '/Database/Table/Column/Abstract.php',
        'sqldiff_database_table_column_mysql' => '/Database/Table/Column/Mysql.php',
        'sqldiff_database_table_index_abstract' => '/Database/Table/Index/Abstract.php',
        'sqldiff_database_table_index_mysql' => '/Database/Table/Index/Mysql.php',
        'sqldiff_database_table_mysql' => '/Database/Table/Mysql.php',
        'sqldiff_exception' => '/Exception.php',
        'sqldiff_textui_command' => '/TextUI/Command.php',
        'sqldiff_textui_formatter' => '/TextUI/Formatter.php',
        'sqldiff_version' => '/Version.php'
    );

    /**
     * Load a class
     *
     * @param string $class The name of the class to load
     */
    static function load($class) {
        $className = strtolower($class);

        if (isset(static::$classes[$className])) {
            require __DIR__ . static::$classes[$className];
        }
    }
}

spl_autoload_register('SqlDiff_Autoload::load');