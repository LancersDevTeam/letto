<?php
namespace Letto\Debug;

use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;
use \Monolog\Handler\ChromePHPHandler;
use \Letto\Core\LettoAbstract;
use \Letto\Debug\LineFormatter;

class Debug extends LettoAbstract
{
    public $logPath = '/tmp/letto_debug.log';
    public static $callIndex = 0;

    /**
     * set dump file path
     *
     * @param   string  $filePath
     */
    public function setLogPath($logPath)
    {
        $this->logPath = $logPath;
    }

    /**
     * dump debug log
     *
     * @param   mixed   $messages dumpdata
     */
    public function log($messages)
    {
        if ($this->isDevelopment) {
            $logger = new Logger('Debug');
            $backtrace = debug_backtrace(false);
            $logger->pushHandler($this->ChromePHPHandler($backtrace));
            $logger->pushHandler($this->StreamHandler($backtrace));
            $logger->debug(gettype($messages), array($messages));
        } else {
            throw new \Exception('$letto->debug is available for development only.');
        }
    }

    /**
     * StreamHandler
     *
     * @param   mixed   $backtrace  debug_backtrace()
     * @return  mixed
     */
    private function StreamHandler($backtrace)
    {
        $handler = new StreamHandler($this->logPath);
        $streamRecord = array(
            'extra' => array(
                'file'  => $backtrace[static::$callIndex]['file'],
                'line'  => $backtrace[static::$callIndex]['line'],
            ),
        );
        $formatter = new LineFormatter("[%datetime%] %file% on line %line%\n%channel%: %logdata%\n");
        $handler->setFormatter($formatter);
        $handler->pushProcessor(function ($record) use ($streamRecord) {
            $record['file'] = $streamRecord['extra']['file'];
            $record['line'] = $streamRecord['extra']['line'];
            $record['logdata'] = print_r($record['context'][0], true);
            return $record;
        });
        return $handler;
    }

    /**
     * ChromePHPHandler
     *
     * @param   mixed   $backtrace  debug_backtrace()
     * @return  mixed
     */
    private function chromePHPHandler($backtrace)
    {
        $handler = new ChromePHPHandler();
        $chromeRecord = array(
            'extra' => array(
                'file'  => $backtrace[static::$callIndex]['file'],
                'line'  => $backtrace[static::$callIndex]['line'],
            ),
        );
        $handler->pushProcessor(function ($record) use ($chromeRecord) {
            $record = array_merge($record, $chromeRecord);
            if (is_object($record['context'][0])) {
                $record['message'] = $record['context'][0];
            }
            $record['context'] = $record['context'][0];
            return $record;
        });
        return $handler;
    }
}
