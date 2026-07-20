<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TypeOperationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nom' => 'Depot'],
            ['nom' => 'Retrait'],
            ['nom' => 'Transfert']
        ];

        $this->db->table('type_operation')->insertBatch($data);
    }
}