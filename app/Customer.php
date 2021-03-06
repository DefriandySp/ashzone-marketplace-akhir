<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;
    protected $guarded = [];

    //mutator
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
