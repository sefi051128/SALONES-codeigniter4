<?php
namespace App\Models;

use CodeIgniter\Model;

class SedeModel extends Model
{
    protected $table = 'venues';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'location', 'capacity', 'lat', 'lng', 'place_id'];
    protected $returnType = 'array';
}