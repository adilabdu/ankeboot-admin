<?php

test('GET request returns 200', function () {
    $response = $this->getWithHeaders('/api/daily-sales?limit=5');

    $response->assertStatus(200);
});

test('GET request returns 422 with invalid parameters', function () {
    $response = $this->getWithHeaders( '/api/daily-sales?limit=5&id=1');

    $response->assertStatus(422);
});
