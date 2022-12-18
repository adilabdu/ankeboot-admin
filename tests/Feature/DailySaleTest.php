<?php

namespace Tests\Feature;

use App\Models\DailySale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DailySaleTest extends TestCase
{

    public function test_daily_sale_get_request_returns_200_status_code(): void
    {

        Sanctum::actingAs(User::first());

        $response = $this->getWithHeaders(
            '/api/daily-sales?limit=5'
        );

        $response->assertStatus(200);
    }

    public function test_daily_sale_get_request_returns_422_with_invalid_request_body(): void
    {

        Sanctum::actingAs(User::first());

        $response = $this->getWithHeaders(
            '/api/daily-sales?limit=5&id=1'
        );

        $response->assertStatus(422);
    }

}
