<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BaremeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // ==========================================
            // 1. DEPOT (id_operation = 1)
            // ==========================================
            [
                'id_operation' => 1,
                'montant_min'  => 100,
                'montant_max'  => 2000000,
                'frais'        => 0
            ],

            // ==========================================
            // 2. RETRAIT (id_operation = 2)
            // ==========================================
            [
                'id_operation' => 2,
                'montant_min'  => 100,
                'montant_max'  => 1000,
                'frais'        => 100
            ],
            [
                'id_operation' => 2,
                'montant_min'  => 1001,
                'montant_max'  => 5000,
                'frais'        => 200
            ],
            [
                'id_operation' => 2,
                'montant_min'  => 5001,
                'montant_max'  => 10000,
                'frais'        => 300
            ],
            [
                'id_operation' => 2,
                'montant_min'  => 10001,
                'montant_max'  => 25000,
                'frais'        => 500
            ],
            [
                'id_operation' => 2,
                'montant_min'  => 25001,
                'montant_max'  => 50000,
                'frais'        => 1000
            ],
            [
                'id_operation' => 2,
                'montant_min'  => 50001,
                'montant_max'  => 100000,
                'frais'        => 1500
            ],
            [
                'id_operation' => 2,
                'montant_min'  => 100001,
                'montant_max'  => 250000,
                'frais'        => 2500
            ],
            [
                'id_operation' => 2,
                'montant_min'  => 250001,
                'montant_max'  => 500000,
                'frais'        => 4000
            ],
            [
                'id_operation' => 2,
                'montant_min'  => 500001,
                'montant_max'  => 1000000,
                'frais'        => 6000
            ],
            [
                'id_operation' => 2,
                'montant_min'  => 1000001,
                'montant_max'  => 2000000,
                'frais'        => 10000
            ],

            // ==========================================
            // 3. TRANSFERT (id_operation = 3)
            // ==========================================
            [
                'id_operation' => 3,
                'montant_min'  => 1,
                'montant_max'  => 99,
                'frais'        => 0
            ],
            [
                'id_operation' => 3,
                'montant_min'  => 100,
                'montant_max'  => 1000,
                'frais'        => 50
            ],
            [
                'id_operation' => 3,
                'montant_min'  => 1001,
                'montant_max'  => 5000,
                'frais'        => 50
            ],
            [
                'id_operation' => 3,
                'montant_min'  => 5001,
                'montant_max'  => 10000,
                'frais'        => 100
            ],
            [
                'id_operation' => 3,
                'montant_min'  => 10001,
                'montant_max'  => 25000,
                'frais'        => 200
            ],
            [
                'id_operation' => 3,
                'montant_min'  => 25001,
                'montant_max'  => 50000,
                'frais'        => 400
            ],
            [
                'id_operation' => 3,
                'montant_min'  => 50001,
                'montant_max'  => 100000,
                'frais'        => 800
            ],
            [
                'id_operation' => 3,
                'montant_min'  => 100001,
                'montant_max'  => 250000,
                'frais'        => 1500
            ],
            [
                'id_operation' => 3,
                'montant_min'  => 250001,
                'montant_max'  => 500000,
                'frais'        => 1500
            ],
            [
                'id_operation' => 3,
                'montant_min'  => 500001,
                'montant_max'  => 1000000,
                'frais'        => 2500
            ],
            [
                'id_operation' => 3,
                'montant_min'  => 1000001,
                'montant_max'  => 2000000,
                'frais'        => 3000
            ],
            [
                'id_operation' => 3,
                'montant_min'  => 2000001,
                'montant_max'  => 5000000,
                'frais'        => 5000
            ],
        ];


        $this->db->table('bareme')->insertBatch($data);
    }
}