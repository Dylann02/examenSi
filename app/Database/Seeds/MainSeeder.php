<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        $this->call('OperateurSeeder');
        $this->call('PrefixeSeeder');
        $this->call('TypeOperationSeeder');
        $this->call('BaremeSeeder');
        $this->call('ClientSeeder');
        $this->call('NumeroSeeder');
    }
}