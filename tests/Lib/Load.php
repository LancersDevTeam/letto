<?php
namespace Letto\Tests\Lib;

use \Letto\Loader as LettoLoader;

class Load
{
    public $letto;

    public function __construct()
    {
        $this->letto = new LettoLoader();
    }
}
