<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory , SoftDeletes, Searchable;

    protected $fillable = ['author_id', 'isbn', 'title', 'genre', 'year', 'publisher', 'image'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected function casts(): array
    {
        return [
            'isbn' => 'string',
        ];
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'isbn' => $this->isbn,
            'title' => $this->title,
            'genre' => $this->genre,
            'year' => $this->year,
        ];
    }

    //---------------------- Relationships ----------------------//

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    //---------------------- Accessors & Mutators ----------------------//

    public function getGenreAttribute($value): string
    {
        return ucfirst($value);
    }

    public function setGenreAttribute($value): void
    {
        $this->attributes['genre'] = strtolower($value);
    }

    public function getYearAttribute($value): int
    {
        return (int) $value;
    }

    public function setYearAttribute($value): void
    {
        $this->attributes['year'] = (int) $value;
    }

    public function getPublisherAttribute($value): string
    {
        return ucfirst($value);
    }

    public function setPublisherAttribute($value): void
    {
        $this->attributes['publisher'] = strtolower($value);
    }

    public function getImageAttribute($value): string
    {
        return $value ? asset('storage/'.$value) : '';
    }

    //---------------------- Scopes ----------------------//

    public function scopeAuthorName($query, $authorName): void
    {
        $query->whereHas('author', function ($query) use ($authorName) {
            $query->where('name', 'like', '%'.$authorName.'%');
        });
    }

    public function scopeSearch($query, $search): void
    {
        $query->where('title', 'like', '%'.$search.'%')
            ->orWhere('author', 'like', '%'.$search.'%')
            ->orWhere('genre', 'like', '%'.$search.'%')
            ->orWhere('year', 'like', '%'.$search.'%')
            ->orWhere('publisher', 'like', '%'.$search.'%');
    }

    public function scopeGenre($query, $genre): void
    {
        $query->where('genre', $genre);
    }

    public function scopeYear($query, $year): void
    {
        $query->where('year', $year);
    }

    public function scopePublisher($query, $publisher): void
    {
        $query->whereLike('publisher', $publisher);
    }

    public function scopeIsbn($query, $isbn): void
    {
        $query->where('isbn', $isbn);
    }

    public function scopeTitle($query, $title): void
    {
        $query->whereLike('title', $title);
    }
}
