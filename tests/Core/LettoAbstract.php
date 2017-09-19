<?php
namespace Letto\Core;

use \Letto\Core\LettoAbstract;

class LettoAbstractTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $obj = new LettoAbstract(true);
        $this->assertTrue($obj->isDevelopment);

        $obj = new LettoAbstract(false);
        $this->assertFalse($obj->isDevelopment);
    }

    public function testReadOnly()
    {
        $obj = new LettoAbstract(true);
        $obj->foo = 'bar';
        $this->assertEquals('bar', $obj->foo);

        try {
            $obj->foo = 'miko';
        } catch (\Exception $e) {
            $this->assertEquals('[Foo] is ReadOnly.', $e->getMessage());
        }
    }
}
