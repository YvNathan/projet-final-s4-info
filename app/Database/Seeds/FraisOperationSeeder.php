<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FraisOperationSeeder extends Seeder
{
    public function run()
    {
        $typeOperation = $this->db->table('type_operation')->get()->getResultArray();
        $idsByLibelle  = array_column($typeOperation, 'id', 'libelle');

        // Depot : gratuit quel que soit le montant
        $bareme = [
            [
                'id_type_operation' => $idsByLibelle['depot'],
                'borne_min'         => 0,
                'borne_max'         => 10_000_000,
                'frais'             => 0,
            ],
        ];

        // Retrait : frais par tranche de montant
        $bareme = array_merge($bareme, [
            ['id_type_operation' => $idsByLibelle['retrait'], 'borne_min' => 0, 'borne_max' => 5_000, 'frais' => 200],
            ['id_type_operation' => $idsByLibelle['retrait'], 'borne_min' => 5_000, 'borne_max' => 10_000, 'frais' => 400],
            ['id_type_operation' => $idsByLibelle['retrait'], 'borne_min' => 10_000, 'borne_max' => 25_000, 'frais' => 800],
            ['id_type_operation' => $idsByLibelle['retrait'], 'borne_min' => 25_000, 'borne_max' => 50_000, 'frais' => 1_500],
            ['id_type_operation' => $idsByLibelle['retrait'], 'borne_min' => 50_000, 'borne_max' => 100_000, 'frais' => 2_500],
            ['id_type_operation' => $idsByLibelle['retrait'], 'borne_min' => 100_000, 'borne_max' => 250_000, 'frais' => 4_500],
            ['id_type_operation' => $idsByLibelle['retrait'], 'borne_min' => 250_000, 'borne_max' => 500_000, 'frais' => 8_000],
            ['id_type_operation' => $idsByLibelle['retrait'], 'borne_min' => 500_000, 'borne_max' => 1_000_000, 'frais' => 15_000],
            ['id_type_operation' => $idsByLibelle['retrait'], 'borne_min' => 1_000_000, 'borne_max' => 10_000_000, 'frais' => 25_000],
        ]);

        // Transfert : frais par tranche de montant, moins cher que le retrait
        $bareme = array_merge($bareme, [
            ['id_type_operation' => $idsByLibelle['transfert'], 'borne_min' => 0, 'borne_max' => 5_000, 'frais' => 100],
            ['id_type_operation' => $idsByLibelle['transfert'], 'borne_min' => 5_000, 'borne_max' => 10_000, 'frais' => 200],
            ['id_type_operation' => $idsByLibelle['transfert'], 'borne_min' => 10_000, 'borne_max' => 25_000, 'frais' => 400],
            ['id_type_operation' => $idsByLibelle['transfert'], 'borne_min' => 25_000, 'borne_max' => 50_000, 'frais' => 700],
            ['id_type_operation' => $idsByLibelle['transfert'], 'borne_min' => 50_000, 'borne_max' => 100_000, 'frais' => 1_200],
            ['id_type_operation' => $idsByLibelle['transfert'], 'borne_min' => 100_000, 'borne_max' => 250_000, 'frais' => 2_000],
            ['id_type_operation' => $idsByLibelle['transfert'], 'borne_min' => 250_000, 'borne_max' => 500_000, 'frais' => 3_500],
            ['id_type_operation' => $idsByLibelle['transfert'], 'borne_min' => 500_000, 'borne_max' => 1_000_000, 'frais' => 6_000],
            ['id_type_operation' => $idsByLibelle['transfert'], 'borne_min' => 1_000_000, 'borne_max' => 10_000_000, 'frais' => 10_000],
        ]);

        $this->db->table('frais_operation')->insertBatch($bareme);
    }
}
