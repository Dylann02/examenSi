<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OperateurSeeder extends Seeder
{
    public function run()
    {
        // Nettoyage de la table
        $this->db->table('operateur')->truncate();

        $data = [
            ['id' => 1, 'nom' => 'Telma'],
            ['id' => 2, 'nom' => 'Orange'],
            ['id' => 3, 'nom' => 'Airtel'],
        ];

        $this->db->table('operateur')->insertBatch($data);
    }
}