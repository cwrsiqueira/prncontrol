@extends('adminlte::page')

@section('title', __('system.reports'))

@section('content_header')
    <cw-header-title>
        <h1> <i class="fas fa-chart-line"></i> {{__('system.reports')}}</h1>
    </cw-header-title>
@stop

@section('content')
    <x-adminlte-card theme="success" theme-mode="outline">
        Conteúdo
    </x-adminlte-card>
@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
    <style>

    </style>
@stop

@section('js')
    <script>

    </script>
@stop
