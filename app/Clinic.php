<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Clinic extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'country', 'city', 'address', 'is_chain', 'distributor_id', 'weekly_hours', 'parent_clinic', 'lat', 'long', 'machine_types', 'treatment_types'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country');
    }

    public function parentClinic()
    {
        return $this->hasOne(Clinic::class, 'id', 'parent_clinic');
    }
}
