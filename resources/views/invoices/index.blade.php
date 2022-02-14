@extends('adminlte::page')

@section('title', __('system.invoices'))

@section('content_header')
    <cw-header-title>

        <h1><i class="fas fa-file-invoice-dollar"></i> {{__('system.invoices')}}</h1>
        <a href="{{route('invoices.create')}}">
            <x-adminlte-button label="{{__('system.add_invoice')}}" class="bg-success" icon="fas fa-plus"/>
        </a>
        {{-- IT OPENS SUCCESS MODAL --}}
        @if(session('success'))
            <x-adminlte-modal id="modalMessages" title="{{__('system.success')}}!" size="lg" theme="success" icon="fas fa-thumbs-up" v-centered static-backdrop scrollable>

                    {!! session('success') !!}

                    <x-slot name="footerSlot">
                        <x-adminlte-button theme="success" label="{{__('system.close')}}" data-dismiss="modal" data-toggle="modal"/>
                    </x-slot>
            </x-adminlte-modal>

            <x-adminlte-button label="Open Modal" data-toggle="modal" data-target="#modalMessages" id="openModalMessages" style="display:none;"/>
        @endif
        <input type="hidden" id="messages" value="{{ session('success') }}">

    </cw-header-title>
@stop

@section('content')
    <x-adminlte-card theme="success" theme-mode="outline">
        {{-- Setup data for datatables --}}
        @php
        $system_edit = __('system.edit');
        $system_delete = __('system.delete');
        $system_details = __('system.details');
        $heads = [
            'ID',
            __('system.invoice_date'),
            __('system.invoice_number'),
            __('system.provider'),
            __('system.construction'),
            ['label' => __('system.actions'), 'no-export' => true, 'width' => 5],
        ];
        $data = [];
        foreach ($invoices as $key => $invoice) {
            $data[$key]['invoice_id'] = $invoice['id'];
            $data[$key]['invoice_date'] = date('d/m/Y', strtotime($invoice['invoice_date']));
            $data[$key]['invoice_number'] = $invoice['invoice_number'];
            $data[$key]['provider'] = $invoice['provider_name'];
            $data[$key]['construction'] = $invoice['construction_name'];
            $data[$key]['actions'] =
            "<nobr>
                <button class='btn btn-xs btn-default text-primary mx-1 shadow' onclick='edit_invoice(this)' title='".$system_edit."' data-id='".$invoice["id"]."'>
                    <i class='fa fa-lg fa-fw fa-pen'></i>
                </button>
                <button class='btn btn-xs btn-default text-teal mx-1 shadow' onclick='view_invoice(this)' title='".$system_details."' data-id='".$invoice["id"]."'>
                    <i class='fa fa-lg fa-fw fa-eye'></i>
                </button>
                <button class='btn btn-xs btn-default text-danger mx-1 shadow' onclick='delete_invoice(this)' title='".$system_delete."' data-id='".$invoice["id"]."'>
                    <i class='fa fa-lg fa-fw fa-trash'></i>
                </button>
            </nobr>";
        }

        $config = [
            'data' => $data,
            'order' => [[1, 'DESC']],
            'columns' => [null, null, null, null, null, ['orderable' => false]],
        ];
        @endphp
        {{-- Minimal example / fill data using the component slot --}}
        <x-adminlte-datatable id="table1" :heads="$heads" with-buttons hoverable>
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

@section('js')
    <script>

        let messages = document.querySelector('#messages').value
        if(messages !== '') {
            document.querySelector('#openModalMessages').click()
        }

        const edit_invoice = (el) => {
            let id = el.getAttribute('data-id')
            let route = '{{route("invoices.edit", ["invoice" => "invoice_id"])}}'
            window.location.href = route.replace('invoice_id', id)
        }

        const view_invoice = (el) => {
            let id = el.getAttribute('data-id')
            let route = '{{route("invoices.show", ["invoice" => "invoice_id"])}}'
            window.location.href = route.replace('invoice_id', id)
        }

        const delete_invoice = (el) => {
            if(!confirm('Confirma a exclusão da nota?')) {
                return false;
            }
            let id = el.getAttribute('data-id')

            var ajax = new XMLHttpRequest();
            ajax.open("GET", "{{route('delInvoice')}}/?id="+id, true);
            ajax.send();
            ajax.onreadystatechange = function() {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    alert(ajax.responseText)
                    location.reload()
                }
            }
        }

    </script>
@stop
