<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMovieRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'imdb_id' => ['nullable', 'string', Rule::unique('movies', 'imdb_id')],
            'title' => ['required', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1800', 'max:'.date('Y')],
            'rated' => ['nullable', 'string', 'max:10'],
            'released' => ['nullable', 'date'],
            'runtime' => ['nullable', 'string', 'max:50'],
            'genre' => ['nullable', 'string', 'max:255'],
            'director' => ['nullable', 'string', 'max:255'],
            'writer' => ['nullable', 'string', 'max:255'],
            'actors' => ['nullable', 'string', 'max:255'],
            'plot' => ['nullable', 'string'],
            'language' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'awards' => ['nullable', 'string', 'max:255'],
            'poster' => ['nullable', 'string', 'url'],
            'metascore' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'imdb_rating' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'imdb_votes' => ['nullable', 'string', 'max:50'],
            'type' => ['nullable', 'string', 'max:50'],
            'dvd' => ['nullable', 'date'],
            'box_office' => ['nullable', 'string', 'max:255'],
            'production' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'url'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Movie title is required',
            'imdb_id.unique' => 'This IMDB ID already exists in the database',
            'poster.url' => 'Poster must be a valid URL',
            'website.url' => 'Website must be a valid URL',
        ];
    }
}
