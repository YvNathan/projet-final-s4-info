<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClientTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'nom' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'numero' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'solde' => [
                'type'    => 'REAL CHECK (solde >= 0)',
                'null'    => false,
                'default' => 0,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('numero');
        $this->forge->createTable('client', true);
    }

    public function down()
    {
        $this->forge->dropTable('client', true);
    }
}
