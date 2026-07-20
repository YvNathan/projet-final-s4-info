<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConfigTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'prefixe' => [
                'type'       => 'TEXT',
                'null'       => false,
            ],
            'id_operateur' => [
                'type' => 'INTEGER',
                'null' => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('prefixe');
        $this->forge->addForeignKey('id_operateur', 'operateur', 'id', '', 'CASCADE');
        $this->forge->createTable('config', true);
    }

    public function down()
    {
        $this->forge->dropTable('config', true);
    }
}
