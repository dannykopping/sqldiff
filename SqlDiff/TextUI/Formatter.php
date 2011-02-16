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
 * Formatter for the TextUI
 *
 * @package SqlDiff
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/sqldiff
 */
class SqlDiff_TextUI_Formatter {
    /**#@+
     * Message type
     *
     * @var int
     */
    const ADD    = 1;
    const CHANGE = 2;
    const DELETE = 3;
    /**#@-*/

    /**
     * Colors for the different types
     *
     * @var array
     */
    protected $colors = array(
        self::ADD    => 32, // Green
        self::CHANGE => 33, // Yellow
        self::DELETE => 31, // Red
    );

    /**
     * Enabled flag
     *
     * @var boolean
     */
    protected $enabled = false;

    /**
     * Disable the formatter
     *
     * @return SqlDiff_TextUI_Formatter
     */
    public function disable() {
        $this->enabled = false;

        return $this;
    }

    /**
     * Enable the formatter
     *
     * @return SqlDiff_TextUI_Formatter
     */
    public function enable() {
        $this->enabled = true;

        return $this;
    }

    /**
     * Wether or not the formatter is enabled
     *
     * @return boolean
     */
    public function isEnabled() {
        return $this->enabled;
    }

    /**
     * Format a message
     *
     * @param string $message The message to format
     * @param int $type One of the defined constants in this class
     * @return string
     */
    public function format($message, $type) {
        if (!$this->enabled) {
            return $message;
        }

        switch ($type) {
            case self::ADD:
            case self::CHANGE:
            case self::DELETE:
                return "\033[" . $this->colors[$type] . 'm' . $message . "\033[0m";
            default:
                return $message;
        }
    }
}