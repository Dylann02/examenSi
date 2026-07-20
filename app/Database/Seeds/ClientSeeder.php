<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run()
    {
        $data = [

            [
                'nom'=>'Rakoto',
                'prenom'=>'Jean',
                'cin'=>'123456789012'
            ],
            [
                'nom'=>'Rabe',
                'prenom'=>'Paul',
                'cin'=>'987654321012'
            ]

        ];

        $this->db->table('client')->insertBatch($data);
    }
}