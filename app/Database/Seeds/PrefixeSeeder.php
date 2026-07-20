<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PrefixeSeeder extends Seeder
{
    public function run()
    {
        // Evite l'erreur UNIQUE constraint failed en vidant la table avant insertion
        $this->db->table('prefixe')->truncate();

        $data = [
            // Telma (ID 1)
            ['id_operateur' => 1, 'prefixe' => '034'],
            ['id_operateur' => 1, 'prefixe' => '038'],

            // Orange (ID 2)
            ['id_operateur' => 2, 'prefixe' => '032'],
            ['id_operateur' => 2, 'prefixe' => '037'],

            // Airtel (ID 3)
            ['id_operateur' => 3, 'prefixe' => '033'],
        ];

        $this->db->table('prefixe')->insertBatch($data);
    }
}