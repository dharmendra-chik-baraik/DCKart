<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PageUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $pageId = $this->route('page')->id;

        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $pageId,
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'status' => 'sometimes|boolean', 
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The page title is required.',
            'slug.required' => 'The page slug is required.',
            'slug.unique' => 'This slug is already in use.',
            'content.required' => 'The page content is required.',
            'status.boolean' => 'The status field must be true or false.',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'status' => $this->boolean('status'), 
        ]);
    }
}