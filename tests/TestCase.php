<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function getWithHeaders($uri, array $headers=[]): TestResponse
    {
        return $this->withHeaders([
            'database' => 'mysql',
            'Accept' => 'application/json'
        ])->get($uri, $headers);
    }
}
