<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run()
    {
        $valeur = ["valeur" => 10.0];
        $this->db->table('promotion')->insert($valeur);
    }
}
