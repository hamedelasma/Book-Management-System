<?php

use App\Enum\UserRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

uses(RefreshDatabase::class);

it('can register a new user', function () {
    $userData = [
        'name' => 'Hamed Test',
        'email' => 'test@example.com',
        'password' => 'password123',
    ];

    $response = $this->postJson(route('register'), $userData);

    $response->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) => $json->where('message', 'User registered successfully')
            ->has('token')
        );

    $user = User::where('email', $userData['email'])->first();
    expect($user)->not->toBeNull();
});

it('cannot register a new user with an existing email', function () {
    $userData = [
        'name' => 'Hamed Test',
        'email' => 'test@example.com',
        'password' => 'password123',
    ];

    User::factory()->create($userData);

    $response = $this->postJson(route('register'), $userData);

    $response->assertStatus(422)
        ->assertJson(fn (AssertableJson $json) => $json->where('message', 'The email has already been taken.')
            ->has('errors.email')
        );
});

it('cannot register a new user with an invalid email', function () {
    $userData = [
        'name' => 'Hamed Test',
        'email' => 'invalid-email',
        'password' => 'password123',
    ];

    $response = $this->postJson(route('register'), $userData);

    $response->assertStatus(422)
        ->assertJson(fn (AssertableJson $json) => $json->where('message', 'The email field must be a valid email address.')
            ->has('errors.email')
        );
});

it('cannot register a new user with a password less than 8 characters', function () {
    $userData = [
        'name' => 'Hamed Test',
        'email' => 'test@example.com',
        'password' => 'pass',
    ];

    $response = $this->postJson(route('register'), $userData);

    $response->assertStatus(422)
        ->assertJson(fn (AssertableJson $json) => $json->where('message', 'The password field must be at least 8 characters.')
            ->has('errors.password')
        );
});

it('cannot pass role in the request', function () {
    $userData = [
        'name' => 'Hamed Test',
        'email' => 'test@example.com',
        'password' => 'password123',
        'role' => 'admin',
    ];

    $response = $this->postJson(route('register'), $userData);

    $response->assertStatus(200)
        ->assertJson(fn (AssertableJson $json) => $json->where('message', 'User registered successfully')
            ->has('token')
        );

    $user = User::where('email', $userData['email'])->first();
    expect($user->role)->toBe(UserRoles::USER);
});
