<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        $events = Event::with(['organizer', 'venue', 'eventType'])
            ->where('is_active', true)
            ->when($query, function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->get();

        return view('events.index', compact('events'));
    }

    public function show($id)
    {
        $event = Event::with(['organizer','venue','room','eventType','showtimes'])
            ->findOrFail($id);

        return view('events.show', compact('event'));
    }

    public function cinema()
    {
        $events = Event::with(['organizer','venue','eventType'])
            ->where('is_active', true)
            ->where('category', 'cinema')
            ->get();

        return view('events.index', compact('events'));
    }

    public function libre()
    {
        $events = Event::with(['organizer','venue','eventType'])
            ->where('is_active', true)
            ->where('category', 'libre')
            ->get();

        return view('events.index', compact('events'));
    }

    public function reserve(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'payment_method' => 'required|in:mobile_money,cash',
            'phone' => 'required_if:payment_method,mobile_money|regex:/^(?:\+261|0)(32|33|34|38)[0-9]{7}$/',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $order = $user->orders()->where('status', 'pending')->first();

        if (!$order) {
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_amount' => 0,
                'payment_method' => $request->payment_method,
            ]);
        }

        Ticket::create([
            'order_id' => $order->id,
            'event_id' => $event->id,
            'price' => $event->ticket_price,
            'qr_code' => Str::uuid(),
            'status' => 'unused',
        ]);

        $order->increment('total_amount', $event->ticket_price);

        if ($request->payment_method === 'mobile_money') {
            app(\App\Services\PapiService::class)->pay(
                $order->total_amount,
                $request->phone,
                $request->payment_method,
                $order->id
            );
        }

        return redirect()->route('orders.cart')->with('success', 'Billet ajouté au panier.');
    }
}
