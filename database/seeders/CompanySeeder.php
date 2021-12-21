<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            'name' => 'Hostler Dev & Host',
            'email' => 'admin@hostler.com.br',
            'website' => 'hostler.com.br',
            'logo' => 'default.jpg',
        ]);
    }
}
