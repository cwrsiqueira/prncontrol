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

            $table->string('logradouro_nome')->nullable();
            $table->string('logradouro_número')->nullable();
            $table->text('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->string('cep')->nullable();

            $table->string('area_m2')->nullable();
            $table->string('formato')->comment('retangular, quadrado, irregular, outros')->nullable();
            $table->string('aprovacao_orgao')->nullable();
            $table->string('aprovacao_documento')->nullable();
            $table->string('aprovacao_numero')->nullable();
            $table->date('aprovacao_data')->nullable();
            $table->string('registro_cartorio_nome')->nullable();
            $table->string('registro_cartorio_numero')->nullable();
            $table->text('confrontacoes')->comment('lados, confrontacoes e medidas')->nullable();
            $table->string('valor')->nullable();

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
