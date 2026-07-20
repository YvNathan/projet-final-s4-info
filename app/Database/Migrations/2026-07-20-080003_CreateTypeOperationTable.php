<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTypeOperationTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'libelle' => [
                'type'       => 'TEXT',
                'null'       => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('libelle');
        $this->forge->createTable('type_operation', true);
    }

    public function down()
    {
        $this->forge->dropTable('type_operation', true);
    }
}
