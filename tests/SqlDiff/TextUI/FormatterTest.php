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

namespace SqlDiff\TextUI;

/**
 * @package SqlDiff
 * @author Christer Edvartsen <cogo@starzinger.net>
 * @copyright Copyright (c) 2011, Christer Edvartsen
 * @license http://www.opensource.org/licenses/mit-license MIT License
 * @link https://github.com/christeredvartsen/sqldiff
 */
class FormatterTest extends \PHPUnit_Framework_TestCase {
    /**
     * Formatter instance
     *
     * @var SqlDiff\TextUI\Formatter
     */
    private $formatter;

    /**
     * Setup method
     */
    public function setUp() {
        $this->formatter = new Formatter();
    }

    /**
     * Teardown method
     */
    public function tearDown() {
        $this->formatter = null;
    }

    public function testEnableFormatter() {
        $this->formatter->enable();
        $this->assertTrue($this->formatter->isEnabled());
    }

    public function testDisableFormatter() {
        $this->formatter->disable();
        $this->assertFalse($this->formatter->isEnabled());
    }

    public function testFormatMessageWhenFormatterIsDisabled() {
        $this->formatter->disable();
        $message = 'My message';
        $this->assertSame($message, $this->formatter->format($message, Formatter::DELETE));
    }

    public function testFormatMessageWhenFormatterIsEnabled() {
        $this->formatter->enable();
        $message = 'My message';

        $this->assertContains($message, $this->formatter->format($message, Formatter::ADD));
        $this->assertContains($message, $this->formatter->format($message, Formatter::CHANGE));
        $this->assertContains($message, $this->formatter->format($message, Formatter::DELETE));
        $this->assertSame($message, $this->formatter->format($message, 'unknown type'));
    }
}
