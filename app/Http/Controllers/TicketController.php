<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login');
        }

        $tickets = Ticket::with(['order','event','showtime','seat'])
            ->whereHas('order', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->get();

        return view('tickets.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = Ticket::with(['order','event','showtime','seat'])->findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }
}
