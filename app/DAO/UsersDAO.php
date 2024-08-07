<?php
namespace App\DAO;

use App\Singleton\DatabaseConectionSingleton;

class UsersDao{

    protected $dbConnection;

    public function __construct()
    {
        //conexion unica de la base de datos
        $this->dbConnection = DatabaseConectionSingleton::getInstance();
    }

    //Funciones para operaciones CRUD
    public function getAllUsers()
    {
        return $this->dbConnection->table('users')->get();
    }

    public function getUserById($id)
    {
        return $this->dbConnection->table('users')->find($id);
    }

    public function registerUser($data)
    {
        return $this->dbConnection->table('users')->insert($data);
    }

    public function updateUser($id, $data)
    {
        return $this->dbConnection->table('users')->where('id', $id)->update($data);
    }

    public function deleteUser($id)
    {
        return $this->dbConnection->table('users')->where('id', $id)->delete();
    }
}
