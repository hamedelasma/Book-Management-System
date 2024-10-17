<?php

namespace App\Http\Requests;

use App\Enum\UserRoles;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
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
            'author_id' => ['sometimes', 'integer', Rule::exists('authors', 'id')->whereNull('deleted_at')],
            'isbn' => ['sometimes', 'string', Rule::unique('books', 'isbn')->ignore($this->id)],
            'title' => ['sometimes', 'string'],
            'genre' => ['sometimes', 'string'],
            'year' => ['sometimes', 'integer'],
            'publisher' => ['sometimes', 'string'],
            'image' => ['nullable', 'string'],
        ];
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('You are not authorized to update a book');
    }
}
