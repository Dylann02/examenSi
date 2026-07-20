<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>[
                'type'=>'INT',
                'unsigned'=>true,
                'auto_increment'=>true
            ],
            'date_transaction'=>[
                'type'=>'DATETIME'
            ],
            'montant'=>[
                'type'=>'DECIMAL',
                'constraint'=>'15,2'
            ],
            'frais'=>[
                'type'=>'DECIMAL',
                'constraint'=>'15,2',
                'default'=>0
            ],
            'statut'=>[
                'type'=>'ENUM',
                'constraint'=>['EN_ATTENTE','SUCCES','ECHEC','ANNULE'],
                'default'=>'SUCCES'
            ],
            'id_operation'=>[
                'type'=>'INT',
                'unsigned'=>true
            ],
            'id_numero_source'=>[
                'type'=>'INT',
                'unsigned'=>true,
                'null'=>true
            ],
            'id_numero_destination'=>[
                'type'=>'INT',
                'unsigned'=>true,
                'null'=>true
            ]
        ]);

        $this->forge->addKey('id',true);

        $this->forge->addForeignKey('id_operation','type_operation','id');
        $this->forge->addForeignKey('id_numero_source','numero','id');
        $this->forge->addForeignKey('id_numero_destination','numero','id');

        $this->forge->createTable('transaction_mm');
    }

    public function down()
    {
        $this->forge->dropTable('transaction_mm');
    }
}