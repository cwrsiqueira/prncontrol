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
            $heads = [
                __('system.user_name'),
                __('system.action'),
                __('system.menu'),
                __('system.changes'),
                __('system.date'),
            ];

            $data = [];
            $isAdmin = Auth::user()->permission_group_id === 1;

            if ($isAdmin) {
                foreach ($logs as $log) {
                    $beforeChange = json_decode($log['detail'], true);

                    // Adiciona os valores na mesma ordem dos cabeçalhos
                    $data[] = [
                        'user_name' => $log['user_name'],
                        'action' => $log['action'],
                        'menu' => $log['menu'],
                        'beforeChange' => json_encode($beforeChange, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                        'date_at' => [
                            'display' => date('d/m/Y - H:i:s', strtotime($log['updated_at'] ?? $log['created_at'])),
                            'timestamp' => strtotime($log['updated_at'] ?? $log['created_at']),
                        ],
                    ];
                }
            }

            // Configuração do DataTable
            $config = [
                'data' => $data,
                'order' => [[4, 'DESC']], // Ordenando pela data
                'columns' => [
                    ['name' => __('system.user_name'), 'data' => 'user_name'],
                    ['name' => __('system.action'), 'data' => 'action'],
                    ['name' => __('system.menu'), 'data' => 'menu'],
                    ['name' => __('system.changes'), 'data' => 'beforeChange'],
                    ['name' => __('system.date'), 'data' => ['_' => 'date_at.display', 'sort' => 'date_at.timestamp']],
                ],
                'lengthMenu' => [10, 50, 100, 500],
            ];
        @endphp

        {{-- Renderiza a tabela DataTable --}}
        <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" with-buttons />
    </x-adminlte-card>
@stop


@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop
