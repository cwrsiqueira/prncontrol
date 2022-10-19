<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clients')->insert([
            'company_id' => 1,
            'nome_razao_social' => 'Carlos Wagner R. Siqueira',
            'pessoa' => 'fisica',
            'nacionalidade' => 'brasileira',
            'estado_civil' => 'casado',
            'profissao' => 'vendedor',
            'documento_tipo' => 'RG',
            'documento_numero' => '123456',
            'documento_orgao_emissor' => 'SSP/SP',
            'cpf' => '12345678909'

        ]);
    }
}