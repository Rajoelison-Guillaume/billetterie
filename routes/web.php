<?php
use Illuminate\Support\Facades\Route;
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


//use App\Http\Middleware\IsAdmin;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\OrganizerController as AdminOrganizerController;
use App\Http\Controllers\Admin\VenueController as AdminVenueController;
use App\Http\Controllers\Admin\TicketTypeController as AdminTicketTypeController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

// Page d’accueil
Route::get('/', [EventController::class, 'index'])->name('home');

// Événements
Route::get('/events/cinema', [EventController::class, 'cinema'])->name('events.cinema');
Route::get('/events/libre', [EventController::class, 'libre'])->name('events.libre');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
Route::post('/events/{id}/reserve', [EventController::class, 'reserve'])->middleware('auth')->name('events.reserve');


// Cinéma
Route::get('/showtimes/{id}', [ShowtimeController::class, 'show'])->name('showtimes.show');
Route::post('/showtimes/{id}/reserve', [ShowtimeController::class, 'reserve'])->middleware('auth')->name('showtimes.reserve');

// Panier / Commandes (côté utilisateur)
Route::middleware(['auth'])->group(function () {
    Route::get('/orders/cart', [OrderController::class, 'cart'])->name('orders.cart');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/add', [OrderController::class, 'add'])->name('orders.add');
});

// Paiement
Route::get('/checkout', [PaymentController::class, 'show'])->middleware('auth')->name('checkout.show');
Route::post('/checkout/pay', [PaymentController::class, 'pay'])->middleware('auth')->name('checkout.pay');

// Billets
Route::get('/tickets', [TicketController::class, 'index'])->middleware('auth')->name('tickets.index');
Route::get('/tickets/{id}', [TicketController::class, 'show'])->middleware('auth')->name('tickets.show');

// Salles
Route::get('/halls', [HallController::class, 'index'])->name('halls.index');
Route::get('/halls/{id}', [HallController::class, 'show'])->name('halls.show');

// Organisateurs
Route::get('/organizers', [OrganizerController::class, 'index'])->name('organizers.index');
Route::get('/organizers/{id}', [OrganizerController::class, 'show'])->name('organizers.show');


// Réservations (côté client)
Route::middleware(['auth'])->prefix('client')->group(function () {
    Route::post('/reservation', [ReservationController::class, 'store'])->name('client.reservation.store');
});


// Réservations de sièges
Route::get('/reservations', [SeatReservationController::class, 'index'])->middleware('auth')->name('reservations.index');
Route::get('/reservations/{id}', [SeatReservationController::class, 'show'])->middleware('auth')->name('reservations.show');

// Profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');




// Admin Dashboard
Route::get('/admin', [DashboardController::class, 'index'])
    ->middleware(['auth', 'is_admin'])
    ->name('admin.dashboard');
// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'is_admin'])->group(function () {
    Route::resource('events', AdminEventController::class);
    Route::resource('organizers', AdminOrganizerController::class);
    Route::resource('venues', AdminVenueController::class);
    Route::resource('ticket-types', AdminTicketTypeController::class);
    Route::resource('orders', AdminOrderController::class)->only(['index','show']);
    
    Route::resource('reservations', AdminReservationController::class)->only(['index','show']);
    Route::resource('payments', AdminPaymentController::class)
    ->only(['index','show']);


});


require __DIR__.'/auth.php';
