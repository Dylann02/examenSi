<?php

namespace App\Models;

use CodeIgniter\Model;

class EpargneModel extends Model
{
    protected $table            = 'epargne';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['pourcentage_epargne', 'solde_epargne','id_client'];

    public function findByIdLicent($id_client){
        $sql = $this->builder()
                    ->where('id_client' , 'id_client')
                    ->get()
                    ->getRowArray();
        return $sql;
    }
}