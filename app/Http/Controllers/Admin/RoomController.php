<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Venue;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('venue')->paginate(10);
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $venues = Venue::all();
        return view('admin.rooms.create', compact('venues'));
    }
    public function store(Request $request)
{
    $validated = $request->validate([
        'name'      => 'required|string|max:255',
        'capacity'  => 'required|integer|min:1',
        'venue_id'  => 'required|exists:venues,id',
        'description' => 'nullable|string',
    ]);

    $room = Room::create($validated);

    if ($request->has('generate_seats')) {
        $room->generateSeats();
    }

    return redirect()->route('admin.rooms.index')->with('success', 'Salle ajoutée avec succès.');
}

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name'      => 'required|string|max:255',
    //         'capacity'  => 'required|integer|min:1',
    //         'venue_id'  => 'required|exists:venues,id',
    //         'description' => 'nullable|string',
    //     ]);

    //     Room::create($validated);

    //     return redirect()->route('admin.rooms.index')->with('success', 'Salle ajoutée avec succès.');
    // }

    // public function show(Room $room)
    // {
    //     return view('admin.rooms.show', compact('room'));
    // }
    public function show(Room $room)
{
    // Charger les sièges associés à cette salle
    $seats = $room->seats; 

    return view('admin.rooms.show', compact('room', 'seats'));
}


    public function edit(Room $room)
    {
        $venues = Venue::all();
        return view('admin.rooms.edit', compact('room','venues'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'capacity'  => 'required|integer|min:1',
            'venue_id'  => 'required|exists:venues,id',
            'description' => 'nullable|string',
        ]);

        $room->update($validated);

        return redirect()->route('admin.rooms.index')->with('success', 'Salle mise à jour avec succès.');
    }
    public function generateSeats(Room $room)
    {   
    $room->generateSeats();

    return redirect()->route('admin.rooms.show', $room->id)
        ->with('success', 'Les sièges ont été générés avec succès.');
    }


    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('admin.rooms.index')->with('success', 'Salle supprimée avec succès.');
    }
}
