<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::latest()->paginate(20);
        return view('admin.payments.index', compact('payments'));
    }

    public function show($id)
    {
        $payment = Payment::findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }

    public function validateCash($id)
    {
        $payment = Payment::findOrFail($id);
        if ($payment->method === 'cash' && $payment->status === 'pending') {
            $payment->update(['status' => 'paid']);
        }
        return redirect()->route('admin.payments.index')->with('success', 'Paiement cash validé.');
    }
}
