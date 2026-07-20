<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClientTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>[
                'type'=>'INT',
                'unsigned'=>true,
                'auto_increment'=>true
            ],
            'nom'=>[
                'type'=>'VARCHAR',
                'constraint'=>100
            ],
            'prenom'=>[
                'type'=>'VARCHAR',
                'constraint'=>100,
                'null'=>true
            ],
            'cin'=>[
                'type'=>'VARCHAR',
                'constraint'=>20
            ]
        ]);

        $this->forge->addKey('id',true);
        $this->forge->addUniqueKey('cin');

        $this->forge->createTable('client');
    }

    public function down()
    {
        $this->forge->dropTable('client');
    }
}