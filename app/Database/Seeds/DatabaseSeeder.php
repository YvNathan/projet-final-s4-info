<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(TypeOperationSeeder::class);
        $this->call(ConfigSeeder::class);
        $this->call(FraisOperationSeeder::class);
        $this->call(ClientSeeder::class);
        // $this->call(TransactionSeeder::class);
    }
}
