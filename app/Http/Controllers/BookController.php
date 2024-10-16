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
            'genre' => ['nullable', 'string'],
            'year' => ['nullable', 'integer'],
            'publisher' => ['nullable', 'string'],
            'sort_by' => ['nullable', 'string', 'in:isbn,title,author,genre,year,publisher'],
            'sort' => [ 'string', 'in:asc,desc'],
            'per_page' => ['nullable', 'integer','min:1', 'max:100'],
            'page' => ['nullable', 'integer'],
        ]);

        $books = Book::query()
            ->when($request->search, fn($query, $search) => $query->search($search))
            ->when($request->genre, fn($query, $genre) => $query->where('genre', $genre))
            ->when($request->year, fn($query, $year) => $query->where('year', $year))
            ->when($request->publisher, fn($query, $publisher) => $query->where('publisher', $publisher))
            ->when($request->sort_by, fn($query, $sort_by) => $query->orderBy($sort_by, $request->get('sort', 'asc')))
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
