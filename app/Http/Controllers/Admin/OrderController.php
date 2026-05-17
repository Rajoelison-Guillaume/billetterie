<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Services\EfainaService; // ⚡ utilise ton service Efaina
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * Liste toutes les commandes avec recherche avancée.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'tickets.event', 'payment']);

        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        if ($request->filled('qr_code')) {
            $query->whereHas('tickets', fn($q) => $q->where('qr_code', $request->qr_code));
        }

        if ($request->filled('event')) {
            $query->whereHas('tickets.event', fn($q) => $q->where('title', 'like', '%' . $request->event . '%'));
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('venue')) {
            $query->whereHas('tickets.event.venue', fn($q) => $q->where('name', 'like', '%' . $request->venue . '%'));
        }
        if ($request->filled('date_start') && $request->filled('date_end')) {
            $query->whereBetween('created_at', [$request->date_start, $request->date_end]);

        } elseif ($request->filled('date_start')) {
                $query->whereDate('created_at', '>=', $request->date_start);

                } elseif ($request->filled('date_end')) {
                            $query->whereDate('created_at', '<=', $request->date_end);
        }


        $orders = $query->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Affiche le détail d’une commande.
     */
    public function show($id)
    {
        $order = Order::with(['user', 'tickets.event', 'payment'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Finalise le paiement d’une commande via Mobile Money (Efaina API).
     */
    public function pay(Request $request, Order $order)
    {
        $request->validate([
            'method' => 'required|in:mvola,orange_money,airtel_money',
            'phone'  => 'required|regex:/^(032|033|034)\d{7}$/',
        ]);

        // ⚡ Appel à ton service Efaina
        $result = app(EfainaService::class)->pay(
            $order->total_amount,
            $request->phone,
            $request->method,
            $order->id
        );

        // ⚡ Vérifie la réponse de l’API Efaina
        if (!empty($result['status']) && strtoupper($result['status']) === 'SUCCESS') {
            Payment::create([
                'order_id'     => $order->id,
                'amount'       => $order->total_amount,
                'method'       => $request->method,
                'provider'     => ucfirst($request->method),
                'provider_ref' => $result['transaction_id'] ?? 'TX-' . uniqid(),
                'status'       => 'success',
            ]);

            $order->update(['status' => 'paid']);

            return back()->with('success', 'Paiement finalisé avec succès via Efaina.');
        }

        // ⚡ Gestion des erreurs
        $errorMessage = $result['message'] ?? 'Échec du paiement, veuillez réessayer.';
        return back()->with('error', $errorMessage);
    }
    

public function exportPdf(Request $request)
{
    $query = Order::with(['user', 'tickets.event', 'payment']);

    // mêmes filtres que index()
    if ($request->filled('id')) {
        $query->where('id', $request->id);
    }
    if ($request->filled('qr_code')) {
        $query->whereHas('tickets', fn($q) => $q->where('qr_code', $request->qr_code));
    }
    if ($request->filled('event')) {
        $query->whereHas('tickets.event', fn($q) => $q->where('title', 'like', '%' . $request->event . '%'));
    }
    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->date);
    }
    if ($request->filled('venue')) {
        $query->whereHas('tickets.event.venue', fn($q) => $q->where('name', 'like', '%' . $request->venue . '%'));
    }
    if ($request->filled('date_start') && $request->filled('date_end')) 
    {
    $query->whereBetween('created_at', [$request->date_start, $request->date_end]);
    }
     elseif ($request->filled('date_start')) 
    {
    $query->whereDate('created_at', '>=', $request->date_start);
    }
 elseif ($request->filled('date_end')) 
    {
    $query->whereDate('created_at', '<=', $request->date_end);
    }


    $orders = $query->get();

    // Génération du PDF
    $pdf = Pdf::loadView('admin.orders.export-pdf', compact('orders'));
    return $pdf->download('orders.pdf');
}

}
