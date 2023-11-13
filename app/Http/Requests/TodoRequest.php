<?php

namespace App\Http\Requests;

use App\Enums\TodoStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TodoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'description' => ['required'],
            'status' => ['required', new Enum(TodoStatus::class)],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
