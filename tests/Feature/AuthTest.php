<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;

uses(RefreshDatabase::class);

it('can log in a user with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->postJson(route('login'), [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->has('token'));
});

it('cannot log in a user with invalid credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->postJson(route('login'), [
        'email' => 'test@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401)
        ->assertJson(fn(AssertableJson $json) => $json->where('message', 'Unauthenticated'));
});

it('can log out a user', function () {
    $user = User::factory()->create();
    $token = $user->createToken('authToken')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->postJson(route('logout'));

    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->where('message', 'Logged out successfully'));
});


it('can get the authenticated user', function () {
    $user = User::factory()->create();
    $token = $user->createToken('authToken')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
        ->getJson(route('user'));

    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->where('email', $user->email)
            ->etc()
        );
});
