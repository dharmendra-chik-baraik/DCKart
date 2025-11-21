<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TicketStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'user_id' => 'required|exists:users,id',
            'vendor_id' => 'nullable|exists:vendor_profiles,id',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ];
    }

    public function messages()
    {
        return [
            'subject.required' => 'Subject is required',
            'message.required' => 'Message is required',
            'priority.required' => 'Priority is required',
            'user_id.required' => 'User is required',
            'attachment.max' => 'Attachment must not exceed 10MB',
        ];
    }
}
