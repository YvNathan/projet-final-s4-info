<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFraisOperationTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'id_type_operation' => [
                'type'       => 'INTEGER',
                'null'       => false,
            ],
            'borne_min' => [
                'type' => 'REAL',
                'null' => false,
            ],
            'borne_max' => [
                'type' => "REAL CHECK (borne_max > borne_min)",
                'null' => false,
            ],
            'frais' => [
                'type' => 'REAL CHECK (frais >= 0)',
                'null' => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('id_type_operation', 'type_operation', 'id', '', 'CASCADE');
        $this->forge->createTable('frais_operation', true);
    }

    public function down()
    {
        $this->forge->dropTable('frais_operation', true);
    }
}
