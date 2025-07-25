<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password', 'role', 'phone', 'email'];
    protected $returnType = 'array';
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    // En tu UserModel
    protected $useTimestamps = true; // Esto automáticamente maneja created_at y updated_at
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        } else {
            // Si el password está vacío, lo eliminamos para no actualizarlo
            unset($data['data']['password']);
        }
        return $data;
    }

    public function getUser($username)
    {
        return $this->where('username', $username)->first();
    }

    public function getUserById($id)
    {
        return $this->find($id);
    }

    public function createUser(array $data)
    {
        return $this->insert($data);
    }
    
    public function createAdminUser(array $data)
    {
        return $this->insert($data);
    }

    public function updateUser($id, array $data)
    {
        return $this->update($id, $data);
    }
}