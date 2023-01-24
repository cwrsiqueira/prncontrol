<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        "company_id",
        "pessoa",
        "cpf",
        "nacionalidade",
        "estado_civil",
        "profissao",
        "documento_tipo",
        "documento_numero",
        "documento_orgao_emissor",
        "cnpj",
        "nome_fantasia",
        "natureza_juridica",
        "inscricao_estadual",
        "socios_ids",
        "obs",
        "nome_razao_social",
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}