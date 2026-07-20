<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OperateurSeeder extends Seeder
{
    public function run()
    {
        $operateurs = [
            'Airtel Madagascar' => ['033'],
            'Yas'               => ['034', '038'],
            'Orange'            => ['032', '037'],
        ];

        foreach ($operateurs as $nom => $prefixes) {
            $this->db->table('operateur')->insert(['nom' => $nom]);
            $idOperateur = $this->db->insertID();

            foreach ($prefixes as $prefixe) {
                $this->db->table('config')->insert([
                    'id_operateur' => $idOperateur,
                    'prefixe'      => $prefixe,
                ]);
            }
        }
    }
}
