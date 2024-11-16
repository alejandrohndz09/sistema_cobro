<?php

namespace App\Http\Controllers;

use App\Mail\RecuperarClaveMail;
use App\Models\Miembro;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    // protected $guard = 'usuario';

    public function show()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required',
            'contraseña' => 'required',
        ]);
        $user = Usuario::with('empleado')->where('usuario', $request->usuario)->first();

        $alert = null;

        if (!$user || !Hash::check($request->contraseña, $user->clave)) {
            // Mensaje unificado para usuario inexistente o contraseña incorrecta
            $alert = [
                'type' => 'error',
                'message' => 'Credenciales incorrectas.',
            ];
        } elseif ($user->estado == 0) {
            $alert = [
                'type' => 'error',
                'message' => 'El usuario está inactivo.',
            ];
        } elseif ($user->empleado && $user->empleado->estado == 0) {
            // Solo verifica el estado del empleado si el usuario tiene uno asociado
            $alert = [
                'type' => 'error',
                'message' => 'El empleado asociado está inactivo.',
            ];
        } else {
            Auth::login($user);
            $request->session()->regenerate();
            $alert = [
                'type' => 'success',
                'message' => '¡Bienvenido/a, ' . Auth::user()->usuario . '!',
            ];
            session()->flash('alert', $alert);
            return redirect('/');
        }

        session()->flash('alert', $alert);
        return redirect()->back();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect('/');
    }

    // public function cambiarClaveTemporal(Request $request)
    // {

    //     $usuario = Usuario::find($request->post('usuario'));
    //     $usuario->estado = 1;
    //     $usuario->clave = Hash::make($request->post('clave1'));
    //     $usuario->save();
    //     Auth::login($usuario);
    //     $request->session()->regenerate();
    //     $alert = array(
    //         'type' => 'success',
    //         'message' => '¡Bienvenido/a, ' . Auth::user()->usuario . '!',
    //     );

    //     session()->flash('alert', $alert);
    //     return redirect('/');
    // }

    // public function recuperarClaveMail(Request $request)
    // {
    //     $correo = $request->post('correo');

    //     $miembro = Miembro::where('correo', $correo)->first();
    //     if ($miembro && !$miembro->usuarios->isEmpty()) {
    //         $usuario = $miembro->usuarios->first();
    //         $token = Str::random(8);
    //         $usuario->token = Hash::make($token);
    //         $usuario->save();
    //         $mail = new RecuperarClaveMail($usuario, $token);
    //         Mail::to($request->post('correo'))->send($mail);

    //         $alert = array(
    //             'type' => 'info',
    //             'message' => 'Se ha enviado un código de seguridad, por favor verifique su correo.',
    //         );

    //         session()->flash('alert', $alert);

    //         return view('usuario.codigoSeguridad')->with([
    //             'usuario' => $usuario,
    //         ]);
    //     } else {
    //         $alert = array(
    //             'type' => 'warning',
    //             'message' => 'Este correo no esta asociado a ningun usuario',
    //         );

    //         session()->flash('alert', $alert);
    //         return redirect()->back();
    //     }
    // }

    // public function verificarToken($token, $id)
    // {
    //     $usuario = Usuario::find($id);
    //     // Verifica si se encontró un usuario con el token proporcionado

    //     $valido = Hash::check($token, $usuario->token) ? 1 : 0;
    //     return response()->json([
    //         'valido' => $valido,
    //         'codEncrypt' => bcrypt($token),
    //         'usuar' => Usuario::where('token', bcrypt($token))->first()
    //     ]);
    // }

    // public function recuperarClave(Request $request)
    // {
    //     $user = Usuario::find($request->post('usuario'))->first();

    //     return view('usuario.actualizarClave')->with([
    //         'usuario' => $user,
    //         'opcion' => 1,
    //     ]);
    // }
}
