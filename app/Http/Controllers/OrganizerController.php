<?php

namespace App\Http\Controllers;
use App\Models\Organizer;

use Illuminate\Http\Request;

class OrganizerController extends Controller
{
    public function index()
    {
        $organizers = Organizer::withCount('events')->get();
        return view('organizers.index', compact('organizers'));
    }

    public function show($id)
    {
        $organizer = Organizer::with('events')->findOrFail($id);
        return view('organizers.show', compact('organizer'));
    }

    public function create()
    {
        return view('organizers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        $path = $request->file('logo')?->store('logos', 'public');

        Organizer::create([
            'name' => $request->name,
            'description' => $request->description,
            'logo' => $path,
        ]);

        return redirect()->route('organizers.index')->with('success', 'Organisateur ajouté.');
    }
}
