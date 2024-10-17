<?php

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);


it('can create author', function () {

    $data = [
        'name' => 'John Doe',
    ];

    $author = Author::create($data);

    expect($author)->toBeInstanceOf(Author::class)
        ->and($author->name)->toBe('John Doe');

});

it('can update author', function () {

    $author = Author::factory()->create();

    $author->update([
        'name' => 'Jane Doe',
    ]);

    expect($author->name)->toBe('Jane Doe');

});

it('can delete author', function () {

    $author = Author::factory()->create();

    $author->delete();

    expect(Author::find($author->id))->toBeNull();

});


it('can list authors', function () {
    $authors = Author::factory()->count(3)->create();

    expect($authors)->toHaveCount(3);

    $authors->each(function ($author) {
        expect($author)->toBeInstanceOf(Author::class);
    });
});


it('can show author', function () {
    $author = Author::factory()->create();

    $author = Author::find($author->id);

    expect($author)->toBeInstanceOf(Author::class);
});

it('can search author', function () {
    $author = Author::factory()->create(['name' => 'John Doe']);

    $authors = Author::where('name', 'like', '%John%')->get();

    expect($authors)->toHaveCount(1);

    $authors->each(function ($author) {
        expect($author->name)->toContain('John');
    });
});

it('can paginate authors', function () {
     Author::factory()->count(10)->create();

    $authors = Author::paginate(5);

    expect($authors->count())->toBe(5);

    $authors->each(function ($author) {
        expect($author)->toBeInstanceOf(Author::class);
    });
});



