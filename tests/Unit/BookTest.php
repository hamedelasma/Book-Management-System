<?php

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('can create a book', function () {
    $author = Author::factory()->create();

    $data = Book::factory()->make([
        'author_id' => $author->id,
        'title' => 'My Book',
    ])->toArray();

    $book = Book::create($data);

    expect($book)->toBeInstanceOf(Book::class)
        ->and($book->title)->toBe('My Book')
        ->and($book->author_id)->toBe($author->id);

    $this->assertDatabaseHas('books', [
        'id' => $book->id,
        'title' => 'My Book',
        'author_id' => $author->id,
    ]);
});

it('can update a book', function () {
    $book = Book::factory()->create();

    $book->update([
        'title' => 'My Updated Book',
    ]);

    expect($book->title)->toBe('My Updated Book');

    $this->assertDatabaseHas('books', [
        'id' => $book->id,
        'title' => 'My Updated Book',
    ]);
});

it('can delete a book', function () {
    $book = Book::factory()->create();

    $book->delete();

    expect(Book::find($book->id))->toBeNull();

    $this->assertSoftDeleted('books', [
        'id' => $book->id,
    ]);
});

it('can list books', function () {
    $books = Book::factory()->count(3)->create();

    expect($books)->toHaveCount(3);

    $books->each(function ($book) {
        expect($book)->toBeInstanceOf(Book::class);
    });
});

it('checks book-author relationship', function () {
    $author = Author::factory()->create();
    $book = Book::factory()->create(['author_id' => $author->id]);

    expect($book->author)->toBeInstanceOf(Author::class)
        ->and($book->author->id)->toBe($author->id);
});
