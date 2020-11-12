<?php

namespace App\Import;

use Illuminate\Support\Facades\Redis;

class NotifyImported
{
    private $parsed;

    public function __construct($path)
    {
        $this->parsed = str_replace('/', '', $path) . 'parsed';
        Redis::set($this->parsed, 1);
        Redis::set('test', 'test');
    }
}
