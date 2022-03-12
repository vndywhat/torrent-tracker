<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanSeeInformationAboutHimself()
    {
        $userData = [
            'username' => 'test',
            'email' => 'test@test.com',
            'gender' => User::GENDER_MALE,
            'locale' => 'de',
        ];

        $user = User::factory()->create($userData);

        $response = $this->actingAs($user, 'api')->getJson(route('auth.me'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'username',
                    'email',
                    'gender',
                    'locale',
                ],
            ])
            ->assertJson([
                'status' => true,
                'data' => [
                    'username' => $userData['username'],
                    'email' => $userData['email'],
                    'gender' => $userData['gender'],
                    'locale' => $userData['locale'],
                ],
            ]);
    }

    public function testUnauthenticatedUserCantSeeInformationAboutHimself()
    {
        $response = $this->getJson(route('auth.me'));

        $response->assertStatus(401)
            ->assertJsonStructure(['message'])
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }
}
