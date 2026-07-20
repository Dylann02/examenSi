<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TypeOperationSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('type_operation')->truncate();

        $data = [
            ['id' => 1, 'nom' => 'DEPOT'],
            ['id' => 2, 'nom' => 'RETRAIT'],
            ['id' => 3, 'nom' => 'TRANSFERT'],
        ];

        $this->db->table('type_operation')->insertBatch($data);
    }
}