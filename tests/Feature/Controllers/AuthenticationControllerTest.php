<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthenticationControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group login
     */
    public function testUserCanLogin()
    {
        $userData = [
            'username' => 'test',
            'email' => 'test@test.com',
            'password' => Hash::make('password'),
        ];

        User::factory()->create($userData);

        $response = $this->postJson(route('auth.login'), [
            'username' => $userData['username'],
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'token',
            ])
            ->assertJson([
                'status' => true,
            ]);
    }

    public function testUserCantLoginWithIncorrectCredentials()
    {
        $userData = [
            'username' => 'test',
            'email' => 'test@test.com',
            'password' => Hash::make('password'),
        ];

        User::factory()->create($userData);

        $response = $this->postJson(route('auth.login'), [
            'username' => $userData['username'],
            'password' => 'password1',
        ]);

        $response->assertStatus(401)
            ->assertJsonStructure([
                'status',
                'message',
            ])
            ->assertJson([
                'status' => false,
                'message' => 'Wrong username or password!',
            ]);
    }

    public function testUserCanLogout()
    {
        $user = User::factory()->create();

        Passport::actingAs($user);

        $response = $this->postJson(route('auth.logout'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
            ])
            ->assertJson([
                'status' => true,
                'message' => 'You\'ve been successfully logout.',
            ]);
    }

    public function testUnauthenticatedUserCantLogout()
    {
        $response = $this->postJson(route('auth.logout'));

        $response->assertStatus(401)
            ->assertJsonStructure(['message'])
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    public function testUserCanRegister()
    {
        $formData = [
            'username' => 'test',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson(route('auth.registration', $formData));

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'token',
            ])
            ->assertJson([
                'status' => true,
                'message' => 'You\'ve been successfully registered!',
            ]);

        $this->assertDatabaseHas('users', [
            'username' => $formData['username'],
            'email' => $formData['email'],
        ]);
    }
}
