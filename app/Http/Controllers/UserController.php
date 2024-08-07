<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DAO\UsersDao;

class UserController extends Controller
{
    protected $UserDao;

    public function __construct(UsersDao $UserDao)
    {
        $this->UserDao = $UserDao;
    }

     // Registrar usuario
    public function registerUser(Request $request)
     {
        echo "ingreso";
         $validatedData = $request->validate([
             'name' => 'required|string|max:255',
             'email' => 'required|string|email|max:255|unique:users',
             'password' => 'required|string|min:4',
         ]);
         $user = $this->UserDao->registerUser([
             'name' => $validatedData['name'],
             'email' => $validatedData['email'],
             'password' => bcrypt($validatedData['password']),
         ]);

         return response()->json($user, 201);
     }

    // Obtener todos los usuarios
    public function users()
    {
        $usuarios = $this->UserDao->getAllUsers();

        if ($usuarios->isEmpty()) {
            return response()->json([
                'message' => 'No existen datos.'
            ], 404);
        }
        return response()->json($usuarios);
    }

    // Obtener usuario por id
    public function findUser($id)
    {
        $usuario = $this->UserDao->getUserById($id);
        if ($usuario) {
            return response()->json($usuario);
        } else {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    }


    // Actualizar usuario por id
    public function updateUsers(Request $request, $id)
    {
        $data = $request->all();
        $usuario = $this->UserDao->updateUser($id, $data);
        if ($usuario) {
            return response()->json($usuario);
        } else {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    }

    // Elimina usuario por id
    public function deleteUser($id)
    {
        $deleted = $this->UserDao->deleteUser($id);
        if ($deleted) {
            return response()->json(['message' => 'Usuario eliminado con exito']);
        } else {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    }
}
