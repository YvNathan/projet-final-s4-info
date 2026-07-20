<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run()
    {
        // Le solde de depart est 0 : le solde final de chaque client est reconstitue
        // par TransactionSeeder a partir de l'historique simule des operations.
        $clients = [
            // Prefixe 033
            ['nom' => 'Rakoto Jean',        'numero' => '0331234567', 'solde' => 0],
            ['nom' => 'Rasoa Marie',        'numero' => '0332345678', 'solde' => 0],
            ['nom' => 'Andry Tojo',          'numero' => '0333456789', 'solde' => 0],

            // Prefixes 034 et 038
            ['nom' => 'Hery Miangaly', 'numero' => '0341234567', 'solde' => 0],
            ['nom' => 'Fanja Voahangy', 'numero' => '0342345678', 'solde' => 0],
            ['nom' => 'Tovo Rakotomalala', 'numero' => '0381234567', 'solde' => 0],
            ['nom' => 'Nirina Andriamampianina', 'numero' => '0382345678', 'solde' => 0],

            // Prefixes 032 et 037
            ['nom' => 'Lova Rabemananjara', 'numero' => '0321234567', 'solde' => 0],
            ['nom' => 'Sitraka Randria', 'numero' => '0322345678', 'solde' => 0],
            ['nom' => 'Malala Ravaka', 'numero' => '0371234567', 'solde' => 0],
            ['nom' => 'Fenosoa Andriatsara', 'numero' => '0372345678', 'solde' => 0],
        ];

        $this->db->table('client')->insertBatch($clients);
    }
}
