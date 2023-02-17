<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function getWithHeaders($uri, array $headers = []): TestResponse
    {
        authenticate();

        return $this->withHeaders([
            'database' => 'mysql',
            'Accept' => 'application/json',
        ])->get($uri, $headers);
    }

    public function postWithHeaders($uri, array $data = [], array $headers = []): TestResponse
    {
        authenticate();

        return $this->withHeaders([
            'database' => 'mysql',
            'Accept' => 'application/json',
        ])->post($uri, $data, $headers);
    }
}
