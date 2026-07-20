<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ConfigSeeder extends Seeder
{
    public function run()
    {
        $prefixes = [
            ['prefixe' => '033'],
            ['prefixe' => '034'],
            ['prefixe' => '038'],
            ['prefixe' => '032'],
            ['prefixe' => '037'],
        ];

        $this->db->table('config')->insertBatch($prefixes);
    }
}
