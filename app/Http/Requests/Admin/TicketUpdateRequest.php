<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TicketUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'subject' => 'sometimes|required|string|max:255',
            'priority' => 'sometimes|required|in:low,medium,high,urgent',
            'status' => 'sometimes|required|in:open,in_progress,resolved,closed',
        ];
    }

    public function messages()
    {
        return [
            'subject.required' => 'Subject is required',
            'priority.required' => 'Priority is required',
            'status.required' => 'Status is required',
        ];
    }
}
