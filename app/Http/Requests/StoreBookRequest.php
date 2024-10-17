<?php

namespace App\Http\Requests;

use App\Enum\UserRoles;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->role === UserRoles::ADMIN;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'author_id' => ['required', 'integer', Rule::exists('authors', 'id')->whereNull('deleted_at')],
            'isbn' => ['required', 'string', 'unique:books,isbn'],
            'title' => ['required', 'string'],
            'genre' => ['required', 'string'],
            'year' => ['required', 'integer'],
            'publisher' => ['required', 'string'],
            'image' => ['nullable', 'string'],
        ];
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('You are not authorized to create a book');
    }
}
