<?php

namespace App\Http\Requests;

use App\Enum\UserRoles;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

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
            'isbn' => ['sometimes', 'string', 'unique:books,isbn,' . $this->route('book')->id],
            'title' => ['sometimes', 'string'],
            'author' => ['sometimes', 'string'],
            'genre' => ['sometimes', 'string'],
            'year' => ['sometimes', 'integer'],
            'publisher' => ['sometimes', 'string'],
            'image' => ['nullable', 'string'],
        ];
    }

    protected function failedAuthorization()
    {
        Throw new AuthorizationException('You are not authorized to update a book');
    }
}
