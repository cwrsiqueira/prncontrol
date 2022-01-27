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

        {{-- SHOW ERRORS FROM MODAL ADD --}}
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

        {{-- SHOW ERRORS FROM MODAL EDIT --}}
        @if($errors->edit->any())
            <x-adminlte-modal id="modalEditErrors" title="{{__('system.atenction')}}!" size="lg" theme="danger" icon="fas fa-ban" v-centered static-backdrop scrollable>
                <ul>
                    @foreach ($errors->edit->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <x-slot name="footerSlot">
                    <x-adminlte-button theme="danger" label="{{__('system.close')}}" data-dismiss="modal" data-toggle="modal" data-target="#modalEdit" id="btn_open_modal_edit"/>
                </x-slot>
            </x-adminlte-modal>

            <x-adminlte-button label="Open Modal" data-toggle="modal" data-target="#modalEditErrors" id="openModalEditErrors" style="display:none;"/>

        @endif

        <input type="hidden" id="errors" value="{{$errors->any()}}">
        <input type="hidden" id="edit_errors" value="{{$errors->edit->any()}}">

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

                    {{-- <x-adminlte-input id="construction" name="construction" label="{{__('system.construction')}}" placeholder="{{__('system.construction')}}" fgroup-class="col-md-6" class="search" enable-old-support/> --}}
                    <x-adminlte-select2 name="construction" label="{{__('system.construction')}}" placeholder="{{__('system.construction')}}" fgroup-class="col-md-6" enable-old-support>
                        <option value="">Selecione uma obra</option>
                        @foreach ($constructions as $construction)
                            <option value="{{$construction->name}}">{{$construction->name}}</option>
                        @endforeach
                    </x-adminlte-select2>

                    <x-adminlte-input name="invoice_number" label="{{__('system.invoice_number')}}" placeholder="{{__('system.invoice_number')}}" fgroup-class="col-md" enable-old-support/>
                    <x-adminlte-input type="date" name="invoice_date" label="{{__('system.invoice_date')}}" placeholder="{{__('system.invoice_date')}}" fgroup-class="col-md" enable-old-support value="{{date('Y-m-d')}}"/>
                </div>
                <div class="row">
                    {{-- <x-adminlte-input id="provider" name="provider" label="{{__('system.provider')}}" placeholder="{{__('system.provider')}}" fgroup-class="col-md-9" class="search" enable-old-support/> --}}
                    <x-adminlte-select2 name="provider" label="{{__('system.provider')}}" fgroup-class="col-md-6" enable-old-support>
                        <option value="">Selecione um fornecedor</option>
                        @foreach ($providers as $provider)
                            <option value="{{$provider->name}}">{{$provider->name}}</option>
                        @endforeach
                    </x-adminlte-select2>
                    <div class="col-m-3 value_field">
                        <h5>{{__('system.invoice_value')}}: </h5> <span class="invoice_value">R$ 0,00</span>
                    </div>
                </div>

                <hr>

                <h5>{{__('system.items')}}</h5>

                <div class="row">
                    {{-- <x-adminlte-input id="material" name="" label="{{__('system.material')}}" placeholder="{{__('system.material')}}" fgroup-class="col-md" class="search" enable-old-support/> --}}
                    <x-adminlte-select2 id="add_material" name="" label="{{__('system.material')}}" fgroup-class="col-md" enable-old-support>
                        <option value="">Selecione um material</option>
                        @foreach ($materials as $material)
                            <option value="{{$material->name}}">{{$material->name}}</option>
                        @endforeach
                    </x-adminlte-select2>
                    <x-adminlte-input id="add_unid" name="" label="{{__('system.unid')}}" placeholder="{{__('system.unid')}}" fgroup-class="col-md" enable-old-support/>
                    <x-adminlte-input id="add_qt" name="" label="{{__('system.qt')}}" placeholder="{{__('system.only_numbers')}}" fgroup-class="col-md" enable-old-support/>
                    <x-adminlte-input id="add_unit_val" name="" label="{{__('system.unit_val')}}" placeholder="{{__('system.only_numbers')}}" fgroup-class="col-md" enable-old-support>
                        <x-slot name="appendSlot">
                            <div class="input-group-text text-success add_material_btn" data-action="add_">
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
                    <tbody id="add_tbody">
                        @if (old('materials'))
                            @for($i=0;$i<count(old('materials')['material']);$i++)
                                @php
                                    $qt = old('materials')['qt'][$i];
                                    $qt = str_replace('.', '', $qt);
                                    $qt = str_replace(',', '.', $qt);
                                    $unit_val = old('materials')['unit_val'][$i];
                                    $unit_val = str_replace('.', '', $unit_val);
                                    $unit_val = str_replace(',', '.', $unit_val);
                                    $total_val = $qt * $unit_val;
                                @endphp
                                <tr>
                                    @foreach (old('materials') as $key => $item)
                                    <td>
                                        <input type="text" name="materials[{{$key}}][]" readonly="" class="{{$key}}" value="{{$item[$i]}}">
                                    </td>
                                    @endforeach
                                    <td class="total_val">{{number_format($total_val, 2, ',', '.')}}</td>
                                    <td>
                                        <div class="btn btn-outline-danger btn-sm delete_line" onclick="deleteLine(this)">
                                            <i class="fas fa-lg fa-trash"></i>
                                        </div>
                                    </td>
                                </tr>
                            @endfor
                        @endif
                    </tbody>
                </table>

            <x-slot name="footerSlot">
                <x-adminlte-button type="submit" class="mr-auto" theme="success" label="Salvar"/>
            </form>
                <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>

        {{-- Modal EDIT --}}
        <x-adminlte-modal id="modalEdit" title=" {{ __('system.edit_invoice') }}" size="lg" theme="success" icon="fas fa-file-invoice-dollar" v-centered static-backdrop scrollable>
            <form method="post" enctype="multipart/form-data" id="form_edit_invoice">
                @method('PUT')
                @csrf
                <x-adminlte-input type="hidden" name="invoiceId" enable-old-support/>
                <input type="hidden" name="company_id" value="{{Auth::user()->company_id}}">
                <input type="hidden" name="updated_at" value="{{date('Y-m-d H:m:i')}}">

                <div class="row">

                    {{-- <x-adminlte-input id="construction" name="construction" label="{{__('system.construction')}}" placeholder="{{__('system.construction')}}" fgroup-class="col-md-6" class="search" enable-old-support/> --}}
                    <x-adminlte-select2 id="edit_construction" name="construction" label="{{__('system.construction')}}" placeholder="{{__('system.construction')}}" fgroup-class="col-md-6" enable-old-support>
                        <option value="">Selecione uma obra</option>
                        @foreach ($constructions as $construction)
                            <option value="{{$construction->name}}">{{$construction->name}}</option>
                        @endforeach
                    </x-adminlte-select2>

                    <x-adminlte-input id="edit_invoice_number" name="invoice_number" label="{{__('system.invoice_number')}}" placeholder="{{__('system.invoice_number')}}" fgroup-class="col-md" enable-old-support/>
                    <x-adminlte-input type="date" id="edit_invoice_date" name="invoice_date" label="{{__('system.invoice_date')}}" placeholder="{{__('system.invoice_date')}}" fgroup-class="col-md" enable-old-support value="{{date('Y-m-d')}}"/>
                </div>
                <div class="row">
                    {{-- <x-adminlte-input id="provider" name="provider" label="{{__('system.provider')}}" placeholder="{{__('system.provider')}}" fgroup-class="col-md-9" class="search" enable-old-support/> --}}
                    <x-adminlte-select2 id="edit_provider" name="provider" label="{{__('system.provider')}}" fgroup-class="col-md-6" enable-old-support>
                        <option value="">Selecione um fornecedor</option>
                        @foreach ($providers as $provider)
                            <option value="{{$provider->name}}">{{$provider->name}}</option>
                        @endforeach
                    </x-adminlte-select2>
                    <div class="col-m-3 value_field">
                        <h5>{{__('system.invoice_value')}}: </h5> <span class="invoice_value">R$ 0,00</span>
                    </div>
                </div>

                <hr>

                <h5>{{__('system.items')}}</h5>

                <div class="row">
                    {{-- <x-adminlte-input id="material" name="" label="{{__('system.material')}}" placeholder="{{__('system.material')}}" fgroup-class="col-md" class="search" enable-old-support/> --}}
                    <x-adminlte-select2 id="edit_material" name="" label="{{__('system.material')}}" fgroup-class="col-md" enable-old-support>
                        <option value="">Selecione um material</option>
                        @foreach ($materials as $material)
                            <option value="{{$material->name}}">{{$material->name}}</option>
                        @endforeach
                    </x-adminlte-select2>
                    <x-adminlte-input id="edit_unid" name="" label="{{__('system.unid')}}" placeholder="{{__('system.unid')}}" fgroup-class="col-md" enable-old-support/>
                    <x-adminlte-input id="edit_qt" name="" label="{{__('system.qt')}}" placeholder="{{__('system.only_numbers')}}" fgroup-class="col-md" enable-old-support/>
                    <x-adminlte-input id="edit_unit_val" name="" label="{{__('system.unit_val')}}" placeholder="{{__('system.only_numbers')}}" fgroup-class="col-md" enable-old-support>
                        <x-slot name="appendSlot">
                            <div class="input-group-text text-success add_material_btn" data-action="edit_">
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
                    <tbody id="edit_tbody">

                    </tbody>
                </table>

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

        .search_result {
            position: fixed;
            border: 1px solid #ccc;
            background-color: #eee;
            margin-top: 38px;
            z-index: 9;
        }
        .search_result_insert {
            padding: 10px;
        }
        .search_result_insert:hover {
            cursor: pointer;
            background-color: green;
            color: #fff;
        }
        .no_results {
            padding: 10px;
        }
    </style>
@stop

@section('js')
    <script>

        const deleteLine = (e) => {
            if(confirm('Confirma a exclusão do material?')) {
                e.parentNode.parentNode.remove()
            }
        }

        let messages = document.querySelector('#messages').value
        if(messages !== '') {
            document.querySelector('#openModalMessages').click()
        }

        let errors = document.querySelector('#errors').value
        if(errors == 1) {
            document.querySelector('#openModalErrors').click()
        }

        const get_invoice = (id, action) => {
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
        }

        let edit_errors = document.querySelector('#edit_errors').value
        if(edit_errors == 1) {
            document.querySelector('#openModalEditErrors').click()

            document.querySelector('#btn_open_modal_edit').addEventListener('click', ()=>{
                let id = document.querySelector('#invoiceId').value
                get_invoice(id, 'edit')
            })

        }

        let btnAction = document.querySelectorAll('.btnAction')
        btnAction.forEach((el) => {
            el.addEventListener('click', (ev)=>{
                let id = el.getAttribute('data-id')
                let action = el.classList.contains('edit') ? 'edit' : el.classList.contains('delete') ? 'delete' : el.classList.contains('details') ? 'details' : ''
                get_invoice(id, action)
            })
        })

        const edit_invoice = (invoice) => {

            let route_edit = "{{route('invoices.update', ['invoice' => 'invoice_id'])}}"
            document.querySelector('#form_edit_invoice').setAttribute('action', route_edit.replace('invoice_id', invoice.id))

            document.querySelector('#invoiceId').value = invoice.id

            document.querySelector('#edit_construction').innerHTML =
                '<option value="'+invoice.construction_name+'">'+invoice.construction_name+'</option>@foreach ($constructions as $construction)<option value="{{$construction->name}}">{{$construction->name}}</option>@endforeach'

            document.querySelector('#edit_invoice_number').value = invoice.invoice_number
            document.querySelector('#edit_invoice_date').value = invoice.invoice_date

            document.querySelector('#edit_provider').innerHTML =
                '<option value="'+invoice.provider_name+'">'+invoice.provider_name+'</option>@foreach ($providers as $provider)<option value="{{$provider->name}}">{{$provider->name}}</option>@endforeach'

            let html = ''
            html += '@if (old("materials"))'
            html += '@for($i=0;$i<count(old("materials")["material"]);$i++)'
            html += '@php'
            html += '$qt = old("materials")["qt"][$i];'
            html += '$qt = str_replace(".", "", $qt);'
            html += '$qt = str_replace(",", ".", $qt);'
            html += '$unit_val = old("materials")["unit_val"][$i];'
            html += '$unit_val = str_replace(".", "", $unit_val);'
            html += '$unit_val = str_replace(",", ".", $unit_val);'
            html += '$total_val = $qt * $unit_val;'
            html += '@endphp'
            html += '<tr>'
            html += '@foreach (old("materials") as $key => $item)'
            html += '<td>'
            html += '<input type="text" name="materials[{{$key}}][]" readonly="" class="{{$key}}" value="{{$item[$i]}}">'
            html += '</td>'
            html += '@endforeach'
            html += '<td class="total_val">{{number_format($total_val, 2, ",", ".")}}</td>'
            html += '<td>'
            html += '<div class="btn btn-outline-danger btn-sm delete_line" onclick="deleteLine(this)">'
            html += '<i class="fas fa-lg fa-trash"></i>'
            html += '</div>'
            html += '</td>'
            html += '</tr>'
            html += '@endfor'
            html += '@endif'

            document.querySelector('#edit_tbody').innerHTML = html

            document.querySelector('#openModalEdit').click()
        }

        const delete_invoice = (invoice) => {
            if(!confirm('Confirma a exclusão da nota?')) {
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

        let add_material_btn = document.querySelectorAll('.add_material_btn')

        add_material_btn.forEach(el => {
            let action = el.getAttribute('data-action')
            let invoice_value = 0
            el.addEventListener('click', () => {

                let material = document.querySelector('#'+action+'material').value
                let unid = document.querySelector('#'+action+'unid').value
                let qt = document.querySelector('#'+action+'qt').value
                let unit_val = document.querySelector('#'+action+'unit_val').value

                qt = qt.replace('.', '')
                qt = qt.replace(',', '.')
                unit_val = unit_val.replace('.', '')
                unit_val = unit_val.replace(',', '.')

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
                    row_1_data_6.innerHTML = "<div class='btn btn-outline-danger btn-sm delete_line' onclick='deleteLine(this)'><i class='fas fa-lg fa-trash'></i></div>"

                    row_1.appendChild(row_1_data_5);
                    row_1.appendChild(row_1_data_6);

                    document.querySelector('#'+action+'tbody').appendChild(row_1)

                    invoice_value += (qt * unit_val)
                    document.querySelector('.invoice_value').innerHTML = invoice_value.toLocaleString('pt-BR', { minimumFractionDigits: 2 , style: 'currency', currency: 'BRL' })

                    material = document.querySelector('#'+action+'material').value = ''
                    unid = document.querySelector('#'+action+'unid').value = ''
                    qt = document.querySelector('#'+action+'qt').value = ''
                    unit_val = document.querySelector('#'+action+'unit_val').value = ''

                    let del_btn = document.querySelector('.delete_line');

                } else {
                    alert('Todos os campos devem ser preenchidos!');
                }

            })

            $(function(){
                $('#'+action+'qt').mask('#.#00,00', {reverse:true})
                $('#'+action+'unit_val').mask('#.#00,00', {reverse:true})
            })
        })

    </script>
@stop
