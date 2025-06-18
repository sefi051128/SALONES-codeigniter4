<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password', 'role', 'contact_info'];
    protected $returnType = 'array'; // Para compatibilidad con password_verify

    public function getUser($username)
    {
        return $this->where('username', $username)->first();
    }

    public function createUser($data)
    {
        // Asegurar que la contraseña se hashee correctamente
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return $this->insert($data);
    }
}