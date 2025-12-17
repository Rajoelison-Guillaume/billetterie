<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\SeatReservation;

class DashboardController extends Controller
{
    public function index()
{
    return view('admin.dashboard', [
        'eventsCount' => Event::count(),
        'activeEvents' => Event::where('start_date', '>=', now())->count(),
        'ordersCount' => Order::count(),
        'paidOrders' => Order::where('status', 'paid')->count(),
        'ticketsCount' => Ticket::count(),
        'reservationsCount' => SeatReservation::count(),
    ]);
}

}
