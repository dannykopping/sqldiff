@echo off
REM SqlDiff
REM 
REM Copyright (c) 2011 Christer Edvartsen <cogo@starzinger.net>
REM 
REM  Permission is hereby granted, free of charge, to any person obtaining a copy
REM  of this software and associated documentation files (the "Software"), to
REM  deal in the Software without restriction, including without limitation the
REM  rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
REM  sell copies of the Software, and to permit persons to whom the Software is
REM  furnished to do so, subject to the following conditions:
REM 
REM  * The above copyright notice and this permission notice shall be included in
REM    all copies or substantial portions of the Software.
REM 
REM  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
REM  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
REM  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
REM  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
REM  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
REM  FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
REM  IN THE SOFTWARE.
 
if "%PHPBIN%" == "" set PHPBIN=@php_bin@
if not exist "%PHPBIN%" if "%PHP_PEAR_PHP_BIN%" neq "" goto USE_PEAR_PATH
GOTO RUN
:USE_PEAR_PATH
set PHPBIN=%PHP_PEAR_PHP_BIN%
:RUN
"%PHPBIN%" "@bin_dir@\sqldiff" %*