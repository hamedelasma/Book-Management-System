<?php

use App\Models\Author;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->user = User::factory()->create(['role' => 'user']);
});

it('can list authors with filters', function () {
    Author::factory()->count(3)->create();

    Sanctum::actingAs($this->admin);

    $response = getJson(route('authors.index'));
    $response->assertStatus(ResponseAlias::HTTP_OK)
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'current_page',
            'data' => [
                '*' => ['id', 'name', 'books_count'],
            ],
        ]);
});

it('can create an author as admin', function () {
    $authorData = Author::factory()->make()->toArray();

    Sanctum::actingAs($this->admin);

    $response = postJson(route('authors.store'), $authorData);

    $response->assertStatus(ResponseAlias::HTTP_CREATED)
        ->assertJsonPath('data.name', $authorData['name']);

});

it('cannot create an author as non-admin', function () {
    $authorData = Author::factory()->make()->toArray();

    Sanctum::actingAs($this->user);

    $response = postJson(route('authors.store'), $authorData);

    $response->assertStatus(ResponseAlias::HTTP_FORBIDDEN);
});

it('can show an author', function () {
    $author = Author::factory()->create();

    Sanctum::actingAs($this->admin);

    $response = getJson(route('authors.show', $author->id));

    $response->assertStatus(ResponseAlias::HTTP_OK)
        ->assertJsonPath('id', $author->id)
        ->assertJsonPath('name', $author->name);

});

it('can update an author as admin', function () {
    $author = Author::factory()->create();
    $updatedData = ['name' => 'Updated Name'];

    Sanctum::actingAs($this->admin);

    $response = putJson(route('authors.update', $author->id), $updatedData);

    $response->assertStatus(ResponseAlias::HTTP_OK)
        ->assertJsonPath('data.name', $updatedData['name']);
});

it('cannot update an author as non-admin', function () {
    $author = Author::factory()->create();
    $updatedData = ['name' => 'Updated Name'];

    Sanctum::actingAs($this->user);

    $response = putJson(route('authors.update', $author->id), $updatedData);

    $response->assertStatus(ResponseAlias::HTTP_FORBIDDEN);
});

it('can delete an author as admin', function () {

    $author = Author::factory()->create();

    Sanctum::actingAs($this->admin);

    $response = deleteJson(route('authors.destroy', $author->id));

    $response->assertStatus(ResponseAlias::HTTP_OK);
});

it('cannot delete an author as non-admin', function () {
    $author = Author::factory()->create();

    Sanctum::actingAs($this->user);

    $response = deleteJson(route('authors.destroy', $author->id));

    $response->assertStatus(ResponseAlias::HTTP_FORBIDDEN);
});

it('cannot delete an author with books', function () {
    $author = Author::factory()->hasBooks(1)->create();

    Sanctum::actingAs($this->admin);

    $response = deleteJson(route('authors.destroy', $author->id));

    $response->assertStatus(ResponseAlias::HTTP_CONFLICT);
});

it('can list authors with search', function () {
    $author = Author::factory()->create(['name' => 'John Doe']);

    Sanctum::actingAs($this->admin);

    $response = getJson(route('authors.index', ['search' => 'John']));

    $response->assertStatus(ResponseAlias::HTTP_OK)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', $author->name);
});

it('can list authors with sort', function () {
    $author1 = Author::factory()->create(['name' => 'John Doe']);
    $author2 = Author::factory()->create(['name' => 'Jane Doe']);

    Sanctum::actingAs($this->admin);

    $response = getJson(route('authors.index', ['sort' => 'name', 'order' => 'desc']));

    $response->assertStatus(ResponseAlias::HTTP_OK)
        ->assertJsonPath('data.0.name', $author1->name)
        ->assertJsonPath('data.1.name', $author2->name);
});


