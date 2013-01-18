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
namespace PopTest\Form;

use Pop\Loader\Autoloader,
    Pop\Form\Element\Radio;

// Require the library's autoloader.
require_once __DIR__ . '/../../../src/Pop/Loader/Autoloader.php';

// Call the autoloader's bootstrap function.
Autoloader::factory()->splAutoloadRegister();

class RadioTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $this->assertInstanceOf('Pop\Form\Element\Radio', new Radio('colors', array('Red', 'Blue', 'Green')));
    }

    public function testMarked()
    {
        $r = new Radio('colors', array('Red', 'Blue', 'Green'), 'Green');
        $this->assertEquals('Green', $r->marked);
    }

    public function testSetMarked()
    {
        $r = new Radio('colors', array('Red' => 'Red', 'Blue' => 'Blue', 'Green' => 'Green'), 'Green');
        $this->assertEquals('Green', $r->marked);
    }

}

