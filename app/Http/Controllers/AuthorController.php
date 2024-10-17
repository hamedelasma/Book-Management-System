<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Author;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'string',
            'sort' => 'string|in:id,name,created_at',
            'order' => 'string|in:asc,desc',
            'with_books_title' => 'boolean',
            'per_page' => 'integer|min:1|max:100',
        ]);
        $authors = Author::query()
            ->withCount('books')
            ->when($request->has('name'), fn($query) => $query->name($request->name))
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
            'data' => $author,
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
    public function update(UpdateAuthorRequest $request, Author $author): JsonResponse
    {
        $author->update($request->validated());

        return response()->json([
            'message' => 'Author updated successfully',
            'data' => $author,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author): JsonResponse
    {
        if ($author->books()->exists()) {
            return response()->json([
                'message' => 'Author cannot be deleted because it has books',
            ], 409);
        }

        $this->authorize('delete', $author);

        $author->delete();

        return response()->json([
            'message' => 'Author deleted successfully',
        ]);
    }
}
