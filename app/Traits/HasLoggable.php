<?php

namespace App\Traits;

trait HasLoggable
{
    public function createLog(string $text)
    {
        ## Run event log service;
        // $this->logs()->create(['text' => $text]);
    }
}
