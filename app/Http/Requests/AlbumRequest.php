<?php

namespace App\Http\Requests;

use App\Rules\ImageURL;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AlbumRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'artist' => ['required', 'string', 'max:150'],
            'img' => ['required', 'image'],
            'description' => ['max:1000']
        ];
    }
}
