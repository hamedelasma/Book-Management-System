<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'search' => ['nullable', 'string'],
            'author' => ['nullable', 'string'],
            'genre' => ['nullable', 'string'],
            'year' => ['nullable', 'integer'],
            'publisher' => ['nullable', 'string'],
            'sort' => ['nullable', 'string', 'in:isbn,title,author,genre,year,publisher'],
            'order' => ['string', 'in:asc,desc'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer'],
        ]);

        $books = Book::query()
            ->with('author')
            ->when($request->has('search'), fn($query) => $query->search($request->search))
            ->when($request->has('author'), fn($query) => $query->author($request->author))
            ->when($request->has('genre'), fn($query) => $query->genre($request->genre))
            ->when($request->has('year'), fn($query) => $query->year($request->year))
            ->when($request->has('publisher'), fn($query) => $query->publisher($request->publisher))
            ->when($request->has('sort'), fn($query) => $query->orderBy($request->sort, $request->get('order', 'asc')))
            ->paginate($request->get('per_page', 10));

        return response()->json($books);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request): JsonResponse
    {
        $book = Book::create($request->validated());

        return response()->json([
            'message' => 'Book created successfully',
            'data' => $book
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book): JsonResponse
    {
        return response()->json([
            'data' => $book
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        $book->update($request->validated());

        return response()->json([
            'message' => 'Book updated successfully',
            'data' => $book
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book): JsonResponse
    {
        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully'
        ]);
    }
}
