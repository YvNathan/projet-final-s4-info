<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TypeOperationSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['libelle' => 'depot'],
            ['libelle' => 'retrait'],
            ['libelle' => 'transfert'],
        ];

        $this->db->table('type_operation')->insertBatch($types);
    }
}
