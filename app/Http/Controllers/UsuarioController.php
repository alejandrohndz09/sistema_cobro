<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
    

     public function store(Request $request){
         $request->validate([
             'usuario' => 'required|min:3',
             'idEmpleado' => 'required|exists:empleado,idEmpleado' 
         ]);
     
         $usuario = new Usuario();
         $usuario->idusuario = $this->generarId();
         $usuario->usuario = $request->post('usuario');
         $usuario->clave = $this->generarPassword(12); // Contraseña en texto plano
         $usuario->estado = 1;
         $usuario->idEmpleado = $request->post('idEmpleado');
     
         $usuario->save();
     
         return response()->json(['type' => 'success', 'message' => 'Usuario creado exitosamente.']);
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
            'usuario' => 'required|min:3',
            'idEmpleado' => 'required|exists:empleado,idEmpleado'
        ]);

        $usuario = Usuario::find($id);
        $usuario->usuario = $request->post('usuario');
        
        if ($request->filled('clave')) {
            $request->validate([
                'clave' => 'string|min:4|max:8',
            ]);
            $usuario->clave = bcrypt($request->post('clave'));
        }
        
        
        $usuario->idEmpleado = $request->post('idEmpleado');
        $usuario->estado = $request->post('estado', $usuario->estado);
        $usuario->save();

        return response()->json(['type' => 'success', 'message' => 'Usuario actualizado exitosamente.']);
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

}
