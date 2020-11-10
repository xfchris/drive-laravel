<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Pagina principal y landing page
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex()
    {
        return view('index');
    }

    /**
     * Panel de administracion
     * @return string
     */
    public function getDashboard(){
        return view('dashboard.index');
    }

    public function getFilesJson(){
        return '[
            {
                "Name": "Unity Pugh",
                "Ext.": "9958",
                "City": "Curicó",
                "Start Date": "2005/02/11",
                "Completion": "37%"
            },
            {
                "Name": "Theodore Duran",
                "Ext.": "8971",
                "City": "Dhanbad",
                "Start Date": "1999/04/07",
                "Completion": "97%"
            },
            {
                "Name": "Theodore Duran",
                "Ext.": "8971",
                "City": "Dhanbad",
                "Start Date": "1999/04/07",
                "Completion": "97%"
            },
            {
                "Name": "Theodore Duran",
                "Ext.": "8971",
                "City": "Dhanbad",
                "Start Date": "1999/04/07",
                "Completion": "97%"
            }
        ]';
    }

    //Sube los archivos, y muestra y devuelve respuesta en json
    public function postUploadFiles(Request $request){
        //Obtengo archivos
        die();
        $archivos = $request->file('files');
        $code = 200;
        $user = Auth::user();
        try{
            foreach($archivos as $archivo)
            {
                $nombre = $archivo->getClientOriginalName();

                //Se busca nombre en base de datos por el usuario
                //Si no existe, creo el archivo en base de datos
                //creo el archivo en el servidor
                //Si aparece, copio

                $nombre = $user->id. '_'.$idSql.'_'.$archivo->getClientOriginalExtension();

                $archivo->move(public_path('images'), $nombre);
                //Se va añadiendo por base de datos el archivo subido
                $out = [
                    'code'=>'success',
                    'msg'=>'Archivos subidos'
                ];
            }
        }catch (\Exception $e){
            error_log('Error_subida: '.$e->getMessage());
            $out = [
                'code'=>'error',
                'msg'=>'Se presentó un error interno, consulte al administrador'
            ];
            $code = 500;
        }

        //Subo cada uno de los archivos a su respectivo directorio
        return response()->json($out, $code);
    }
}
