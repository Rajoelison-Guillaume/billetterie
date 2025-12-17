<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\Admin\UpdateEventRequest;

use App\Models\Organizer;
use App\Models\Venue;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Requests\Admin\StoreEventRequest;

class EventController extends Controller
{
    /**
     * Affiche la liste des événements (admin).
     */
    public function index()
    {
        $events = Event::paginate(10);
        return view('admin.events.index', compact('events'));
    }

    /**
     * Formulaire de création d’un nouvel événement.
     */
    public function create()
{
    $organizers = Organizer::all();
    $venues = Venue::all();

    return view('admin.events.create', compact('organizers', 'venues'));
}


    /**
     * Enregistre un nouvel événement.
     */
    public function store(StoreEventRequest $request)
    {
    Event::create($request->validated());

    return redirect()->route('admin.events.index')
        ->with('success','Événement créé avec succès.');
    }
    /**
     * Affiche les détails d’un événement (admin).
     */
    public function show(string $id)
    {
        $event = Event::with(['sessions','ticketTypes'])->findOrFail($id);
        return view('admin.events.show', compact('event'));
    }

    /**
     * Formulaire d’édition d’un événement.
     */
    public function edit(string $id)
    {
    $event = Event::findOrFail($id);
    $organizers = Organizer::all();
    $venues = Venue::all();

    return view('admin.events.edit', compact('event', 'organizers', 'venues'));
    }


    /**
     * Met à jour un événement existant.
     */

public function update(UpdateEventRequest $request, Event $event)
{
    $event->update($request->validated());

    return redirect()->route('admin.events.index')
        ->with('success','Événement mis à jour avec succès.');
}


    /**
     * Supprime un événement.
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('admin.events.index')->with('success','Événement supprimé');
    }
    
}
