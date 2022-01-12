@extends('adminlte::page')

@section('title', __('system.reports'))

@section('content_header')
    <cw-header-title>
        <h1> <i class="fas fa-chart-line"></i> {{__('system.reports')}}</h1>
        <a href="{{route('reports.index')}}"><x-adminlte-button label="{{__('system.back')}}" class="bg-info" icon="fas fa-angle-double-left"/></a>
    </cw-header-title>
@stop

@section('content')

    {{-- Setup data for datatables --}}
    @php
    $system_edit = __('system.edit');
    $system_delete = __('system.delete');
    $system_details = __('system.details');
    $heads = [
        __('system.invoice_date'),
        __('system.invoice_number'),
        __('system.provider'),
        __('system.construction'),
        __('system.material'),
        __('system.unid'),
        __('system.qt'),
        __('system.unit_val'),
        __('system.total_val'),
    ];
    $data = [];
    foreach ($invoices as $key => $invoice) {
        $data[$key]['invoice_date'] = date('d/m/Y', strtotime($invoice['invoice_date']));
        $data[$key]['invoice_number'] = $invoice['invoice_number'];
        $data[$key]['provider'] = $invoice['provider_name'];
        $data[$key]['construction'] = $invoice['construction_name'];
        $data[$key]['material'] = $invoice['material_name'];
        $data[$key]['unid'] = $invoice['material_unid'];
        $data[$key]['qt'] = $invoice['material_qt'];
        $data[$key]['unit_val'] = $invoice['material_unit_value'];
        $data[$key]['total_val'] = $invoice['material_qt'] * $invoice['material_unit_value'];
    }

    $config = [
        'data' => $data,
        'order' => [[1, 'asc']],
        'columns' => [null, null, null, null, null, null, null, null, null],
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
