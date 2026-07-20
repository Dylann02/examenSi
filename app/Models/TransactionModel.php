<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table            = 'transaction_mm';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'date_transaction', 
        'montant', 
        'frais', 
        'statut', 
        'id_operation', 
        'id_numero_source', 
        'id_numero_destination'
    ];

    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'montant'               => 'required|numeric',
        'frais'                 => 'permit_empty|numeric',
        'statut'                => 'permit_empty|in_list[EN_ATTENTE,SUCCES,ECHEC,ANNULE]',
        'id_operation'          => 'required|is_natural_no_zero|is_not_unique[type_operation.id]',
        'id_numero_source'      => 'permit_empty|is_natural_no_zero|is_not_unique[numero.id]',
        'id_numero_destination' => 'permit_empty|is_natural_no_zero|is_not_unique[numero.id]',
    ];
}