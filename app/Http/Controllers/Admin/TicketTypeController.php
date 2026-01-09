<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TicketType;
use App\Models\Event;

class TicketTypeController extends Controller
{
    public function index() {
        $ticketTypes = TicketType::paginate(10);
        return view('admin.ticket-types.index', compact('ticketTypes'));
    }

    public function create() {
    $events = Event::all();
    return view('admin.ticket-types.create', compact('events'));
    }


    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'event_id' => 'required|exists:events,id',
        ]);
        TicketType::create($validated);
        return redirect()->route('admin.ticket-types.index')->with('success','Type de billet ajouté');
    }

    public function show($id) {
        $ticketType = TicketType::findOrFail($id);
        return view('admin.ticket-types.show', compact('ticketType'));
    }

    public function edit($id) {
        $ticketType = TicketType::findOrFail($id);
        $events = Event::all();
        return view('admin.ticket-types.edit', compact('ticketType','events'));
    }

    public function update(Request $request, $id) {
        $ticketType = TicketType::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'event_id' => 'required|exists:events,id',
        ]);
        $ticketType->update($validated);
        return redirect()->route('admin.ticket-types.index')->with('success','Type de billet mis à jour');
    }

    public function destroy($id) {
        TicketType::findOrFail($id)->delete();
        return redirect()->route('admin.ticket-types.index')->with('success','Type de billet supprimé');
    }
}
