<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBaremeTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>[
                'type'=>'INT',
                'unsigned'=>true,
                'auto_increment'=>true
            ],
            'id_operation'=>[
                'type'=>'INT',
                'unsigned'=>true
            ],
            'montant_min'=>[
                'type'=>'DECIMAL',
                'constraint'=>'15,2'
            ],
            'montant_max'=>[
                'type'=>'DECIMAL',
                'constraint'=>'15,2'
            ],
            'frais'=>[
                'type'=>'DECIMAL',
                'constraint'=>'15,2'
            ]
        ]);

        $this->forge->addKey('id',true);

        $this->forge->addForeignKey(
            'id_operation',
            'type_operation',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('bareme');
    }

    public function down()
    {
        $this->forge->dropTable('bareme');
    }
}