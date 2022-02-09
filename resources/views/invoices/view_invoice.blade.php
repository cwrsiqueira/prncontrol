@extends('adminlte::page')

@section('title', __('system.invoices'))

@section('content_header')

<pre>
    ID: {{$invoice->id}}
    NOTA NR.: {{$invoice->invoice_number}}
    DATA DA NOTA: {{date('d/m/Y', strtotime($invoice->invoice_date))}}

    CONSTRUÇÃO: {{$invoice->construction_name}}
    FORNECEDOR: {{$invoice->provider_name}}

    VALOR DA NOTA: {{number_format($total_invoice_value, 2, ',', '.')}}
</pre>


<table class="table table-hover">
    <thead>
        <tr>
            <th>Material</th>
            <th>Unid</th>
            <th>Quant.</th>
            <th>Vlr Unit.</th>
            <th>Vlr Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($invoice_materials as $item)
            <tr>
                <td>{{$item->material_name}}</td>
                <td>{{$item->unid}}</td>
                <td>{{number_format($item->qt, 2, ',', '.')}}</td>
                <td>{{number_format($item->unit_value, 2, ',', '.')}}</td>
                <td>{{number_format($item->qt * $item->unit_value, 2, ',', '.')}}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@stop


