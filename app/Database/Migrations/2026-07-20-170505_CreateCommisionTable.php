<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCommisionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_operateur_source' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'id_operateur_dest' => [
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

        // Définition de la clé primaire composite
        $this->forge->addPrimaryKey(['id_operateur_source', 'id_operateur_dest']);

        // Création finale de la table
        $this->forge->createTable('commission_interoperateur');
    }

    public function down()
    {
        // Suppression de la table en cas de rollback
        $this->forge->dropTable('commission_interoperateur');
    }
}