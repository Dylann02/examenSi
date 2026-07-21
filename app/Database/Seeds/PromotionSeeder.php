<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PromotionSeeder extends Seeder
{
     public function run()
    {
        $this->db->table('promotion')->truncate();

        $data = [
            ['id_promotion' => 1, 'pourcentagew' => 10],
        ];

        $this->db->table('promotion')->insertBatch($data);
    }
}
