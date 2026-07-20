<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeModel extends Model
{
    protected $table            = 'bareme';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_operation', 'montant_min', 'montant_max', 'frais'];

    // Validation
    protected $validationRules = [
        'id_operation' => 'required|is_natural_no_zero|is_not_unique[type_operation.id]',
        'montant_min'  => 'permit_empty|numeric',
        'montant_max'  => 'permit_empty|numeric',
        'frais'        => 'required|numeric',
    ];

    /**
     * Récupère le frais applicable selon l'opération et le montant
     */
    public function getFrais(int $idOperation, float $montant): float
    {
        $result = $this->where('id_operation', $idOperation)
                       ->where('montant_min <=', $montant)
                       ->where('montant_max >=', $montant)
                       ->first();

        return $result ? (float) $result['frais'] : 0.0;
    }
}