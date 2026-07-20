<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PrefixeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'prefixe' => '032',
                'id_operateur' => 2
            ],
            [
                'prefixe' => '033',
                'id_operateur' => 1
            ],
            [
                'prefixe' => '034',
                'id_operateur' => 2
            ],
            [
                'prefixe' => '038',
                'id_operateur' => 2
            ]
        ];

        $this->db->table('prefixe')->insertBatch($data);
    }
}