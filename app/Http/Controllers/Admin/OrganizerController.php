<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organizer;
use Illuminate\Support\Facades\Storage;

class OrganizerController extends Controller
{
    public function index()
    {
        $organizers = Organizer::paginate(10);
        return view('admin.organizers.index', compact('organizers'));
    }

    public function create()
    {
        return view('admin.organizers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:150',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:30',
            'description'   => 'nullable|string',
            'logo'          => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        Organizer::create($validated);

        return redirect()->route('admin.organizers.index')->with('success', 'Organisateur ajouté avec succès.');
    }

    public function show($id)
    {
        $organizer = Organizer::findOrFail($id);
        return view('admin.organizers.show', compact('organizer'));
    }

    public function edit($id)
    {
        $organizer = Organizer::findOrFail($id);
        return view('admin.organizers.edit', compact('organizer'));
    }

    public function update(Request $request, $id)
    {
        $organizer = Organizer::findOrFail($id);

        $validated = $request->validate([
            'name'          => 'required|string|max:150',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:30',
            'description'   => 'nullable|string',
            'logo'          => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            // Supprimer l’ancien logo si présent
            if ($organizer->logo && Storage::disk('public')->exists($organizer->logo)) {
                Storage::disk('public')->delete($organizer->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $organizer->update($validated);

        return redirect()->route('admin.organizers.index')->with('success', 'Organisateur mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $organizer = Organizer::findOrFail($id);

        // Supprimer le logo associé si présent
        if ($organizer->logo && Storage::disk('public')->exists($organizer->logo)) {
            Storage::disk('public')->delete($organizer->logo);
        }

        $organizer->delete();

        return redirect()->route('admin.organizers.index')->with('success', 'Organisateur supprimé avec succès.');
    }
}
