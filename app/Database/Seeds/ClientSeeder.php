<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run()
    {
        $clients = [
            ['nom' => 'Rakoto Jean',                 'numero' => '0341234567', 'solde' => 0],
            ['nom' => 'Rasoa Marie',                 'numero' => '0342345678', 'solde' => 0],
            ['nom' => 'Andry Tojo',                  'numero' => '0343456789', 'solde' => 0],
            ['nom' => 'Hery Miangaly',                'numero' => '0381234567', 'solde' => 0],
            ['nom' => 'Fanja Voahangy',               'numero' => '0382345678', 'solde' => 0],
            ['nom' => 'Tovo Rakotomalala',            'numero' => '0383456789', 'solde' => 0],
            ['nom' => 'Nirina Andriamampianina',      'numero' => '0344567890', 'solde' => 0],
            ['nom' => 'Lova Rabemananjara',           'numero' => '0345678901', 'solde' => 0],
            ['nom' => 'Sitraka Randria',               'numero' => '0384567890', 'solde' => 0],
            ['nom' => 'Malala Ravaka',                 'numero' => '0385678901', 'solde' => 0],
            ['nom' => 'Fenosoa Andriatsara',           'numero' => '0346789012', 'solde' => 0],
        ];

        $this->db->table('client')->insertBatch($clients);
    }
}
