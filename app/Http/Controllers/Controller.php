<?php

namespace App\Http\Controllers;

use App\Archivo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

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
        $archivos = Auth::user()->archivos()->select('id','nombre','tipo','fechasubida','tamano')
            ->orderBy('fechasubida', 'DESC')->get();

        $out = [];
        if ($archivos){

            $total = count($archivos);

            for ($i=$total; $i!=0; $i--){
                $archivo = $archivos[$total-$i];
                $out[] = [
                    '#'=>"$i",
                    'Nombre'=>$archivo->nombre,
                    'Tipo'=>$archivo->tipo,
                    'Subido el'=>$archivo->getFechaSubida(),
                    'Tamaño'=>humanFilesize($archivo->tamano, 1),
                    'ops'=>"$archivo->id"
                ];
            }
        }

        return $out;
    }

    /**
     * Sube los archivos, y muestra y devuelve respuesta en json
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postUploadFiles(Request $request){
        //Obtengo archivos
        $archivos = $request->file('files');
        $code = 200;
        $user = Auth::user();

        try{
            foreach($archivos as $archivo)
            {
                $nombre = $archivo->getClientOriginalName();

                //Se busca nombre en base de datos por el usuario
                $sqlFile = Archivo::where('nombre', $nombre)->first();

                //Si no existe, creo el archivo en base de datos
                if (!$sqlFile){
                    $sqlFile = Archivo::create(['nombre'=>$nombre,
                        'tipo'=>$archivo->getClientOriginalExtension(),
                        'fechasubida'=>time(),
                        'tamano'=>$archivo->getSize(),
                        'user_id'=>$user->id]);
                }else{
                    //Si existe solo actualizo fecha de subida y el tamaño.
                    $sqlFile->fechasubida = time();
                    $sqlFile->tamano = $archivo->getSize();
                    $sqlFile->save();
                }

                //Guardo el archivo en el servidor
                $archivo->move(storage_path('public'), $sqlFile->getNombreServer());
                //Se va añadiendo por base de datos el archivo subido
                $out = [
                    'code'=>'success',
                    'msg'=>'Archivos subidos'
                ];
            }
        }catch (\Exception $e){
            //Falta controlar error cuando suceda un error y aya creado el archivo en la base de datos.

            //Error de subida
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

    public function postEliminarArchivo(Request $request){
        $id = $request->id;
        $archivo = Auth::user()->archivos()->find($id);

        //elimino el archivo del servidor
        if (unlink(storage_path('public').'/'.$archivo->getNombreServer())){
            $res = response()->json([
                'code'=>'success',
                "msg"=>'Archivo eliminado exitosamente'
            ]);
            //elimino el archivo en SQL
            $archivo->delete();
        }else{
            error_log("Error: No se pudo eliminar el archivo");
            $res = response()->json([
                'code'=>'error',
                "msg"=>'No se pudo eliminar el archivo'
            ],500);
        }
        return $res;
    }

    //Elimina los archivos automaticamente mediante una tarea programada
    public function getEliminarAuto(){

    }
}
