<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNumeroTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>[
                'type'=>'INT',
                'unsigned'=>true,
                'auto_increment'=>true
            ],
            'numero'=>[
                'type'=>'VARCHAR',
                'constraint'=>10
            ],
            'solde'=>[
                'type'=>'DECIMAL',
                'constraint'=>'15,2',
                'default'=>0
            ],
            'etat'=>[
                'type'=>'ENUM',
                'constraint'=>['ACTIF','BLOQUE'],
                'default'=>'ACTIF'
            ],
            'id_client'=>[
                'type'=>'INT',
                'unsigned'=>true
            ],
            'id_operateur'=>[
                'type'=>'INT',
                'unsigned'=>true
            ]
        ]);

        $this->forge->addKey('id',true);
        $this->forge->addUniqueKey('numero');

        $this->forge->addForeignKey('id_client','client','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('id_operateur','operateur','id','CASCADE','CASCADE');

        $this->forge->createTable('numero');
    }

    public function down()
    {
        $this->forge->dropTable('numero');
    }
}