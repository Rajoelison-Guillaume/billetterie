<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['order', 'event', 'seat'])
            ->whereHas('order', fn($q) => $q->where('user_id', Auth::id()))
            ->get();

        return view('tickets.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = Ticket::with(['order', 'event', 'seat'])->findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }
}