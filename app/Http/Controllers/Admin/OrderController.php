<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Liste toutes les commandes avec recherche avancée.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user','tickets.event','payment']);

        // Recherche par ID
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        // Recherche par QR code
        if ($request->filled('qr_code')) {
            $query->whereHas('tickets', fn($q) => $q->where('qr_code', $request->qr_code));
        }

        // Recherche par événement
        if ($request->filled('event')) {
            $query->whereHas('tickets.event', fn($q) => $q->where('title','like','%'.$request->event.'%'));
        }

        // Recherche par date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Recherche par lieu
        if ($request->filled('venue')) {
            $query->whereHas('tickets.event.venue', fn($q) => $q->where('name','like','%'.$request->venue.'%'));
        }

        $orders = $query->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Affiche le détail d’une commande.
     */
    public function show($id)
    {
        $order = Order::with(['user','tickets.event','payment'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }
}
