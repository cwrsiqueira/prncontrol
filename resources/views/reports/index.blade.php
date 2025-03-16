@extends('adminlte::page')

@section('title', 'PRNCONTROL | ' . __('system.reports'))

@section('content_header')
    <cw-header-title>
        <h1> <i class="fas fa-chart-line"></i> {{ __('system.reports') }}</h1>
    </cw-header-title>
@stop

@section('content')
    <x-adminlte-card theme="success" theme-mode="outline">
        <h1>Gerar Relatório</h1>
        <p>Insira os campos pra filtragem</p>

        <form action="{{ route('reports.generate') }}" method="post" target="_blank">
            @csrf
            <input type="checkbox" name="group_by_material" value="1"> Agrupar por Material

            <div class="row">
                <x-adminlte-select2 name="construction_id" label="{{ __('system.construction') }}" fgroup-class="col-md-6">
                    <option value="">Todas</option>
                    @foreach ($constructions as $construction)
                        <option value="{{ $construction->id }}">{{ $construction->name }}</option>
                    @endforeach
                </x-adminlte-select2>
            </div>
            {{-- <div class="row">
                <x-adminlte-select2 name="provider_id" label="{{__('system.provider')}}" fgroup-class="col-md-6">
                    <option value=""></option>
                    @foreach ($providers as $provider)
                        <option value="{{$provider->id}}">{{$provider->name}}</option>
                    @endforeach
                </x-adminlte-select2>
            </div> --}}
            <div class="row">
                <x-adminlte-select2 name="material_id" label="{{ __('system.material') }}" fgroup-class="col-md-6">
                    <option value="">Todos</option>
                    @foreach ($materials as $material)
                        <option value="{{ $material->id }}">{{ $material->name }}</option>
                    @endforeach
                </x-adminlte-select2>
            </div>
            <div class="row">
                <x-adminlte-select2 name="category_id" label="{{ __('system.category') }}" fgroup-class="col-md-6">
                    <option value="">Todos</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </x-adminlte-select2>
            </div>
            {{-- <div class="row">
                <x-adminlte-select2 name="invoice_id" label="{{__('system.invoice_number')}}" fgroup-class="col-md-6">
                    <option value=""></option>
                    @foreach ($invoices as $invoice)
                        <option value="{{$invoice->id}}">{{$invoice->invoice_number}}</option>
                    @endforeach
                </x-adminlte-select2>
            </div> --}}

            @php
                $config = [
                    'showDropdowns' => true,
                    'startDate' => date('d-m-Y', strtotime($first_invoice->invoice_date ?? '01-01-0001')),
                    'endDate' => date('d-m-Y', strtotime($last_invoice->invoice_date ?? '01-01-0001')),
                    // "minYear" => 2000,
                    // "maxYear" => "js:parseInt(moment().format('YYYY'),10)",
                    // "timePicker" => true,
                    // "timePicker24Hour" => true,
                    // "timePickerIncrement" => 30,
                    'locale' => ['format' => 'DD-MM-YYYY'],
                    // "locale" => ["format" => "YYYY-MM-DD HH:mm"],
                    'opens' => 'center',
                ];
            @endphp
            <x-adminlte-date-range name="dtRange" fgroup-class="col-md-6" label="Período" :config="$config" />

            <x-adminlte-button type="submit" label="{{ __('system.generate_report') }}" class="bg-success"
                icon="fas fa-chart-line" />
        </form>

    </x-adminlte-card>
@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
    <style>

    </style>
@stop

@section('js')
    <script></script>
@stop
