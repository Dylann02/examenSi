<?php

namespace App\Models;

use CodeIgniter\Model;

class CommissionInteroperateurModel extends Model
{
    protected $table = 'commission_interoperateur';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = false;

    protected $returnType = 'array';

    protected $allowedFields = [
        'id_operateur_source',
        'id_operateur_dest',
        'pourcentage'
    ];

    protected $useTimestamps = false;
}