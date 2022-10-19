<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('addresses')->insert([
            'company_id' => 1,
            'client_id' => 1,
            'logradouro_tipo' => 'Avenida',
            'logradouro_nome' => 'Das Flores',
            'numero' => '123',
            'complemento' => 'casa',
            'bairro' => 'Residencial',
            'municipio' => 'Curitiba',
            'estado' => 'Paraná',
            'cep' => '80420160'
        ]);

        DB::table('addresses')->insert([
            'company_id' => 1,
            'client_id' => 1,
            'logradouro_tipo' => 'Rua',
            'logradouro_nome' => 'Das Oliveiras',
            'numero' => '987',
            'complemento' => 'Ed. Central - 4º andar - Sala 402',
            'bairro' => 'Industrial',
            'municipio' => 'Curitiba',
            'estado' => 'Paraná',
            'cep' => '80420160'
        ]);
    }
}