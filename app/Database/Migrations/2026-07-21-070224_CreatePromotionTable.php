<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePromotionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
            ],
            'valeur' => [
                'type'       => 'REAL CHECK (valeur > 0)',
                'null'       => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('promotion', true);
    }

    public function down()
    {
        $this->forge->dropTable('promotion', true);
    }
}
