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
        return view('admin.ticket-types.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
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
        return view('admin.ticket-types.edit', compact('ticketType'));
    }

    public function update(Request $request, $id) {
        $ticketType = TicketType::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
        ]);
        $ticketType->update($validated);
        return redirect()->route('admin.ticket-types.index')->with('success','Type de billet mis à jour');
    }

    public function destroy($id) {
        TicketType::findOrFail($id)->delete();
        return redirect()->route('admin.ticket-types.index')->with('success','Type de billet supprimé');
    }
}
