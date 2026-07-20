<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OperateurSeeder extends Seeder
{
    public function run()
    {
        $autresOperateurs = [
            ['nom' => 'Orange Money', 'autre_operateur' => 1, 'pct_commission' => 1.5],
            ['nom' => 'Airtel Money', 'autre_operateur' => 1, 'pct_commission' => 2.0],
        ];

        $this->db->table('operateur')->insertBatch($autresOperateurs);
    }
}
