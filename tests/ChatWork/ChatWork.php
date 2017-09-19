<?php
namespace Letto\Tests\ChatWork;

use Letto\ChatWork\ChatWork;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;

class ChatWorkMock extends ChatWork
{
    protected function _send($request)
    {
        return $request;
    }
}

class ChatWorkTest extends \PHPUnit_Framework_TestCase
{
    public function testChatWorkConstants()
    {
        $this->assertEquals('https://api.chatwork.com/', ChatWork::BASE_URL);
        $this->assertEquals('v2', ChatWork::VERSION);

        $chatwork = new ChatWork('1234567890');
        $ref = new \ReflectionClass($chatwork);

        $this->assertEquals('1234567890', $this->getProperty($chatwork, $ref, 'token'));
        $this->assertEquals('https://api.chatwork.com/', $this->getProperty($chatwork, $ref, 'baseUrl'));
        $this->assertEquals('v2', $this->getProperty($chatwork, $ref, 'version'));

        $chatwork = new ChatWork('1234567890', 'http://hoge.com/', 'v1');
        $ref = new \ReflectionClass($chatwork);
        $this->assertEquals('http://hoge.com/', $this->getProperty($chatwork, $ref, 'baseUrl'));
        $this->assertEquals('v1', $this->getProperty($chatwork, $ref, 'version'));
    }

    public function testAddMessage()
    {
        $chatwork = new ChatWork('1234567890');
        $chatwork->addMessage('plain', 'hoge moge miko');

        $ref = new \ReflectionClass($chatwork);
        $this->assertEquals(array('hoge moge miko'), $this->getProperty($chatwork, $ref, 'messages'));

        $chatwork->addMessage('to', '1234');
        $this->assertEquals(
            array(
                'hoge moge miko',
                '[To:1234]'
            ),
            $this->getProperty($chatwork, $ref, 'messages')
        );
    }

    public function testRoom()
    {
        $chatwork = new ChatWorkMock('1234567890');
        $ref = new \ReflectionClass($chatwork);
        $chatwork->room(1234567890);
        $this->assertEquals(1234567890, $this->getProperty($chatwork, $ref, 'roomId'));

        $chatwork->room('0123456789');
        $this->assertEquals('0123456789', $this->getProperty($chatwork, $ref, 'roomId'));
    }

    public function testMessage()
    {
        $chatwork = new ChatWorkMock('1234567890');
        $ref = new \ReflectionClass($chatwork);

        $client = $this->getProperty($chatwork, $ref, 'client');
        $this->assertEquals('https://api.chatwork.com/', $client->getBaseUrl());

        $request = $chatwork->room(1234)->message('plain text hoge moge');
        $this->assertEquals('https://api.chatwork.com/v2/rooms/1234/messages', $request->getUrl());
        $this->assertEquals(array('plain text hoge moge'), $this->getProperty($chatwork, $ref, 'messages'));
    }

    public function testInfo()
    {
        $chatwork = new ChatWorkMock('1234567890');
        $ref = new \ReflectionClass($chatwork);

        $client = $this->getProperty($chatwork, $ref, 'client');
        $this->assertEquals('https://api.chatwork.com/', $client->getBaseUrl());

        $request = $chatwork->room('2345')->info('info', 'text hoge moge');
        $this->assertEquals('https://api.chatwork.com/v2/rooms/2345/messages', $request->getUrl());
        $this->assertEquals(array('[info][title]info[/title]text hoge moge[/info]'), $this->getProperty($chatwork, $ref, 'messages'));
    }

    public function testCode()
    {
        $chatwork = new ChatWorkMock('1234567890');
        $ref = new \ReflectionClass($chatwork);

        $client = $this->getProperty($chatwork, $ref, 'client');
        $this->assertEquals('https://api.chatwork.com/', $client->getBaseUrl());

        $request = $chatwork->room(3456)->code('var $hoge = 12345678;');
        $this->assertEquals('https://api.chatwork.com/v2/rooms/3456/messages', $request->getUrl());
        $this->assertEquals(array('[code]var $hoge = 12345678;[/code]'), $this->getProperty($chatwork, $ref, 'messages'));
    }

    public function testTask()
    {
        $chatwork = new ChatWorkMock('1234567890');
        $ref = new \ReflectionClass($chatwork);

        $client = $this->getProperty($chatwork, $ref, 'client');
        $this->assertEquals('https://api.chatwork.com/', $client->getBaseUrl());

        $request = $chatwork->room('4567')->task(array(1234), 'task');
        $this->assertEquals('https://api.chatwork.com/v2/rooms/4567/tasks', $request->getUrl());
    }

    public function getProperty($chatwork, $ref, $key)
    {
        $prop = $ref->getProperty($key);
        $prop->setAccessible(true);
        return $prop->getValue($chatwork);
    }
}
