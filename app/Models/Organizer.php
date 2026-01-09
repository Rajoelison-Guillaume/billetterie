<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Organizer extends Model
{
    protected $fillable = ['name','contact_email','contact_phone','description','logo'];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
