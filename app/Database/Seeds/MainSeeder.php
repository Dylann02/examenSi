<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // 1. Désactiver la vérification des clés étrangères (Foreign Keys)
        $db->query('PRAGMA foreign_keys = OFF;');

        // 2. Exécution de tous les seeders dans l'ordre
        $this->call('OperateurSeeder');
        $this->call('PrefixeSeeder');
        $this->call('TypeOperationSeeder');
        $this->call('BaremeSeeder');
        $this->call('CommissionSeeder');
        $this->call('ClientSeeder');
        $this->call('NumeroSeeder');
    
        // 3. Réactiver la vérification des clés étrangères
        $db->query('PRAGMA foreign_keys = ON;');
    }
}