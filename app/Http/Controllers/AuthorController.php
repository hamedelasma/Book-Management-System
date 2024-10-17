<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'name' => 'string',
            'with_books_count' => 'boolean',
            'with_books_title' => 'boolean',
            'sort' => 'string|in:id,name,created_at',
            'order' => 'string|in:asc,desc',
            'per_page' => 'integer',
        ]);
        $authors = Author::query()
            ->withCount('books')
            ->when($request->has('name'), fn($query) => $query->name($request->name))
            ->when($request->has('with_books_count'), fn($query) => $query->withBooksCount())
            ->when($request->has('with_books_title'), fn($query) => $query->withBooksTitle())
            ->when($request->has('sort'), fn($query) => $query->orderBy($request->sort, $request->get('order', 'asc')))
            ->paginate($request->get('per_page', 10));

        return response()->json($authors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request): JsonResponse
    {
        $author = Author::create($request->validated());

        return response()->json([
            'message' => 'Author created successfully',
            'data' => $author
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author): JsonResponse
    {
        return response()->json($author->load('books')->append(['books_count']));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorRequest $request, Author $author)
    {
        $author->update($request->validated());

        return response()->json([
            'message' => 'Author updated successfully',
            'data' => $author
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        $author->delete();

        return response()->json([
            'message' => 'Author deleted successfully'
        ]);
    }
}
