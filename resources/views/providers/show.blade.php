@extends('adminlte::page')

@section('title', $provider->name)

@section('content_header')
    <cw-header-title>
        <h1><i class="fas fa-truck"></i> {{ $provider->name }}</h1>

        {{-- IT OPENS SUCCESS MODAL --}}
        @if (session('success'))
            <x-adminlte-modal id="modalMessages" title="{{ __('system.success') }}!" size="lg" theme="success"
                icon="fas fa-thumbs-up" v-centered static-backdrop scrollable>

                {!! session('success') !!}

                <x-slot name="footerSlot">
                    <x-adminlte-button theme="success" label="{{ __('system.close') }}" data-dismiss="modal"
                        data-toggle="modal" />
                </x-slot>
            </x-adminlte-modal>

            <x-adminlte-button label="Open Modal" data-toggle="modal" data-target="#modalMessages" id="openModalMessages"
                style="display:none;" />
        @endif
        <input type="hidden" id="messages" value="{{ session('success') }}">

        {{-- IT OPENS ERRORS FILING FORM FIELDS MODAL --}}
        @if ($errors->any())
            <x-adminlte-modal id="modalErrors" title="{{ __('system.atenction') }}!" size="lg" theme="danger"
                icon="fas fa-ban" v-centered static-backdrop scrollable>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <x-slot name="footerSlot">
                    <x-adminlte-button theme="danger" label="{{ __('system.close') }}" data-dismiss="modal"
                        data-toggle="modal" data-target="#modalAdd" />
                </x-slot>
            </x-adminlte-modal>

            <x-adminlte-button label="Open Modal" data-toggle="modal" data-target="#modalErrors" id="openModalErrors"
                style="display:none;" />

        @endif

        <input type="hidden" id="errors" value="{{ $errors->any() }}">
    </cw-header-title>
@stop

@section('content')
    <x-adminlte-card theme="success" theme-mode="outline">

        <pre>
            ID: {{ $provider->id }}
            NOME: {{ $provider->name }}
        </pre>

        <h4>Notas Lançadas para o fornecedor</h4>

        {{-- Setup data for datatables --}}
        @php
            $system_edit = __('system.edit');
            $system_delete = __('system.delete');
            $system_details = __('system.details');
            $heads = [__('Nota nr.'), __('Data'), __('Obra'), __('Vlr Total'), ['label' => __('system.actions'), 'no-export' => true, 'width' => 5]];
            $data = [];
            foreach ($invoices as $key => $invoice) {
                $data[$key]['invoice_number'] = $invoice->invoice_number;
                $data[$key]['invoice_date'] = date('d/m/Y', strtotime($invoice->invoice_date));
                $data[$key]['construction'] = $invoice['construction_name'];
                $data[$key]['total'] = number_format($invoice['total'], 2, ',', '.');
                $data[$key]['actions'] =
                    "<nobr>
                    <a class='btn btn-xs btn-default text-primary mx-1 shadow btnAction edit' title='Editar'
                                href=" .
                    route('invoices.edit', ['invoice' => $invoice->id]) .
                    "><i
                                    class='fa fa-lg fa-fw fa-pen'></i></a>
                            <a class='btn btn-xs btn-default text-teal mx-1 shadow btnAction details' title='Visualizar'
                                href=" .
                    route('invoices.show', ['invoice' => $invoice->id]) .
                    "><i
                                    class='fa fa-lg fa-fw fa-eye'></i></a>
                            <button class='btn btn-xs btn-default text-danger mx-1 shadow btnAction delete' title='Deletar'
                                data-id=" .
                    $invoice->id .
                    ">
                                <i class='fa fa-lg fa-fw fa-trash'></i>
                            </button>
                        </nobr>";
            }

            $config = [
                'data' => $data,
                'order' => [[1, 'DESC']],
                'columns' => [null, null, null, null, ['orderable' => false]],
            ];
        @endphp
        {{-- Minimal example / fill data using the component slot --}}
        <x-adminlte-datatable id="table1" :heads="$heads" with-buttons hoverable>
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

@section('js')
    <script>
        let invoices = document.querySelectorAll('.delete');
        invoices.forEach(el => {
            el.addEventListener('click', (e) => {
                if (!confirm('Confirma a exclusão da nota?')) {
                    return false;
                }
                let id = el.getAttribute('data-id')

                var ajax = new XMLHttpRequest();
                ajax.open("GET", "{{ route('delInvoice') }}/?id=" + id, true);
                ajax.send();
                ajax.onreadystatechange = function() {
                    if (ajax.readyState == 4 && ajax.status == 200) {
                        alert(ajax.responseText)
                        location.reload()
                    }
                }
            });
        });
    </script>
@stop
