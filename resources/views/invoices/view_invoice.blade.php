@extends('adminlte::page')

@section('title', 'PRNCONTROL | ' . __('system.invoices'))

@section('content_header')

    <pre>
        ID: {{ $invoice->id }}
        NOTA NR.: {{ $invoice->invoice_number }}
        DATA DA NOTA: {{ date('d/m/Y', strtotime($invoice->invoice_date)) }}

        CONSTRUÇÃO: {{ $invoice->construction_name }}
        FORNECEDOR: {{ $invoice->provider_name }}

        VALOR DA NOTA: {{ number_format($total_invoice_value, 2, ',', '.') }}
    </pre>
    <a href="#" onclick="window.history.back()" class="btn btn-warning mb-2">Voltar</a>


    <table class="table table-hover view-invoice">
        <thead>
            <tr>
                <th class="text-col">Material</th>
                <th class="number-col">Unid</th>
                <th class="number-col">Quant.</th>
                <th class="number-col">Vlr Unit.</th>
                <th class="number-col">Vlr Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice_materials as $item)
                <tr>
                    <td class="text-col">{{ $item->material_name }}</td>
                    <td class="number-col">{{ $item->unid }}</td>
                    <td class="number-col">{{ number_format($item->qt, 2, ',', '.') }}</td>
                    <td class="number-col">{{ number_format($item->unit_value, 4, ',', '.') }}</td>
                    <td class="number-col">{{ number_format($item->qt * $item->unit_value, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endsection
