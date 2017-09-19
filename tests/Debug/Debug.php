<?php
namespace Letto\Tests\Debug;

use \Letto\Debug\Debug;

class DebugTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $debug = new Debug(true);
        $this->assertTrue($debug instanceof Debug);
        $this->assertEquals('/tmp/letto_debug.log', $debug->logPath);

        $debug = new Debug(false);
        try {
            $debug->log('hoge');
        } catch (\Exception $e) {
            $this->assertEquals(
                '$letto->debug is available for development only.',
                $e->getMessage()
            );
        }
    }

    public function testSetLogPath()
    {
        $debug = new Debug(true);
        $this->assertEquals('/tmp/letto_debug.log', $debug->logPath);

        $debug->setLogPath('/path/to/debug.log');
        $this->assertEquals('/path/to/debug.log', $debug->logPath);
    }
}
