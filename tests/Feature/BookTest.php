<?php

use App\Enum\UserRoles;
use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->adminUser = User::factory([
        'role' => UserRoles::ADMIN,
    ])->create();
    actingAs($this->adminUser,'auth');
});

it('can list books with filters', function () {
    Book::factory()->count(5)->create();

    $response = $this->getJson(route('books.index', [
        'search' => 'test',
        'genre' => 'fiction',
        'year' => 2021,
        'publisher' => 'Test Publisher',
        'sort_by' => 'title',
        'sort' => 'asc',
        'per_page' => 10,
        'page' => 1,
    ]));

    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->has('data')
            ->has('links')
            ->has('meta')
        );
});

it('can create a book', function () {
    $bookData = [
        'title' => 'Test Book',
        'author' => 'Test Author',
        'genre' => 'fiction',
        'year' => 2021,
        'publisher' => 'Test Publisher',
        'isbn' => '1234567890',
    ];

    $response = $this->postJson(route('books.store'), $bookData);

    $response->assertStatus(201)
        ->assertJson(fn(AssertableJson $json) => $json->where('message', 'Book created successfully')
            ->has('data')
        );

    $this->assertDatabaseHas('books', $bookData);
});

it('can show a book', function () {
    $book = Book::factory()->create();

    $response = $this->getJson(route('books.show', $book));

    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->has('data')
            ->where('data.id', $book->id)
        );
});

it('can update a book', function () {
    $book = Book::factory()->create();
    $updateData = [
        'title' => 'Updated Title',
        'author' => 'Updated Author',
    ];

    $response = $this->putJson(route('books.update', $book), $updateData);

    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->where('message', 'Book updated successfully')
            ->has('data')
        );

    $this->assertDatabaseHas('books', $updateData);
});

it('can delete a book', function () {
    $book = Book::factory()->create();

    $response = $this->deleteJson(route('books.destroy', $book));

    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->where('message', 'Book deleted successfully')
        );

    $this->assertDatabaseMissing('books', ['id' => $book->id]);
});
