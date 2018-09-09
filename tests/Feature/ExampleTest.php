<?php

namespace Tests\Feature;

use App\Models\Auth\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
	/**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
		$response = $this
			->actingAs(User::first())
			->get('/');

        $response->assertStatus(200);
    }
}
