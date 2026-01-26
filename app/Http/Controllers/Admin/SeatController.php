<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seat;
use App\Models\Room;
use Illuminate\Http\Request;

class AdminSeatController extends Controller
{
    public function index()
    {
        $seats = Seat::with('room')->paginate(50);
        return view('admin.seats.index', compact('seats'));
    }

    public function create()
    {
        $rooms = Room::all();
        return view('admin.seats.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'row_label' => 'required|string|max:10',
            'seat_number' => 'required|integer|min:1',
            'is_accessible' => 'boolean',
        ]);

        Seat::create($request->all());

        return redirect()->route('admin.seats.index')->with('success', 'Siège ajouté avec succès.');
    }

    public function edit(Seat $seat)
    {
        $rooms = Room::all();
        return view('admin.seats.edit', compact('seat','rooms'));
    }

    public function update(Request $request, Seat $seat)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'row_label' => 'required|string|max:10',
            'seat_number' => 'required|integer|min:1',
            'is_accessible' => 'boolean',
        ]);

        $seat->update($request->all());

        return redirect()->route('admin.seats.index')->with('success', 'Siège mis à jour.');
    }

    public function destroy(Seat $seat)
    {
        $seat->delete();
        return redirect()->route('admin.seats.index')->with('success', 'Siège supprimé.');
    }
}
