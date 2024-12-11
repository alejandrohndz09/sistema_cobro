<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Mail\CredencialesMail;
use Illuminate\Support\Facades\Mail;

class UsuarioController extends Controller
{
    
     public function index() {
      $usuarios = Usuario::all();
      $empleados = Empleado::all(); // Asegúrate de que el modelo `Empleado` exista y apunte a la tabla correcta
      $usuarios = DB::table('usuario')
         ->leftJoin('empleado', 'usuario.idEmpleado', '=', 'empleado.idEmpleado')
         ->select('usuario.*', 'empleado.nombres as nombres')
         ->get();
    
        return view('opciones.usuarios.index', compact('usuarios', 'empleados'));

  } 

  public function store(Request $request) {
    $request->validate([
        'usuario' => 'required|min:3|unique:usuario,usuario',
        'email' => 'required|email|unique:usuario,email',
        'idEmpleado' => 'required|exists:empleado,idEmpleado',
    ]);

    // Generar contraseña
    $clave = Str::random(8); // Genera una contraseña aleatoria

    // Crear el usuario
    $usuario = new Usuario();
    $usuario->idusuario = $this->generarId();
    $usuario->usuario = $request->post('usuario');
    $usuario->email = $request->post('email');
    $usuario->clave = Hash::make($clave); // Encripta la contraseña
    $usuario->estado = 1;
    $usuario->idEmpleado = $request->post('idEmpleado');
    $usuario->save();

    // Enviar el correo
    try {
        Mail::to($usuario->email)->send(new CredencialesMail($usuario->usuario, $clave));
    } catch (\Exception $e) {
        return response()->json([
            'type' => 'warning',
            'message' => 'Usuario creado, pero no se pudo enviar el correo. Por favor verifica la configuración de correo.'. $e->getMessage(),
        ]);
    }

    return response()->json([
        'type' => 'success',
        'message' => 'Usuario creado exitosamente. Las credenciales fueron enviadas al correo.',
    ]);
}


     
     function generarPassword($longitud = 12) {
         $caracteres = 'abcdefghijklmnopqrstuvwxyz0123456789'; // Letras minúsculas y números
         $password = '';
         $max = strlen($caracteres) - 1;
     
         for ($i = 0; $i < $longitud; $i++) {
             $password .= $caracteres[rand(0, $max)];
         }
     
         return $password;
     }
     
    public function edit($id){
        $usuario = Usuario::find($id);
        return response()->json($usuario);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'usuario' => 'required|min:3|unique:usuario,usuario,' . $id . ',idusuario',
            'idEmpleado' => 'required|exists:empleado,idEmpleado',
        ]);
    
        // Obtener el usuario actual
        $usuario = Usuario::find($id);
    
        if (!$usuario) {
            return response()->json(['type' => 'error', 'message' => 'Usuario no encontrado.'], 404);
        }
    
        // Verificar si el usuario ha cambiado
        $usuarioAnterior = $usuario->usuario; // Guardar el usuario anterior
        $usuario->usuario = $request->post('usuario'); // Actualizar el nombre de usuario
        $usuario->idEmpleado = $request->post('idEmpleado');
        $usuario->estado = $request->post('estado', $usuario->estado);
    
        $usuario->save();
    
        // Si el usuario ha cambiado, enviar un correo con el nuevo nombre
        if ($usuarioAnterior !== $usuario->usuario) {
            try {
                Mail::to($usuario->email)->send(new CredencialesMail($usuario->usuario, 'La contraseña permanece igual.'));
            } catch (\Exception $e) {
                return response()->json([
                    'type' => 'warning',
                    'message' => 'Usuario actualizado, pero no se pudo enviar el correo. Por favor verifica la configuración de correo.',
                ]);
            }
        }
    
        return response()->json([
            'type' => 'success',
            'message' => 'Usuario actualizado correctamente.',
        ]);
    }
    

    public function destroy($id) {
        $usuario = Usuario::find($id);
        $usuario->delete();

        return response()->json(['type' => 'success', 'message' => 'Usuario eliminado exitosamente.']);
    }

    public function baja($id) {
        $usuario = Usuario::find($id);
        $usuario->estado = 0;
        $usuario->save();

        return response()->json(['type' => 'success', 'message' => 'Usuario deshabilitado exitosamente.']);
    }

    public function alta($id) {
        $usuario = Usuario::find($id);
        $usuario->estado = 1;
        $usuario->save();

        return response()->json(['type' => 'success', 'message' => 'Usuario habilitado exitosamente.']);
    }
 
    public function generarId() {
        $ultimoUsuario = Usuario::latest('idusuario')->first();

        if (!$ultimoUsuario) {
            $nuevoId = 'US0001';
        } else {
            $ultimoNumero = intval(substr($ultimoUsuario->idusuario, 2));
            $nuevoId = 'US' . str_pad($ultimoNumero + 1, 4, '0', STR_PAD_LEFT);
        }

        return $nuevoId;
    }

    public function getUsuarios() {
        $usuarios = Usuario::all();
        $usuarios = DB::table('usuario')
        ->leftJoin('empleado', 'usuario.idEmpleado', '=', 'empleado.idEmpleado')
        ->select('usuario.*', 'empleado.nombres as nombres') // Incluir el nombre del empleado
        ->get();
        return response()->json($usuarios);
    }

    public function enviarCredenciales($id)
    {
        // Buscar al usuario por su ID
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json([
                'type' => 'error',
                'message' => 'Usuario no encontrado.',
            ], 404);
        }

        // Validar que el usuario tenga un correo válido
        if (empty($usuario->email)) {
            return response()->json([
                'type' => 'error',
                'message' => 'El usuario no tiene un correo electrónico registrado.',
            ], 400);
        }

        // Generar una nueva contraseña (sin encriptar para enviarla por correo)
        $nuevaClave = Str::random(8);

        // Actualizar el usuario con la nueva clave encriptada
        $usuario->clave = Hash::make($nuevaClave);
        $usuario->estado = 1; // Cambiar estado a "activo" si es necesario
        $usuario->save();

        // Enviar las credenciales al correo del usuario
        try {
            Mail::to($usuario->email)->send(new CredencialesMail($usuario->usuario, $nuevaClave));

            return response()->json([
                'type' => 'success',
                'message' => 'Credenciales enviadas exitosamente al correo del usuario.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al enviar el correo: ' . $e->getMessage(),
            ], 500);
        }
    }
  
}
