<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'title'         => 'required|string|max:255',
            'slug'          => 'required|string|unique:events,slug,' . $this->route('event'),
            'category'      => 'required|in:cinema,expo,concert,conference',
            'start_date'    => 'required|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'description'   => 'nullable|string',
            'organizer_id'  => 'required|exists:organizers,id',
            'venue_id'      => 'required|exists:venues,id',
            'room_id'       => 'required|exists:rooms,id',
            'event_type_id' => 'required|exists:event_types,id',
            'ticket_price'  => 'required|numeric|min:0',
            'max_per_user'  => 'nullable|integer|min:1',
            'is_active'     => 'required|boolean',
        ];
    }
}
