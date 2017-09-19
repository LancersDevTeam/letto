<?php
namespace Letto\Tests\ChatWork;

use Letto\ChatWork\Tags as ChatWorkTags;

class TagsTest extends \PHPUnit_Framework_TestCase
{
    public function testTo()
    {
        $ids = '1';
        $this->assertEquals('[To:1]', ChatWorkTags::to($ids));

        $ids = array('1');
        $this->assertEquals('[To:1]', ChatWorkTags::to($ids));

        $ids = array('123', '456');
        $this->assertEquals('[To:123] [To:456]', ChatWorkTags::to($ids));
    }

    public function testPlain()
    {
        $this->assertEquals('123456789', ChatWorkTags::plain('123456789'));
    }

    public function testInfo()
    {
        $this->assertEquals(
            '[info][title]title title[/title]hello info[/info]',
            ChatWorkTags::info('title title', 'hello info')
        );
    }

    public function testCode()
    {
        $this->assertEquals("[code]var \$hoge = 'uga';[/code]", ChatWorkTags::code("var \$hoge = 'uga';"));
    }
}
