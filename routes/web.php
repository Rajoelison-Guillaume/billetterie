<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ShowtimeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\SeatReservationController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\WebhookController;


use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\OrganizerController as AdminOrganizerController;
use App\Http\Controllers\Admin\VenueController as AdminVenueController;
use App\Http\Controllers\Admin\TicketTypeController as AdminTicketTypeController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\AdminSeatController;
use App\Http\Controllers\DashboardController as ClientDashboardController;

// ✅ Page d’accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// ✅ Dashboard utilisateur
Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ✅ Checkout (paiement côté client)
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [PaymentController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/pay', [PaymentController::class, 'pay'])->name('checkout.pay');
    Route::get('/checkout/success', [PaymentController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel', [PaymentController::class, 'cancel'])->name('checkout.cancel');
});

// ✅ Webhook Efaina (callback API)
Route::post('/webhook/efaina', [WebhookController::class, 'handle'])->name('webhook.efaina');

// ✅ Réservations
Route::middleware(['auth'])->group(function () {
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
});

// ✅ Événements (côté client)
Route::get('/events/cinema', [EventController::class, 'cinema'])->name('events.cinema');
Route::get('/events/libre', [EventController::class, 'libre'])->name('events.libre');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
Route::post('/events/{id}/reserve', [EventController::class, 'reserve'])
    ->middleware('auth')
    ->name('events.reserve');

// ✅ Séances cinéma
Route::get('/showtimes/{id}', [ShowtimeController::class, 'show'])->name('showtimes.show');
Route::middleware(['auth'])->group(function () {
    Route::get('/showtimes/{id}/seats', [SeatReservationController::class, 'seats'])->name('showtimes.seats');
    Route::post('/showtimes/{id}/reserve', [SeatReservationController::class, 'reserve'])->name('showtimes.reserve');
});

// ✅ Panier / Commandes
Route::middleware(['auth'])->group(function () {
    Route::get('/orders/cart', [OrderController::class, 'cart'])->name('orders.cart');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/add', [OrderController::class, 'add'])->name('orders.add');
    Route::get('orders/export/{format}', [OrderController::class, 'export'])->name('admin.orders.export');

});

// ✅ Tickets
Route::middleware(['auth'])->group(function () {
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
});

// ✅ Salles
Route::get('/halls', [HallController::class, 'index'])->name('halls.index');
Route::get('/halls/{id}', [HallController::class, 'show'])->name('halls.show');

// ✅ Organisateurs
Route::get('/organizers', [OrganizerController::class, 'index'])->name('organizers.index');
Route::get('/organizers/{id}', [OrganizerController::class, 'show'])->name('organizers.show');

// ✅ Profil utilisateur
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ✅ Dashboard admin
Route::get('/admin', [AdminDashboardController::class, 'index'])
    ->middleware(['auth', 'is_admin'])
    ->name('admin.dashboard');

// ✅ Routes d’administration
 Route::prefix('admin')->name('admin.')->middleware(['auth', 'is_admin'])->group(function () {
    Route::resource('events', AdminEventController::class);
    Route::resource('seats', AdminSeatController::class);
    Route::resource('organizers', AdminOrganizerController::class);
    Route::resource('venues', AdminVenueController::class);
    Route::resource('ticket-types', AdminTicketTypeController::class);
    Route::resource('orders', AdminOrderController::class)->only(['index','show']);
    Route::resource('reservations', AdminReservationController::class)->only(['index','show']);
    Route::resource('payments', AdminPaymentController::class)->only(['index','show']);

    // ✅ Export PDF des commandes
    Route::get('orders/export/pdf', [AdminOrderController::class, 'exportPdf'])->name('orders.export.pdf');

    // ✅ Gestion des sièges par salle
    Route::get('seats/{room}/plan', [AdminSeatController::class, 'showRoom'])->name('seats.room');
    Route::get('rooms/{room}/seats', [AdminSeatController::class, 'showRoom'])->name('rooms.seats');

    // ✅ Actions spécifiques
    Route::put('payments/{id}/failed', [AdminPaymentController::class, 'markAsFailed'])->name('payments.failed');
    Route::post('orders/{order}/pay', [AdminOrderController::class, 'pay'])->name('orders.pay');

    // ✅ Endpoint JSON pour stats
    Route::get('/stats', [AdminDashboardController::class, 'stats'])->name('stats');
});

require __DIR__.'/auth.php';