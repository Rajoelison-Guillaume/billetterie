<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Affiche le panier actif + l’historique des commandes de l’utilisateur.
     */
    public function cart()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Commande en cours (statut pending)
        $activeOrder = $user->orders()
            ->where('status', 'pending')
            ->with('tickets.event')
            ->latest()
            ->first();

        // Commandes passées (statut != pending)
        $pastOrders = $user->orders()
            ->where('status', '!=', 'pending')
            ->with('tickets.event')
            ->orderByDesc('created_at')
            ->get();

        return view('orders.cart', compact('activeOrder', 'pastOrders'));
    }

    /**
     * Affiche le détail d’une commande spécifique (utile si l’utilisateur clique sur une commande).
     */
    public function show($id)
    {
        $order = Order::with(['user', 'tickets.event', 'payment'])->findOrFail($id);
        return view('orders.show', compact('order'));
    }
}
