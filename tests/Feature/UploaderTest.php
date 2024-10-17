<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
});

it('can upload a file', function () {
    Sanctum::actingAs($this->admin);

    Storage::fake('public');

    $file = UploadedFile::fake()->image('test.jpg');

    $response = postJson(route('uploader'), [
        'file' => $file,
    ]);

    $response->assertStatus(ResponseAlias::HTTP_OK)
        ->assertJsonStructure(['path']);
});
