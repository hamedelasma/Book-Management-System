<?php

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use function Pest\Laravel\getJson;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
});

it('can search books', function () {
    $book = Book::factory()->create(['title' => 'Test Book']);

    Sanctum::actingAs($this->admin);

    $response = getJson(route('books.search', ['search' =>'Test']));

    $response->assertStatus(ResponseAlias::HTTP_OK)
        ->assertJsonCount(1)
        ->assertJsonPath('0.title', $book->title);
});
