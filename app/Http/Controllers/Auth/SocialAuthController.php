<?php


namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\User;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class SocialAuthController extends Controller
{


    public function principal(){
        return "este controlador es todo lo relacionado con login y registro SOCIAL!";
    }


    /**
     * redirectToProvider redirecciona la peticion al proveedor, (google)
     * @param $provedor
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider($provedor)
    {
        session(['urlAntes' => url()->previous()]);
        return Socialite::driver($provedor)->redirect();
    }


    /**
     * handleProviderCallback es la respuesta del proveedor para crear el usuario si no existe e iniciar sesion
     * @param $proveedor (puede ser google, facebook, linkedin etc).
     */
    public function handleProviderCallback($proveedor)
    {
        $urlAntes="/";
        try{
            $user = Socialite::driver($proveedor)->user();
            $urlAntes=session('urlAntes');

            $this->findOrCreateUser($proveedor, $user);
            return redirect($urlAntes);

        }catch(\Exception $ex){
            error_log('Error_social: '.$ex->getMessage());
            return redirect($urlAntes)->with('error', 'Se presento un error interno, contacte al administrador');
        }
    }

    /**
     * findOrCreateUser crea un usuario si no existe e inicia sesion
     * @param $proveedor
     * @param $callUser
     * @param $redirect
     * @return mixed
     */
    private function findOrCreateUser($proveedor, $callUser)
    {
        if (!($proveedor=='' or $callUser=='')){
            $authUser='';
            if ($callUser->email){ //busca por email
                $authUser = User::where('email', $callUser->email)->first();
            }

            if (!$authUser){//buscar por id
                $authUser = User::where('socials_id', 'LIKE', '%'.$proveedor.'::'.$callUser->id.'%')->first();
            }

            if ($authUser) {
                //si no esta el proveedor con su id, lo agrega y guarda.
                if(stripos($authUser->socials_id, $proveedor.'::'.$callUser->id)===false){
                    $authUser->socials_id.=$proveedor.'::'.$callUser->id."\n";
                    $authUser->save();
                }

            }else{//si no lo encontro por ninguno, lo crea
                $authUser = User::create([
                    'name' => $callUser->name,
                    'email' => ($callUser->email)?$callUser->email:time().'@ramdon_'.$proveedor.'.com',
                    'avatar' => $callUser->avatar,
                    'socials_id' => $proveedor.'::'.$callUser->id."\n"
                ]);
            }
            Auth::login($authUser);
            return true;
        }
        return false;
    }
}
