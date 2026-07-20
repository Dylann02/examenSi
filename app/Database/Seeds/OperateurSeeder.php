<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OperateurSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nom' => 'Orange Money'],
            ['nom' => 'MVola'],
            ['nom' => 'Airtel Money'],
        ];

        $this->db->table('operateur')->insertBatch($data);
    }
}