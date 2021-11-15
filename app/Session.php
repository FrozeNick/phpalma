<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patient_id', 'area_id', 'machine_id', 'clinic_id'
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
        'patient_id' => 'int',
        'area_id' => 'int',
        'machine_id' => 'int',
        'clinic_id' => 'int'
    ];

    public function patient()
    {
        return $this->hasOne(Patient::class, 'id', 'patient_id');
    }

    public function area()
    {
        return $this->hasOne(Area::class, 'id', 'area_id');
    }
    
    public function machine()
    {
        return $this->hasOne(Machine::class, 'id', 'machine_id');
    }

    public function clinic()
    {
        return $this->hasOne(Clinic::class, 'id', 'clinic_id');
    }
}
