<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Payment;

class Order extends Model
{
    protected $fillable = ['user_id','total_amount','status','payment_method'];

    public function user(){ return $this->belongsTo(User::class); }
    public function tickets(){ return $this->hasMany(Ticket::class); }
    public function payment(){ return $this->hasOne(Payment::class); }
}


