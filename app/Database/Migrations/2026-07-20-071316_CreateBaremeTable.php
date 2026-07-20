<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBaremeTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_operation' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'id_operateur' => [ // <-- NOUVEAU CHAMP
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'montant_min' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'montant_max' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'frais' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('id_operation', 'type_operation', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_operateur', 'operateur', 'id', 'CASCADE', 'CASCADE'); // <-- CLE ETRANGERE

        $this->forge->createTable('bareme', true);
    }

    public function down()
    {
        $this->forge->dropTable('bareme', true);
    }
}