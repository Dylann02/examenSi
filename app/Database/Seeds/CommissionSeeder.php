<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CommissionSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('commission_interoperateur')->truncate();

        $data = [
            [
                'id_operateur_source' => 1, // Telma
                'id_operateur_dest'   => 2, // Orange
                'pourcentage'         => 1.00, // 1%
            ],
            [
                'id_operateur_source' => 1, // Telma
                'id_operateur_dest'   => 3, // Airtel
                'pourcentage'         => 2.00, // 2%
            ],
        ];

        $this->db->table('commission_interoperateur')->insertBatch($data);
    }
}