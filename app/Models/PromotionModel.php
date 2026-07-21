<?php

namespace App\Models;

use CodeIgniter\Model;

class PromotionModel extends Model
{
    protected $table            = 'promotion';
    protected $allowedFields    = ['id_promotion','pourcentage'];

    public function getPromotion(){{
        $sql= $this->builder()
        ->limit(1);

        return $sql;

    }}

    
}