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
namespace PopTest\Auth;

use Pop\Loader\Autoloader;
use Pop\Auth\Resource;

// Require the library's autoloader.
require_once __DIR__ . '/../../../src/Pop/Loader/Autoloader.php';

// Call the autoloader's bootstrap function.
Autoloader::factory()->splAutoloadRegister();

class ResourceTest extends \PHPUnit_Framework_TestCase
{

    public function testFactory()
    {
        $this->assertInstanceOf('Pop\Auth\Resource', Resource::factory('page'));
    }

    public function testGetName()
    {
        $p = Resource::factory('page');
        $this->assertEquals('page', $p->getName());
    }

    public function testToString()
    {
        $p = Resource::factory('page');
        $this->assertEquals('page', (string)$p);
    }

}

