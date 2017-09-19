<?php
namespace Letto\Debug;

use \Monolog\Formatter\LineFormatter as MonologLineFormatter;

class LineFormatter extends MonologLineFormatter
{
    protected function convertToString($data)
    {
        if (null === $data || is_bool($data)) {
            return var_export($data, true);
        }

        return $data;
    }

    protected function replaceNewlines($str)
    {
        return $str;
    }
}
