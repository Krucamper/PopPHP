<?php
/**
 * Pop PHP Framework Unit Tests (http://www.popphp.org/)
 *
 * @link       https://github.com/nicksagona/PopPHP
 * @category   Pop
 * @package    Pop_Test
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace PopTest\Compress;

use Pop\Loader\Autoloader,
    Pop\Compress\Lzf;

// Require the library's autoloader.
require_once __DIR__ . '/../../../src/Pop/Loader/Autoloader.php';

// Call the autoloader's bootstrap function.
Autoloader::factory()->splAutoloadRegister();

class LzfTest extends \PHPUnit_Framework_TestCase
{

    public $string = 'Hello World!';

    public function testLzf()
    {
        if (function_exists('lzf_compress')) {
            $compressed = Lzf::compress($this->string);
            $decompressed = Lzf::decompress($compressed);
            $this->assertEquals($this->string, $decompressed);
        }
    }

}

