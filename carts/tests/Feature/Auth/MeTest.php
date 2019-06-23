<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_unthenticated()
    {
        $this->json('GET', 'api/auth/me')
            ->assertStatus(401);

    }

    public function test_it_returns_userdetail()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'GET', 'api/auth/me')->
        assertJsonFragment([
            'email' => $user->email
        ]);

    }
}
