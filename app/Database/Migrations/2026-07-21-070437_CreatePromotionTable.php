<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePromotionTable extends Migration
{
 public function up()
    {
        $this->forge->addField([
            'id_promotion' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            
            'pourcentage' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => false,
            ],
        ]);

        // Création finale de la table
        $this->forge->createTable('promotion');
    }

    public function down()
    {
        // Suppression de la table en cas de rollback
        $this->forge->dropTable('promotion');
    }
}
