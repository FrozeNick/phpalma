<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Mail\ForgotPassword;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'clinic_id', 'status', 'timezone', 'terms',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'tmp_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'role' => 'int',
        'clinic_id' => 'int',
        'status' => 'boolean',
        'terms' => 'boolean'
    ];

    public function clinic()
    {
        return $this->hasOne(Clinic::class, 'id', 'clinic_id');
    }

    public function sendFpMail() {
        Mail::to($this)->send(new ForgotPassword($this));
    }

    public function generateFpCode() {
        $code = Str::random(64);
        $this->tmp_token = $code;
        $this->save();
        
        return $code;
    }
}
