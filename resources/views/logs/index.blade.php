@extends('adminlte::page')

@section('title', __('system.logs'))

@section('content_header')
    <cw-header-title>
        <h1><i class="fas fa-lg fa-report"></i> {{ __('system.logs') }}</h1>
    </cw-header-title>
@stop

@section('content')
    <x-adminlte-card theme="success" theme-mode="outline">
        {{-- Setup data for datatables --}}
        @php
            $heads = [__('system.user_name'), __('system.action'), __('system.menu'), __('system.beforeChange'), __('system.afterChange'), __('system.updated_at')];
            $data = [];
            foreach ($logs as $key => $log) {
                if (Auth::user()->permission_group_id === 1) {
                    $data[$key]['user_name'] = $log['user_name'];
                    $data[$key]['action'] = $log['action'];
                    $data[$key]['menu'] = $log['menu'];
                    $data[$key]['beforeChange'] = $log['beforeChange'];
                    $data[$key]['afterChange'] = $log['afterChange'];
                    $data[$key]['updated_at'] = $log['updated_at'];
                }
            }

            $config = [
                'data' => $data,
                'order' => [[1, 'asc']],
                'columns' => [null, null, null, null, null, null],
            ];
        @endphp
        {{-- Minimal example / fill data using the component slot --}}
        <x-adminlte-datatable id="table1" :heads="$heads" with-buttons>
            @foreach ($config['data'] as $row)
                <tr>
                    @foreach ($row as $cell)
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
