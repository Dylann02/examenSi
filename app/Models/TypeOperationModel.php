<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeOperationModel extends Model
{
    protected $table            = 'type_operation';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nom'];

    protected $validationRules = [
        'nom' => 'required|max_length[30]|is_unique[type_operation.nom,id,{id}]',
    ];
}