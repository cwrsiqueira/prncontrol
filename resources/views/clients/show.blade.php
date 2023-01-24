@extends('adminlte::page')

@section('title', 'PRNCONTROL | ' . __('system.show_client'))

@section('content_header')
    <cw-header-title>
        <h1><i class="fas fa-user"></i> {{ __('system.show_client') }}</h1>
        <div>
            <a href="{{ route('clients.index') }}">{{ __('system.clients') }}</a> | {{ __('system.show_client') }}
        </div>
    </cw-header-title>
@stop

@section('content')
    <x-adminlte-card theme="success" theme-mode="outline">
        <h4>Ficha Cadastral do Cliente</h4>
        <pre>
ID: {{ $client->id }}
NOME: {{ $client->nome_razao_social }}
PESSOA: {{ $client->pessoa }}
<hr>
@if ($client['pessoa'] == 'fisica')
CPF:{{ $client->cpf }}
Nacionalidade:{{ $client->nacionalidade }}
Estado Civil:{{ $client->estado_civil }}
Profissão:{{ $client->profissao }}
Tipo de Documento:{{ $client->documento_tipo }}
Número do Documento:{{ $client->documento_numero }}
Órgão Emissor / UF:{{ $client->documento_orgao_emissor }}
@elseif($client['pessoa'] == 'juridica')
CNPJ:{{ $client->cnpj }}
Nome de Fantasia:{{ $client->nome_fantasia }}
Natureza Jurídica:{{ $client->natureza_juridica }}
Inscrição Estadual:{{ $client->inscricao_estadual }}
Sócios:{{ $client->socios_ids }}
@else
Pessoa não definida!
@endif
<hr>
<h6>Informações de Contatos:</h6>
@php $n = 1; @endphp
@foreach ($client['contacts'] as $contact)
Contato {{ $n }} *****
Descrição do Contato:{{ $contact->descricao_contato }}
Dados do Contato:{{ $contact->dados_contato }}
@php $n++; @endphp
@endforeach
<hr>
<h6>Informações de Endereço:</h6>
CEP:{{ $client->addresses[0]->cep }}
Tipo de Logradouro:{{ $client->addresses[0]->logradouro_tipo }}
Nome do Logradouro:{{ $client->addresses[0]->logradouro_nome }}
Número:{{ $client->addresses[0]->numero }}
Complemento:{{ $client->addresses[0]->complemento }}
Bairro:{{ $client->addresses[0]->bairro }}
Município:{{ $client->addresses[0]->municipio }}
Estado:{{ $client->addresses[0]->estado }}
<hr>
<h6>Observações:</h6>
{{ $client->obs }}
<hr>
<h6>Imóveis Vinculados:</h6>
<table class="table table-hover">
<thead>
<tr>
<th class="number-col">#</th>
<th class="text-col">Documento</th>
<th class="text-col">Abrir</th>
</tr>
</thead>
<tbody>
<tr>
<td class="number-col">#</td>
<td class="text-col">Documento</td>
<td class="text-col">Abrir</td>
</tr>
</tbody>
</table>
<hr>
<h6>Documentos Vinculados:</h6>
<hr>
<h6>Conta Corrente:</h6>
        </pre>
    </x-adminlte-card>
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endsection
