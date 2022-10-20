<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained();
            $table->string('nome_razao_social')->nullable();
            $table->string('pessoa')->comment('juridica, fisica');

            // Caso Pessoa Física
            $table->string('nacionalidade')->nullable();
            $table->string('estado_civil')->nullable();
            $table->string('profissao')->nullable();
            $table->string('documento_tipo')->comment('identidade, carteira profissional, carteira de trabalho, CNH, passaporte etc.')->nullable();
            $table->string('documento_numero')->nullable();
            $table->string('documento_orgao_emissor')->nullable();
            $table->string('cpf')->nullable();
            // Caso Pessoa Jurídica
            $table->string('nome_fantasia')->nullable();
            $table->string('natureza_juridica')->comment('sociedade anonima, sociedade limitada, eirele etc.')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('inscricao_estadual')->nullable();
            $table->string('socios_ids')->nullable();

            $table->text('obs')->nullable();
            $table->integer('inactive')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}