<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];


    //---------------------- Relationships ----------------------//
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }



    //---------------------- Accessors & Mutators ----------------------//
    public function getBooksCountAttribute(): int
    {
        return $this->books()->count();
    }


    public function getBooksTitleAttribute(): ?string
    {
        return $this->books()->pluck('title')->implode(', ');
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = ucfirst($value);
    }

    public function getNameAttribute($value): string
    {
        return ucfirst($value);
    }
}
