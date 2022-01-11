@extends('adminlte::page')

@section('title', __('system.reports'))

@section('content_header')
    <cw-header-title>
        <h1> <i class="fas fa-chart-line"></i> {{__('system.reports')}}</h1>
    </cw-header-title>
@stop

@section('content')
    <x-adminlte-card theme="success" theme-mode="outline">
        <h1>Gerar Relatório</h1>
        <p>Insira os campos pra filtragem</p>
        <form action="{{}}" method="post"></form>
        <div class="row">
            <x-adminlte-select2 name="construction" label="{{__('system.construction')}}" fgroup-class="col-md-6">
                <option value=""></option>
                @foreach ($constructions as $construction)
                    <option value="{{$construction->id}}">{{$construction->name}}</option>
                @endforeach
            </x-adminlte-select2>
        </div>
        <div class="row">
            <x-adminlte-select2 name="material" label="{{__('system.material')}}" fgroup-class="col-md-6">
                <option value=""></option>
                @foreach ($materials as $material)
                    <option value="{{$material->id}}">{{$material->name}}</option>
                @endforeach
            </x-adminlte-select2>
        </div>
        <div class="row">
            <x-adminlte-select2 name="invoice_number" label="{{__('system.invoice_number')}}" fgroup-class="col-md-6">
                <option value=""></option>
                @foreach ($invoices as $invoice)
                    <option value="{{$invoice->id}}">{{$invoice->invoice_number}}</option>
                @endforeach
            </x-adminlte-select2>
        </div>
        <x-adminlte-date-range name="dtRange" fgroup-class="col-md-6" label="Período"/>
        <x-adminlte-button label="{{__('system.generate_report')}}" class="bg-success" icon="fas fa-chart-line"/>
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
