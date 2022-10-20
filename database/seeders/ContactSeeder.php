<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contacts')->insert([
            'company_id' => 1,
            'client_id' => 1,
            'descricao_contato' => 'Telefone',
            'dados_contato' => '96991100451',
            'preferencial' => 0
        ]);

        DB::table('contacts')->insert([
            'company_id' => 1,
            'client_id' => 1,
            'descricao_contato' => 'Email',
            'dados_contato' => 'cwrsiqueira@hotmail.com',
            'preferencial' => 1
        ]);
    }
}