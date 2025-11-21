<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TicketMessageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ];
    }

    public function messages()
    {
        return [
            'message.required' => 'Message is required',
            'attachment.max' => 'Attachment must not exceed 10MB',
        ];
    }
}