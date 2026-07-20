<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ConfigSeeder extends Seeder
{
    public function run()
    {
        $operateurs   = $this->db->table('operateur')->get()->getResultArray();
        $idsByNom     = array_column($operateurs, 'id', 'nom');
        $idSoi        = $idsByNom['Soi-même'];
        $idOrange     = $idsByNom['Orange Money'] ?? null;
        $idAirtel     = $idsByNom['Airtel Money'] ?? null;

        $prefixes = [
            ['prefixe' => '034', 'id_operateur' => $idSoi],
            ['prefixe' => '038', 'id_operateur' => $idSoi],
        ];

        if ($idOrange !== null) {
            $prefixes[] = ['prefixe' => '032', 'id_operateur' => $idOrange];
            $prefixes[] = ['prefixe' => '037', 'id_operateur' => $idOrange];
        }

        if ($idAirtel !== null) {
            $prefixes[] = ['prefixe' => '033', 'id_operateur' => $idAirtel];
        }

        $this->db->table('config')->insertBatch($prefixes);
    }
}
