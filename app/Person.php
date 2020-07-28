<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $guarded = ['id'];
    protected $table = 'persons';
    protected $appends = ['address_count'];

    public function addresses()
    {
        return $this->hasMany(Address::class, 'person_id');
    }

    public function getAddressCountAttribute()
    {
        return $this->addresses()->count();
    }
}
