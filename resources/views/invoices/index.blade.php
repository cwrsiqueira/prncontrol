@extends('adminlte::page')

@section('title', __('system.invoices'))

@section('content_header')
    <cw-header-title>
        <h1><i class="fas fa-file-invoice-dollar"></i> {{__('system.invoices')}}</h1>

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

        {{-- IT OPENS ERRORS FILING FORM FIELDS MODAL --}}
        @if($errors->any())
            <x-adminlte-modal id="modalErrors" title="{{__('system.atenction')}}!" size="lg" theme="danger" icon="fas fa-ban" v-centered static-backdrop scrollable>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <x-slot name="footerSlot">
                    <x-adminlte-button theme="danger" label="{{__('system.close')}}" data-dismiss="modal" data-toggle="modal" data-target="#modalAdd"/>
                </x-slot>
            </x-adminlte-modal>

            <x-adminlte-button label="Open Modal" data-toggle="modal" data-target="#modalErrors" id="openModalErrors" style="display:none;"/>

        @endif

        <input type="hidden" id="errors" value="{{$errors->any()}}">

        <x-adminlte-button label="{{__('system.add_invoice')}}" data-toggle="modal" data-target="#modalAdd" class="bg-success" icon="fas fa-plus" id="openModalAdd"/>
        <x-adminlte-button data-toggle="modal" data-target="#modalEdit" id="openModalEdit" style="display:none;"/>
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
            __('system.invoice_date'),
            __('system.invoice_number'),
            __('system.provider'),
            __('system.construction'),
            ['label' => __('system.actions'), 'no-export' => true, 'width' => 5],
        ];
        $data = [];
        foreach ($invoices as $key => $invoice) {
            $data[$key]['invoice_date'] = date('d/m/Y', strtotime($invoice['invoice_date']));
            $data[$key]['invoice_number'] = $invoice['invoice_number'];
            $data[$key]['provider'] = $invoice['provider_name'];
            $data[$key]['construction'] = $invoice['construction_name'];
            $data[$key]['actions'] =
            "<nobr>
                <button class='btn btn-xs btn-default text-primary mx-1 shadow btnAction edit' title='".$system_edit."' data-id='".$invoice["id"]."'>
                    <i class='fa fa-lg fa-fw fa-pen'></i>
                </button>
                <button class='btn btn-xs btn-default text-danger mx-1 shadow btnAction delete' title='".$system_delete."' data-id='".$invoice["id"]."'>
                    <i class='fa fa-lg fa-fw fa-trash'></i>
                </button>
                <button class='btn btn-xs btn-default text-teal mx-1 shadow btnAction details' title='".$system_details."' data-id='".$invoice["id"]."'>
                    <i class='fa fa-lg fa-fw fa-eye'></i>
                </button>
            </nobr>";
        }

        $config = [
            'data' => $data,
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, ['orderable' => false]],
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

        {{-- Modal ADD --}}
        <x-adminlte-modal id="modalAdd" title=" {{ __('system.add_invoice') }}" size="lg" theme="success" icon="fas fa-file-invoice-dollar" v-centered static-backdrop scrollable>
            <form action="{{route('invoices.store')}}" method="post" enctype="multipart/form-data" id="form_add_invoice">
                @csrf
                <input type="hidden" name="company_id" value="{{Auth::user()->company_id}}">
                <input type="hidden" name="created_at" value="{{date('Y-m-d H:m:i')}}">

                <div class="row">
                    <x-adminlte-input name="construction" label="{{__('system.construction')}}" placeholder="{{__('system.construction')}}" fgroup-class="col-md-6" enable-old-support/>
                    <x-adminlte-input name="invoice_number" label="{{__('system.invoice_number')}}" placeholder="{{__('system.invoice_number')}}" fgroup-class="col-md" enable-old-support/>
                    <x-adminlte-input type="date" name="invoice_date" label="{{__('system.invoice_date')}}" placeholder="{{__('system.invoice_date')}}" fgroup-class="col-md" enable-old-support value="{{date('Y-m-d')}}"/>
                </div>
                <div class="row">
                    <x-adminlte-input name="provider" label="{{__('system.provider')}}" placeholder="{{__('system.provider')}}" fgroup-class="col-md-9" enable-old-support/>
                    <div class="col-m-3 value_field">
                        <h5>{{__('system.invoice_value')}}: </h5> <span class="invoice_value">R$ 0,00</span>
                    </div>
                </div>

                <hr>

                <h5>{{__('system.items')}}</h5>

                <div class="row">
                    <x-adminlte-input id="material" name="" label="{{__('system.material')}}" placeholder="{{__('system.material')}}" fgroup-class="col-md" enable-old-support/>
                    <x-adminlte-input id="unid" name="" label="{{__('system.unid')}}" placeholder="{{__('system.unid')}}" fgroup-class="col-md" enable-old-support/>
                    <x-adminlte-input id="qt" name="" label="{{__('system.qt')}}" placeholder="{{__('system.qt')}}" fgroup-class="col-md" enable-old-support/>
                    <x-adminlte-input id="unit_val" name="" label="{{__('system.unit_val')}}" placeholder="{{__('system.unit_val')}}" fgroup-class="col-md" enable-old-support>
                        <x-slot name="appendSlot">
                            <div class="input-group-text text-success" id="add_material_btn">
                                <i class="fas fa-plus"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input>
                </div>

                <hr>

                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>{{__('system.material')}}</th>
                            <th>{{__('system.unid')}}</th>
                            <th>{{__('system.qt')}}</th>
                            <th>{{__('system.unit_val')}}</th>
                            <th>{{__('system.total_val')}}</th>
                            <th>{{__('system.delete')}}</th>
                        </tr>
                    </thead>
                    <tbody id="tbody"></tbody>
                </table>

            <x-slot name="footerSlot">
                <x-adminlte-button type="submit" class="mr-auto" theme="success" label="Salvar"/>
            </form>
                <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>

        {{-- Modal EDIT --}}
        <x-adminlte-modal id="modalEdit" title=" {{ __('system.edit_invoice') }}" size="lg" theme="success" icon="fas fa-file-invoice-dollar" v-centered static-backdrop scrollable>
            <form id="form_edit_invoice" method="post" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <input type="hidden" name="updated_at" value="{{date('Y-m-d H:m:i')}}">
                <div class="row">
                    <x-adminlte-input id="edit_input_name" name="name" label="{{__('system.name')}}" placeholder="{{__('system.enter_name')}}" fgroup-class="col-md-12" enable-old-support/>
                </div>
                <div class="row">
                    <x-adminlte-input id="edit_input_obs" name="obs" label="{{__('system.obs')}}" placeholder="{{__('system.enter_obs')}}" fgroup-class="col-md-12" enable-old-support/>
                </div>
            <x-slot name="footerSlot">
                <x-adminlte-button type="submit" class="mr-auto" theme="success" label="Salvar"/>
            </form>
                <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>

    </x-adminlte-card>
@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
    <style>
        .table input.material {
            border:0;
            outline:0;
            width: 250px;
        }
        .table input.unid {
            border:0;
            outline:0;
            width: 100px;
        }
        .table input.qt {
            border:0;
            outline:0;
            width: 100px;
            text-align: right;
        }
        .table input.unit_val {
            border:0;
            outline:0;
            width: 100px;
            text-align: right;
        }
        .table .total_val {
            text-align: right;
            background-color: #eee;
        }
        .value_field {
            background-color: #eee;
            text-align: center;
            padding: 5px;
            border-radius: 5px;
        }
        .invoice_value {
            font-size: 16px;
            font-weight: bold;
            font-family:'Courier New', Courier, monospace;
        }
    </style>
@stop

@section('js')
    <script>

        let messages = document.querySelector('#messages').value
        if(messages !== '') {
            document.querySelector('#openModalMessages').click()
        }

        let errors = document.querySelector('#errors').value
        if(errors == 1) {
            document.querySelector('#openModalErrors').click()
        }

        let btnAction = document.querySelectorAll('.btnAction')
        btnAction.forEach((el) => {
            el.addEventListener('click', (ev)=>{
                let id = el.getAttribute('data-id')
                let action = el.classList.contains('edit') ? 'edit' : el.classList.contains('delete') ? 'delete' : el.classList.contains('details') ? 'details' : ''
                let invoice

                var ajax = new XMLHttpRequest();
                ajax.open("GET", "{{route('getInvoice')}}/?id="+id, true);
                ajax.send();
                ajax.onreadystatechange = function() {
                    if (ajax.readyState == 4 && ajax.status == 200) {
                        invoice = JSON.parse(ajax.responseText)
                        switch (action) {
                            case 'edit':
                                edit_invoice(invoice)
                                break;
                            case 'delete':
                                delete_invoice(invoice)
                                break;
                            case 'details':
                                show_invoice(invoice)
                                break;
                        }
                    }
                }
            })
        })

        const edit_invoice = (invoice) => {
            let route_edit = "{{route('invoices.update', ['invoice' => 'invoice_id'])}}"
            document.querySelector('#form_edit_invoice').setAttribute('action', route_edit.replace('invoice_id', invoice.id))
            document.querySelector('#edit_input_name').value = invoice.name
            document.querySelector('#edit_input_obs').value = invoice.obs
            document.querySelector('#openModalEdit').click()
        }

        const delete_invoice = (invoice) => {
            if(!confirm('system.delete_confirm')) {
                return false;
            }
            let id = invoice.id

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

        let invoice_value = 0

        document.querySelector('#add_material_btn').addEventListener('click', () => {
            let material = document.querySelector('#material').value
            let unid = document.querySelector('#unid').value
            let qt = document.querySelector('#qt').value
            let unit_val = document.querySelector('#unit_val').value

            if(material && unid && qt && unit_val) {

                let item = [material, unid, qt = parseFloat(qt), unit_val = parseFloat(unit_val)]
                let cols = ['material', 'unid', 'qt', 'unit_val']

                let row_1 = document.createElement('tr')

                for (let i = 1; i < 5; i++) {

                    window['row_1_data_'+i] = document.createElement('td')
                    window['row_1_data_'+i+'_input'] = document.createElement('input')
                    window['row_1_data_'+i+'_input'].type = 'text'
                    window['row_1_data_'+i+'_input'].name = 'materials'+'['+cols[i-1]+']'+'[]'
                    window['row_1_data_'+i+'_input'].setAttribute('readonly', '')
                    window['row_1_data_'+i+'_input'].classList.add(cols[i-1])
                    if(typeof(item[i-1]) === 'number') {
                        window['row_1_data_'+i+'_input'].value = item[i-1].toLocaleString('pt-BR', { minimumFractionDigits: 2 })
                    } else {
                        window['row_1_data_'+i+'_input'].value = item[i-1]
                    }

                    row_1.appendChild(window['row_1_data_'+i]);
                    window['row_1_data_'+i].appendChild(window['row_1_data_'+i+'_input']);
                }

                let row_1_data_5 = document.createElement('td')
                row_1_data_5.classList.add('total_val')
                row_1_data_5.innerHTML = (qt * unit_val).toLocaleString('pt-BR', { minimumFractionDigits: 2 , style: 'currency', currency: 'BRL' })

                let row_1_data_6 = document.createElement('td')
                row_1_data_6.innerHTML = "<button class='btn btn-outline-danger btn-sm delete_line'><i class='fas fa-lg fa-trash'></i></button>"

                row_1.appendChild(row_1_data_5);
                row_1.appendChild(row_1_data_6);

                document.querySelector('#tbody').appendChild(row_1)

                invoice_value += (qt * unit_val)
                document.querySelector('.invoice_value').innerHTML = invoice_value.toLocaleString('pt-BR', { minimumFractionDigits: 2 , style: 'currency', currency: 'BRL' })

                material = document.querySelector('#material').value = ''
                unid = document.querySelector('#unid').value = ''
                qt = document.querySelector('#qt').value = ''
                unit_val = document.querySelector('#unit_val').value = ''

            } else {
                alert('Todos os campos devem ser preenchidos!');
            }

        })
    </script>
@stop
