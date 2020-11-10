<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    //
    protected $fillable = ['nombre', 'tipo', 'fechasubida','tamano', 'user_id'];

    function getNombreServer(){
        return $this->user_id. '_'.$this->id.'_'.$this->tipo;
    }

    //Devuelve informacion del usuario
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function getFechaSubida(){
        return date('d/m/Y h:i:A', $this->fechasubida);
    }
}
