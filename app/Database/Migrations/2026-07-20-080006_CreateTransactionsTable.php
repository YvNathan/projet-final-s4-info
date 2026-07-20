<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'id_client' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            'id_type_operation' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
            'date_heure' => [
                'type'    => 'TEXT',
                'null'    => false,
                'default' => new \CodeIgniter\Database\RawSql("(datetime('now'))"),
            ],
            'montant' => [
                'type' => 'REAL CHECK (montant > 0)',
                'null' => false,
            ],
            'frais_applique' => [
                'type'    => 'REAL CHECK (frais_applique >= 0)',
                'null'    => false,
                'default' => 0,
            ],
            'commission' => [
                'type'    => 'REAL CHECK (commission >= 0)',
                'null'    => false,
                'default' => 0,
            ],
            'numero_destinataire' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('id_client', 'client', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('id_type_operation', 'type_operation', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->createTable('transactions', true);

        $this->forge->addKey('date_heure');
        $this->forge->processIndexes('transactions');
    }

    public function down()
    {
        $this->forge->dropTable('transactions', true);
    }
}
