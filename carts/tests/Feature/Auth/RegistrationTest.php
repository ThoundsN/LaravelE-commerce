<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_requries_a_name()
    {
        $this->json('POST', 'api/auth/register')->assertJsonValidationErrors('name');
    }

    public function test_it_requries_a_email()
    {
        $this->json('POST', 'api/auth/register')->assertJsonValidationErrors('email');
    }

    public function test_it_requries_a_password()
    {
        $this->json('POST', 'api/auth/register')->assertJsonValidationErrors('password');
    }


    public function test_it_requries_a_valid_email()
    {
        $this->json('POST', 'api/auth/register',[
            'email' => 'nope'
        ])->assertJsonValidationErrors('email');
    }

    public function test_it_requries_a_unique_email()
    {

        $user = factory(User::class)->create();

        $this->json('POST', 'api/auth/register',[
            'email' => $user->email,
        ])->assertJsonValidationErrors(['email']);
    }

    public function test_it_registers_a_user()
    {
        $this->json('POST', 'api/auth/register', [
            'email' => 'inthedsadas@qq.com',
            'name' => 'curelworld',
            'password' => 'sercert'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'inthedsadas@qq.com',
            'name' => 'curelworld',
        ]);

    }
}


