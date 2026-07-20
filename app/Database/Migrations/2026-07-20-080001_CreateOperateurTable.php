<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOperateurTable extends Migration
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
                'null' => false,
            ],
            'autre_operateur' => [
                'type'    => 'INTEGER CHECK (autre_operateur IN (0, 1))',
                'null'    => false,
                'default' => 0,
            ],
            'pct_commission' => [
                'type'    => 'REAL CHECK (pct_commission >= 0 AND pct_commission <= 100)',
                'null'    => false,
                'default' => 0,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('nom');
        $this->forge->createTable('operateur', true);

        $this->db->table('operateur')->insert([
            'nom'             => 'Soi-même',
            'autre_operateur' => 0,
            'pct_commission'  => 0,
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('operateur', true);
    }
}
