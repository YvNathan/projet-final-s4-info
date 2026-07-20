<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TransactionSeeder extends Seeder
{
    private array $fraisParType = [];

    public function run()
    {
        $types = $this->db->table('type_operation')->get()->getResultArray();
        $idsByLibelle = array_column($types, 'id', 'libelle');

        $clients = $this->db->table('client')->get()->getResultArray();
        $clientsByNumero = array_column($clients, null, 'numero');

        foreach ($idsByLibelle as $libelle => $id) {
            $this->fraisParType[$id] = $this->db->table('frais_operation')
                ->where('id_type_operation', $id)
                ->orderBy('borne_min', 'ASC')
                ->get()
                ->getResultArray();
        }

        $transactions = [
            // Rakoto Jean (034) : depots, un retrait, un transfert vers Rasoa
            ['numero' => '0341234567', 'type' => 'depot',     'montant' => 100_000, 'date' => '2026-07-01 08:15:00'],
            ['numero' => '0341234567', 'type' => 'depot',     'montant' => 50_000,  'date' => '2026-07-05 10:30:00'],
            ['numero' => '0341234567', 'type' => 'retrait',   'montant' => 20_000,  'date' => '2026-07-10 14:00:00'],
            ['numero' => '0341234567', 'type' => 'transfert', 'montant' => 15_000,  'date' => '2026-07-12 09:45:00', 'destinataire' => '0342345678'],

            // Rasoa Marie (034)
            ['numero' => '0342345678', 'type' => 'depot',   'montant' => 30_000, 'date' => '2026-07-06 11:00:00'],
            ['numero' => '0342345678', 'type' => 'retrait', 'montant' => 10_000, 'date' => '2026-07-15 16:20:00'],

            // Andry Tojo (034) : compte a 0, historique de depenses jusqu'a epuisement
            ['numero' => '0343456789', 'type' => 'depot',   'montant' => 20_000, 'date' => '2026-06-20 09:00:00'],
            ['numero' => '0343456789', 'type' => 'retrait', 'montant' => 19_200, 'date' => '2026-06-28 17:30:00'],

            // Hery Miangaly (038)
            ['numero' => '0381234567', 'type' => 'depot',     'montant' => 400_000, 'date' => '2026-07-02 08:00:00'],
            ['numero' => '0381234567', 'type' => 'retrait',   'montant' => 50_000,  'date' => '2026-07-08 13:10:00'],
            ['numero' => '0381234567', 'type' => 'transfert', 'montant' => 25_000,  'date' => '2026-07-14 19:00:00', 'destinataire' => '0382345678'],

            // Fanja Voahangy (038)
            ['numero' => '0382345678', 'type' => 'depot', 'montant' => 5_000, 'date' => '2026-07-14 19:05:00'],

            // Tovo Rakotomalala (038)
            ['numero' => '0383456789', 'type' => 'depot',     'montant' => 100_000, 'date' => '2026-07-03 07:45:00'],
            ['numero' => '0383456789', 'type' => 'transfert', 'montant' => 10_000,  'date' => '2026-07-09 12:00:00', 'destinataire' => '0344567890'],
            ['numero' => '0383456789', 'type' => 'retrait',   'montant' => 3_000,   'date' => '2026-07-18 18:40:00'],

            // Nirina Andriamampianina (034)
            ['numero' => '0344567890', 'type' => 'depot',   'montant' => 500_000, 'date' => '2026-06-25 10:00:00'],
            ['numero' => '0344567890', 'type' => 'retrait', 'montant' => 10_000,  'date' => '2026-07-09 12:01:00'],

            // Lova Rabemananjara (034)
            ['numero' => '0345678901', 'type' => 'depot',     'montant' => 80_000, 'date' => '2026-07-04 09:20:00'],
            ['numero' => '0345678901', 'type' => 'retrait',   'montant' => 15_000, 'date' => '2026-07-11 15:00:00'],

            // Sitraka Randria (038)
            ['numero' => '0384567890', 'type' => 'depot',     'montant' => 250_000, 'date' => '2026-07-07 08:30:00'],
            ['numero' => '0384567890', 'type' => 'transfert', 'montant' => 40_000,  'date' => '2026-07-16 20:10:00', 'destinataire' => '0385678901'],

            // Malala Ravaka (038)
            ['numero' => '0385678901', 'type' => 'depot', 'montant' => 5_000, 'date' => '2026-07-16 20:11:00'],
            ['numero' => '0385678901', 'type' => 'retrait', 'montant' => 3_600, 'date' => '2026-07-17 09:00:00'],

            // Fenosoa Andriatsara (034)
            ['numero' => '0346789012', 'type' => 'depot',   'montant' => 100_000, 'date' => '2026-07-13 11:11:00'],
            ['numero' => '0346789012', 'type' => 'retrait', 'montant' => 24_000,  'date' => '2026-07-19 17:00:00'],
        ];

        $rows   = [];
        $soldes = array_column($clients, 'solde', 'numero');

        foreach ($transactions as $t) {
            $client = $clientsByNumero[$t['numero']] ?? null;

            if ($client === null) {
                continue;
            }

            $idType = $idsByLibelle[$t['type']];
            $frais  = $this->calculerFrais($idType, (float) $t['montant']);

            $soldes[$t['numero']] += $t['type'] === 'depot'
                ? $t['montant']
                : -($t['montant'] + $frais);

            $rows[] = [
                'id_client'           => $client['id'],
                'id_type_operation'   => $idType,
                'date_heure'          => $t['date'],
                'montant'             => $t['montant'],
                'frais_applique'      => $frais,
                'numero_destinataire' => $t['destinataire'] ?? null,
            ];
        }

        $this->db->table('transactions')->insertBatch($rows);

        foreach ($soldes as $numero => $solde) {
            $this->db->table('client')
                ->where('numero', $numero)
                ->update(['solde' => $solde]);
        }
    }

    private function calculerFrais(int $idTypeOperation, float $montant): float
    {
        foreach ($this->fraisParType[$idTypeOperation] as $tranche) {
            if ($montant > (float) $tranche['borne_min'] && $montant <= (float) $tranche['borne_max']) {
                return (float) $tranche['frais'];
            }
        }

        return 0.0;
    }
}
