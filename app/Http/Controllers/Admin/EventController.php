<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Organizer;
use App\Models\Venue;
use App\Models\Room;
use App\Models\EventType;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(20);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create', [
            'organizers' => Organizer::all(),
            'venues'     => Venue::all(),
            'rooms'      => Room::all(),
            'eventTypes' => EventType::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'slug'           => 'required|string|unique:events,slug',
            'category'       => 'required|string|in:cinema,concert,festival,libre',
            'organizer_id'   => 'required|exists:organizers,id',
            'venue_id'       => 'required|exists:venues,id',
            'room_id'        => 'required|exists:rooms,id',
            'event_type_id'  => 'required|exists:event_types,id',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'ticket_price'   => 'required|numeric|min:0',
            'is_active'      => 'required|boolean',
            'description'    => 'nullable|string',
            'trailer_url'    => 'nullable|string|max:255',
        ]);

        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', 'Événement créé avec succès.');
    }

    public function show($id)
    {
        $event = Event::with(['organizer', 'venue', 'room', 'eventType'])->findOrFail($id);
        return view('admin.events.show', compact('event'));
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.edit', [
            'event'      => $event,
            'organizers' => Organizer::all(),
            'venues'     => Venue::all(),
            'rooms'      => Room::all(),
            'eventTypes' => EventType::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'slug'           => 'required|string|unique:events,slug,' . $event->id,
            'category'       => 'required|string|in:cinema,concert,festival,libre',
            'organizer_id'   => 'required|exists:organizers,id',
            'venue_id'       => 'required|exists:venues,id',
            'room_id'        => 'required|exists:rooms,id',
            'event_type_id'  => 'required|exists:event_types,id',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'ticket_price'   => 'required|numeric|min:0',
            'is_active'      => 'required|boolean',
            'description'    => 'nullable|string',
            'trailer_url'    => 'nullable|string|max:255',
        ]);

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Événement mis à jour.');
    }
}
