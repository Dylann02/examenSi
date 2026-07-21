<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class  CreateEpargneTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'pourcentage_epargne' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'solde_epargne' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'id_client'=>[
                'type'=>'INT',
                'unsigned'=>true
            ],

        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('id_client', 'clients', 'id', 'CASCADE', 'CASCADE');
    
        $this->forge->createTable('epargne', true);
    }

    public function down()
    {
        $this->forge->dropTable('epargne', true);
    }
}