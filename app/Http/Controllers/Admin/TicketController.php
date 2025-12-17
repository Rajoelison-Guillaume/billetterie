<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::latest()->paginate(20);
        return view('admin.tickets.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('admin.tickets.show', compact('ticket'));
    }

    public function scan($qrCode)
    {
        $ticket = Ticket::where('qr_code', $qrCode)->firstOrFail();

        if ($ticket->status === 'unused') {
            $ticket->update(['status' => 'used']);
            return response()->json(['success' => true, 'message' => 'Billet validé.']);
        }

        return response()->json(['success' => false, 'message' => 'Billet déjà utilisé ou invalide.']);
    }
}
