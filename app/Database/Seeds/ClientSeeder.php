<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run()
    {
        $operateurs   = $this->db->table('operateur')->get()->getResultArray();
        $idsByOperateur = array_column($operateurs, 'id', 'nom');

        $clients = [
            // Airtel Madagascar (033)
            ['id_operateur' => $idsByOperateur['Airtel Madagascar'], 'nom' => 'Rakoto Jean',        'numero' => '0331234567', 'solde' => 0],
            ['id_operateur' => $idsByOperateur['Airtel Madagascar'], 'nom' => 'Rasoa Marie',        'numero' => '0332345678', 'solde' => 0],
            ['id_operateur' => $idsByOperateur['Airtel Madagascar'], 'nom' => 'Andry Tojo',          'numero' => '0333456789', 'solde' => 0],

            // Yas (034 et 038)
            ['id_operateur' => $idsByOperateur['Yas'], 'nom' => 'Hery Miangaly', 'numero' => '0341234567', 'solde' => 0],
            ['id_operateur' => $idsByOperateur['Yas'], 'nom' => 'Fanja Voahangy', 'numero' => '0342345678', 'solde' => 0],
            ['id_operateur' => $idsByOperateur['Yas'], 'nom' => 'Tovo Rakotomalala', 'numero' => '0381234567', 'solde' => 0],
            ['id_operateur' => $idsByOperateur['Yas'], 'nom' => 'Nirina Andriamampianina', 'numero' => '0382345678', 'solde' => 0],

            // Orange (032 et 037)
            ['id_operateur' => $idsByOperateur['Orange'], 'nom' => 'Lova Rabemananjara', 'numero' => '0321234567', 'solde' => 0],
            ['id_operateur' => $idsByOperateur['Orange'], 'nom' => 'Sitraka Randria', 'numero' => '0322345678', 'solde' => 0],
            ['id_operateur' => $idsByOperateur['Orange'], 'nom' => 'Malala Ravaka', 'numero' => '0371234567', 'solde' => 0],
            ['id_operateur' => $idsByOperateur['Orange'], 'nom' => 'Fenosoa Andriatsara', 'numero' => '0372345678', 'solde' => 0],
        ];

        $this->db->table('client')->insertBatch($clients);
    }
}
