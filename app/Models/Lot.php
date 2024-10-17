<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        "company_id",
        "loteamento",
        "quadra",
        "lote",
        "cep",
        "logradouro_nome",
        "logradouro_número",
        "complemento",
        "bairro",
        "cidade",
        "estado",
        "area_m2",
        "formato",
        "aprovacao_orgao",
        "aprovacao_documento",
        "aprovacao_numero",
        "aprovacao_data",
        "registro_cartorio_nome",
        "registro_cartorio_numero",
        "confrontacoes",
        "valor",
        "obs",
    ];
}
