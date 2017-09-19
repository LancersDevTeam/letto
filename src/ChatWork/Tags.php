<?php
namespace Letto\ChatWork;

class Tags
{
    public static function to($userIds)
    {
        $tags = array();
        if (!is_array($userIds)) {
            $userIds = array($userIds);
        }
        foreach ($userIds as $id) {
            $tags[] = sprintf('[To:%s]', $id);
        }
        return implode(' ', $tags);
    }

    public static function plain($message)
    {
        return $message;
    }

    public static function info($title, $message)
    {
        return vsprintf(
            '[info][title]%s[/title]%s[/info]',
            array($title, $message)
        );
    }

    public static function code($message)
    {
        return sprintf('[code]%s[/code]', $message);
    }
}
