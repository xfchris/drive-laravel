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
        $ultimoPlan = $this->getUltimoPlanVigente();
        if ($ultimoPlan){
            return date('d/m/Y h:i A', $ultimoPlan->pivot->fechafin);
        }
        return 0;
    }

    /**
     * Obtengo el ultimo plan comprado vigente por el usuario
     * @return mixed|null
     */
    public function getUltimoPlanVigente(){
        return $this->plans()->wherePivot('fechafin','>', time())
            ->wherePivot('estado','1')
            ->orderBy('fechafin','DESC')->first();
    }

    /**
     * Funcion que aumenta el plan de almacenamiento para el usuario
     * @param $planId
     * @return bool
     */
    public function aumentarPlan($planId){

        $plan = Plan::select('tiempo')->find($planId);

        //obtengo un plan comprado donde su fechafin sea mayor a la actual
        $miUltimoPlan = $this->getUltimoPlanVigente();

        //si no encuentra ningun plan vigente
        if(!$miUltimoPlan){
            $fechaInicio = time();
            $fechaFin = $fechaInicio+$plan->tiempo;
        }else{
            $fechaInicio = $miUltimoPlan->pivot->fechafin+1;
            $fechaFin = $fechaInicio+$plan->tiempo;
        }

        //creo registro en tabla de rompimiento
        $this->plans()->attach($planId,[
            'fechainicio'=>$fechaInicio,
            'fechafin'=>$fechaFin,
            'estado'=>true]);

        return true;
    }

    /**
     * Planes que tiene el usuario
     */
    function plans(){
        return $this->belongsToMany('App\Plan', 'user_plan')
            ->withPivot('fechainicio','fechafin','estado');
    }
}
