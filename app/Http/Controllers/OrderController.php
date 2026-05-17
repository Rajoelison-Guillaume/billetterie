<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function cart()
    {
        $user = Auth::user();
        $activeOrder = $user->orders()
            ->where('status', 'pending')
            ->with(['tickets.event', 'tickets.seat'])
            ->latest()
            ->first();

        $pastOrders = $user->orders()
            ->where('status', '!=', 'pending')
            ->with(['tickets.event', 'tickets.seat'])
            ->orderByDesc('created_at')
            ->get();

        return view('orders.cart', compact('activeOrder', 'pastOrders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'tickets.event', 'tickets.seat', 'payment'])->findOrFail($id);
        return view('orders.show', compact('order'));
    }
}