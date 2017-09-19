<?php
namespace Letto\Tests;

use \Letto\Loader as LettoLoader;
use \Letto\ChatWork\ChatWork;
use \Letto\Config\Config;
use \Letto\Debug\Debug;

class LoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testLazyLoad()
    {
        $letto = new LettoLoader(true);
        $this->assertTrue($letto instanceof LettoLoader);

        $this->assertTrue($letto->debug instanceof Debug);
        $this->assertTrue($letto->config instanceof Config);
        $this->assertTrue($letto->chatwork instanceof ChatWork);

        $this->assertNull($letto->foo);
    }
}
