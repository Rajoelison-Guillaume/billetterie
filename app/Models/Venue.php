<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = ['name','address','city','type','capacity','description'];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
