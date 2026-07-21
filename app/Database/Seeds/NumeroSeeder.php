<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NumeroSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('numero')->truncate();

        $data = [
            [
                'numero'       => '0341234567',
                'solde'        => 500000,
                'etat'         => 'ACTIF',
                'id_client'    => 1,
                'id_operateur' => 1 // Telma (034)
            ],
            [
                'numero'       => '0339876543',
                'solde'        => 250000,
                'etat'         => 'ACTIF',
                'id_client'    => 2,
                'id_operateur' => 3 // Airtel (033)
            ]
        ];

        $this->db->table('numero')->insertBatch($data);
    }
}