<?php

namespace App\Http\Controllers;

use App\Plan;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Pagina principal y landing page
     */
    public function getIndex()
    {
        $user = Auth::user();
        $plans=Plan::select('id', 'nombre')->orderBy('id', 'ASC')->pluck('nombre','id');
        return view('user.index',compact('user','plans'));
    }

    /**
     * Pagina principal y landing page
     */
    public function getPlan()
    {
        $user = Auth::user();
        $plans=Plan::select('id', 'nombre')->orderBy('id', 'ASC')->pluck('nombre','id');
        return view('user.plan',compact('user','plans'));
    }

    /**
     * Actualiza cuenta de usuario y/o planes de almacenamiento
     */
    public function postIndex(Request $request)
    {
        $data = $request->all();
        $code = 500;
        try {
            //valido data
            $user = Auth::user();
            $rules = array(
                'nombres' => 'required|min:1|max:180',
            );

            $valid = Validator::make($data, $rules, [
                'required'=>'El campo es requerido',
            ]);

            $user->name = $data['nombres'];

            if ($valid->fails()){
                $errores = $valid->errors()->all();
                $code=400;
                $res = [
                    'status'=>"error",
                    "msg"=>implode(', ', $errores)
                ];

            }elseif($user->save()){
                $res = [
                    'status'=>"success",
                    "msg"=>"Información actualizada"
                ];
                $code = 200;
            }else{
                throw new \Exception("No se pudo guardar la informacion al actualizar el usuario");
            }
        }catch(\Exception $ex){
            error_log($ex->getMessage());
            $res = [
                'status'=>"error",
                "msg"=>"Se produjo un error, contacte al administrador"
            ];
        }

        //retorno respuesta
        return response()->json($res,$code);
    }

    /**
     * Actualiza cuenta de usuario y/o planes de almacenamiento
     */
    public function postPlan(Request $request)
    {
        $data = $request->all();
        $code = 500;
        try {
            //valido data
            $user = Auth::user();

            $rules = array(
                'plan' => 'required|numeric|exists:App\Plan,id',
            );

            $valid = Validator::make($data, $rules, [
                'required'=>'Primero selecciona un plan de la lista',
                'exists'=>'El plan no existe'
            ]);

            if ($valid->fails()){
                $errores = $valid->errors()->all();
                $code=400;
                $res = [
                    'status'=>"error",
                    "msg"=>implode(', ', $errores)
                ];

            }else{
                if($user->aumentarPlan($data['plan'])){
                    $res = [
                        'status'=>"success",
                        "msg"=>"Tu plan ha aumentado exitosamente",
                        "tiempo" => $user->getTiempoPlan()
                    ];
                    $code = 200;
                }else{
                    throw new \Exception("No se pudo guardar la informacion al actualizar el usuario");
                }
            }

        }catch(\Exception $ex){
            error_log($ex->getMessage());
            $res = [
                'status'=>"error",
                "msg"=>"Se produjo un error, contacte al administrador"
            ];
        }

        //retorno respuesta
        return response()->json($res,$code);
    }

}
