<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained();

            $table->string('loteamento')->comment('Loteamento Conjunto Agrovilla Hortifrutigranjeira, Loteamento Conjunto Fazendinha Alfaville');
            $table->string('quadra');
            $table->string('lote');

            $table->string('logradouro_nome');
            $table->string('logradouro_número')->nullable();
            $table->string('bairro');
            $table->string('cidade');
            $table->string('estado');
            $table->string('cep');

            $table->string('area_m2');
            $table->string('formato')->comment('retangular, quadrado, irregular, outros');
            $table->string('aprovacao_orgao');
            $table->string('aprovacao_documento');
            $table->string('aprovacao_numero');
            $table->date('aprovacao_data');
            $table->string('registro_cartorio_nome');
            $table->string('registro_cartorio_numero');
            $table->text('confrontacoes')->comment('lado e confrontacao e medida');
            $table->string('valor');

            $table->text('obs')->nullable();
            $table->integer('inactive')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lots');
    }
}