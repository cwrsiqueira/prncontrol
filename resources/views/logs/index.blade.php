@extends('adminlte::page')

@section('title', 'PRNCONTROL | ' . __('system.logs'))

@section('content_header')
    <cw-header-title>
        <h1><i class="fas fa-lg fa-report"></i> {{ __('system.logs') }}</h1>
    </cw-header-title>
@stop

@section('content')
    <x-adminlte-card theme="success" theme-mode="outline">
        @php
            // Cabeçalhos da tabela
            $heads = [__('system.user_name'), __('system.action'), __('system.menu'), __('system.changes'), __('system.date')];

            $data = [];
            $isAdmin = Auth::user()->permission_group_id === 1;

            if ($isAdmin) {
                foreach ($logs as $log) {
                    $beforeChange = json_decode($log['detail'], true);
                    
                    // Adiciona os valores na mesma ordem dos cabeçalhos
                    $data[] = [
                        $log['user_name'],
                        $log['action'],
                        $log['menu'],
                        "<pre>" . json_encode($beforeChange, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>",
                        date('d/m/Y - H:i:s', strtotime($log['updated_at'] ?? $log['created_at'])),
                    ];
                }
            }

            // Configuração do DataTable
            $config = [
                'data' => $data,
                'order' => [[4, 'DESC']], // Ordenando pela data
                'columns' => [
                    ['title' => __('system.user_name')],
                    ['title' => __('system.action')],
                    ['title' => __('system.menu')],
                    ['title' => __('system.changes')],
                    ['title' => __('system.date')],
                ],
                'lengthMenu' => [10, 50, 100, 500]
            ];
        @endphp

        {{-- Renderiza a tabela DataTable --}}
        <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" with-buttons />
    </x-adminlte-card>
@stop


@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop
