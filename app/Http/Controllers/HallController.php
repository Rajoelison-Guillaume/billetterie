<?php

namespace App\Http\Controllers;
use App\Models\Room;
use Illuminate\Http\Request;

class HallController extends Controller
{
    public function index()
    {
        $rooms = Room::with('venue')->get();
        return view('halls.index', compact('rooms'));
    }

    public function show($id)
    {
        $room = Room::with(['venue','seats'])->findOrFail($id);
        return view('halls.show', compact('room'));
    }
}
