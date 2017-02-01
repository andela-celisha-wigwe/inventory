<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomeTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHomeRootRoute()
    {
        $response = $this->get('/');

        $this->assertEquals("Welcome to Roy's Inventory API.", $response->content());
        $response->assertStatus(200);
    }
}
