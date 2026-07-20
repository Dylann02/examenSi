<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeModel extends Model
{
    protected $table            = 'bareme';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id_operation', 'id_operateur', 'montant_min', 'montant_max', 'frais'];

    public function getBaremesWithDetails(?int $idOperateur = null, ?int $idOperation = null): array
    {
        $builder = $this->select('bareme.*, type_operation.nom as operation_nom, operateur.nom as operateur_nom')
                        ->join('type_operation', 'type_operation.id = bareme.id_operation')
                        ->join('operateur', 'operateur.id = bareme.id_operateur');

        if (!empty($idOperateur)) {
            $builder->where('bareme.id_operateur', $idOperateur);
        }

        if (!empty($idOperation)) {
            $builder->where('bareme.id_operation', $idOperation);
        }

        return $builder->orderBy('bareme.id_operateur', 'ASC')
                       ->orderBy('bareme.id_operation', 'ASC')
                       ->orderBy('bareme.montant_min', 'ASC')
                       ->findAll();
    }
}