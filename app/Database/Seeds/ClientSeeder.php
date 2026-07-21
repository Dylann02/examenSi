<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run()
    {
        // Nettoyage de la table pour éviter le doublon de CIN
        $this->db->table('client')->truncate();

        $data = [
            [
                'id'     => 1,
                'nom'    => 'Rakoto',
                'prenom' => 'Jean',
                'cin'    => '123456789012'
            ],
            [
                'id'     => 2,
                'nom'    => 'Rabe',
                'prenom' => 'Paul',
                'cin'    => '987654321012'
            ]
        ];

        $this->db->table('client')->insertBatch($data);
    }
}