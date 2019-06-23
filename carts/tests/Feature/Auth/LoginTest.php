<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_requries_a_email()
    {
        $this->json('POST', 'api/auth/login')->assertJsonValidationErrors('email');
    }

    public function test_it_requries_a_password()
    {
        $this->json('POST', 'api/auth/login',[
            'email' =>  'dads@321.com'
        ])->assertJsonValidationErrors('email');
    }

    public function test_it_return_error_when_credential_invalid()
    {
        $user = factory(User::class)->create();

        $this->json('POST', 'api/auth/login',[
            'email' => $user->email,
            'password' => 'nope'
        ])->assertJsonValidationErrors('email');
    }

    public function test_it_return_token_when_credential_mathch()
    {
        $user = factory(User::class)->create([
            'password' => 'nwo'
        ]);

        $this->json('POST', 'api/auth/login',[
            'email' => $user->email,
            'password' => 'nwo'
        ])->assertJsonStructure([
            'meta' => [
                'token'
            ]
        ]);
    }
}
