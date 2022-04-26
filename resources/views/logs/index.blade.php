@extends('adminlte::page')

@section('title', __('system.logs'))

@section('content_header')
    <cw-header-title>
        <h1><i class="fas fa-lg fa-report"></i> {{__('system.logs')}}</h1>
    </cw-header-title>
@stop

@section('content')
    <x-adminlte-card theme="success" theme-mode="outline">
        {{-- Setup data for datatables --}}
        @php
        $heads = [
            __('system.user_id'),
            __('system.action'),
            __('system.detail'),
            __('system.updated_at'),
        ];
        $data = [];
        foreach ($logs as $key => $log) {
            if (Auth::user()->permission_group_id === 1) {
                $data[$key]['user_id'] = $log['user_id'];
                $data[$key]['action'] = $log['action'];
                $data[$key]['detail'] = $log['detail'];
                $data[$key]['updated_at'] = $log['updated_at'];
            }
        }

        $config = [
            'data' => $data,
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, null],
        ];
        @endphp
        {{-- Minimal example / fill data using the component slot --}}
        <x-adminlte-datatable id="table1" :heads="$heads" with-buttons>
            @foreach($config['data'] as $row)
                <tr>
                    @foreach($row as $cell)
                        <td>{!! $cell !!}</td>
                    @endforeach
                </tr>
            @endforeach
        </x-adminlte-datatable>
    </x-adminlte-card>
@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop
