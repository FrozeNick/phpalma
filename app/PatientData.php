<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientData extends Model
{
    public $timestamps = false;
    protected $table = 'patients_data';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patient_id', 'name', 'passport', 'picture'
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
        'patient_id' => 'int'
    ];

    public function patient()
    {
        return $this->hasOne(Patient::class, 'id', 'patient_id');
    }
}
