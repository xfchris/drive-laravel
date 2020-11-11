<?php

namespace App\Http\Controllers;

use App\Archivo;
use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->eliminarArchivosVencidos();
    }

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
        $user = Auth::user();
        return view('dashboard.index', compact('user'));
    }

    /**
     * Obtiene la lista de archivos en formato json
     * @return array
     */
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
                    'Opciones'=>"$archivo->id"
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
            //compruebo que tenga plan vigente
            if (!$user->getUltimoPlanVigente()){
                throw new \Exception('Antes de subir un archivo, primero actualiza tu plan', 400);
            }
            //compruebo que el archivo no supere x mbs
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
                    'status'=>'success',
                    'msg'=>'Archivos subidos'
                ];
            }
        }catch (\Exception $e){
            //Falta controlar error cuando suceda un error y aya creado el archivo en la base de datos.

            if ($e->getCode()==400){
                $code=400;
                $msg = $e->getMessage();
            }else{
                $code = 500;
                $msg = 'Se presentó un error interno, consulte al administrador';
                error_log('Error_subida: '.$e->getMessage());
            }
            $out = [
                'status'=>'error',
                'msg'=>$msg
            ];
        }

        //Subo cada uno de los archivos a su respectivo directorio
        return response()->json($out, $code);
    }

    /**
     * Eliminar un archivo seleccionado
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postEliminarArchivo(Request $request){
        $id = $request->id;
        $archivo = Auth::user()->archivos()->find($id);

        //elimino el archivo del servidor
        if (unlink(storage_path('public').'/'.$archivo->getNombreServer())){
            $res = response()->json([
                'status'=>'success',
                "msg"=>'Archivo eliminado exitosamente'
            ]);
            //elimino el archivo en SQL
            $archivo->delete();
        }else{
            error_log("Error: No se pudo eliminar el archivo");
            $res = response()->json([
                'status'=>'error',
                "msg"=>'No se pudo eliminar el archivo'
            ],500);
        }
        return $res;
    }


    /**
     * Funcion que elimina archivos vencidos
     */
    private function eliminarArchivosVencidos(){
        //1. Obtengo todos los archivos de los usuarios vencidos
        $archivos = Archivo::whereIn('user_id', function ($query){

            //selecciono usuarios que no esten en tabla de planes comprados (user_plan)
            $query->select('id')->from('users')->whereNotIn('id', function($query){
                //selecciono planes comprados por usuarios activos
                $query->select('user_id')->from('user_plan')->where('estado',1)->where('fechafin','>', time());
            });
        })->get();

        //3. Elimino archivos vencidos del sistema
        $idEliminados = [];
        foreach ($archivos as $archivo) {
            $ruta = storage_path('public').'/'.$archivo->getNombreServer();
            if(file_exists($ruta)){
                if(unlink($ruta)){
                    $idEliminados[] = $archivo->id;
                }else{
                    error_log("warning_eliminar_archivo: no pudo eliminar el archivo [".$ruta."]");
                }
            }
        }
        //4. Elimino SQL de archivos vencidos por usuario
        if (count($idEliminados)){
            if (!Archivo::whereIn('id', $idEliminados)->delete()){
                error_log("warning_eliminar_archivos_sql: no pudo eliminar los archivos ".json_encode($idEliminados)." de la base de datos");
            }
        }
    }

    /**
     * Metodo para descargar un archivo
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function getDescargarArchivo($id){
        $archivo = Auth::user()->archivos()->find($id);
        if ($archivo){
            return response()->download(storage_path('public').'/'.$archivo->getNombreServer(),
                $archivo->nombre);
        }else{
            return redirect('/');
        }
    }
}
