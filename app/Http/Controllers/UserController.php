<?php

namespace App\Http\Controllers;

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
        return view('user.index',compact('user'));
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
            $user->name = $data['nombres'];
           // $user->plan_id = $data['plan'];

            $rules = array(
                'nombres' => 'required|min:1|max:180',
                'plan' => 'required|numeric',
            );

            $valid = Validator::make($data, $rules, [
                'required'=>'El campo es requerido',
            ]);

            if ($valid->fails()){
                $errores = $valid->errors()->all();
                $code=400;
                $res = [
                    'code'=>"error",
                    "msg"=>implode(', ', $errores)
                ];

            }elseif($user->save()){
                $res = [
                    'code'=>"success",
                    "msg"=>"Información actualizada"
                ];
                $code = 200;
            }else{
                throw new \Exception("No se pudo guardar la informacion al actualizar el usuario");
            }
        }catch(\Exception $ex){
            error_log($ex->getMessage());
            $res = [
                'code'=>"error",
                "msg"=>"Se produjo un error, contacte al administrador"
            ];
        }

        //retorno respuesta
        return response()->json($res,$code);
    }

}
