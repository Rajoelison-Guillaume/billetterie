<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venue;

class VenueController extends Controller
{
    public function index() {
        $venues = Venue::paginate(10);
        return view('admin.venues.index', compact('venues'));
    }

    public function create() {
        return view('admin.venues.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string',
            'address' => 'nullable|string',
            'capacity' => 'required|integer',
            'type' => 'required|in:hall,cinema,plein_air,stade,theatre',

        ]);
        Venue::create($validated);
        return redirect()->route('admin.venues.index')->with('success','Salle ajoutée');
    }

    public function show($id) {
        $venue = Venue::findOrFail($id);
        return view('admin.venues.show', compact('venue'));
    }

    public function edit($id) {
        $venue = Venue::findOrFail($id);
        return view('admin.venues.edit', compact('venue'));
    }

    public function update(Request $request, $id) {
        $venue = Venue::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string',
            'address' => 'nullable|string',
            'capacity' => 'required|integer',
            'type' => 'required|in:hall,cinema,plein_air,stade,theatre',

        ]);
        $venue->update($validated);
        return redirect()->route('admin.venues.index')->with('success','Salle mise à jour');
    }

    public function destroy($id) {
        Venue::findOrFail($id)->delete();
        return redirect()->route('admin.venues.index')->with('success','Salle supprimée');
    }
}
