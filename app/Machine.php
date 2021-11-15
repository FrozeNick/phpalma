<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'machine_id', 'machine_name', 'clinic_id', 'preparation_time', 'status', 'ip', 'temperature_avg', 'connected_to_clinic'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'clinic_id' => 'int',
        'status' => 'int',
        'temperature_avg' => 'float',
        'connected_to_clinic' => 'datetime'
    ];

    public function clinic()
    {
        return $this->hasOne(Clinic::class, 'id', 'clinic_id');
    }
}
