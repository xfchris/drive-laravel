<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['name', 'email', 'password','socials_id', 'avatar'];


    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Devuelve los archivos del usuario
     */
    public function archivos(){
        return $this->hasMany('App\Archivo');
    }

    /**
     * Funcion que te dice, cuanto tiempo le queda a tu plan en formato entendible
     * @return string
     */
    public function getTiempoPlan(){
        return '4 semanas';
    }

    /**
     * Funcion que aumenta el plan de almacenamiento para el usuario
     * @param $planId
     * @return bool
     */
    public function aumentarPlan($planId){
        return true;
    }
}
