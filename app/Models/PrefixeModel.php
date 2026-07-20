<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table            = 'prefixe';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['prefixe', 'id_operateur'];

    // Récupère tous les préfixes avec le nom de leur opérateur associé.
    public function getPrefixesWithOperateurs(): array
    {
        return $this->select('prefixe.*, operateur.nom as operateur_nom')
                    ->join('operateur', 'operateur.id = prefixe.id_operateur')
                    ->findAll();
    }
}