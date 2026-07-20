<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TypeOperationSeeder extends Seeder
{
    public function run()
    {
        // 1. Vide la table pour éviter le "UNIQUE constraint failed"
        $this->db->table('type_operation')->truncate();

        // 2. Réinsère les types d'opérations avec leurs ID fixes
        $data = [
            ['id' => 1, 'nom' => 'Depot'],
            ['id' => 2, 'nom' => 'Retrait'],
            ['id' => 3, 'nom' => 'Transfert'],
        ];

        $this->db->table('type_operation')->insertBatch($data);
    }
}