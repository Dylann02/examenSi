<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrefixeTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>[
                'type'=>'INT',
                'unsigned'=>true,
                'auto_increment'=>true
            ],
            'prefixe'=>[
                'type'=>'VARCHAR',
                'constraint'=>3
            ],
            'id_operateur'=>[
                'type'=>'INT',
                'unsigned'=>true
            ]
        ]);

        $this->forge->addKey('id',true);
        $this->forge->addUniqueKey('prefixe');

        $this->forge->addForeignKey(
            'id_operateur',
            'operateur',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('prefixe');
    }

    public function down()
    {
        $this->forge->dropTable('prefixe');
    }
}