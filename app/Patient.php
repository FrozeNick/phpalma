<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patient_id', 'name', 'clinic_id', 'age', 'gender', 'skin_id'
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
        'clinic_id' => 'int',
        'skin_id' => 'int',
        'gender' => 'boolean'
    ];

    public function clinic()
    {
        return $this->hasOne(Clinic::class, 'id', 'clinic_id');
    }

    public function skin()
    {
        return $this->hasOne(Skin::class, 'id', 'skin_id');
    }

    public function data() {
        return $this->hasOne(PatientData::class, 'patient_id', 'id');
    }
}
