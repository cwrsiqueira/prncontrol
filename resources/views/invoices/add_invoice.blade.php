@extends('adminlte::page')

@section('title', 'PRNCONTROL | ' . __('system.add_invoice'))

@section('content_header')
    <cw-header-title>
        <h1><i class="fas fa-file-invoice-dollar"></i> {{ __('system.add_invoice') }}</h1>
        <div>
            <a href="{{ route('invoices.index') }}">{{ __('system.invoices') }}</a> | {{ __('system.add_invoice') }}
        </div>

        {{-- SHOW ERRORS FROM MODAL ADD --}}
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

        <form action="{{ route('invoices.store') }}" method="post" enctype="multipart/form-data" id="form_add_invoice">
            @csrf
            <input type="hidden" name="company_id" value="{{ Auth::user()->company_id }}">

            <div class="row">

                {{-- <x-adminlte-input id="construction" name="construction" label="{{__('system.construction')}}" placeholder="{{__('system.construction')}}" fgroup-class="col-md-6" class="search" enable-old-support/> --}}
                <x-adminlte-select2 name="construction" label="{{ __('system.construction') }}"
                    placeholder="{{ __('system.construction') }}" fgroup-class="col-md-6" enable-old-support>
                    <option value="">Selecione uma obra</option>
                    @foreach ($constructions as $construction)
                        <option value="{{ $construction->name }}">{{ $construction->name }}</option>
                    @endforeach
                </x-adminlte-select2>

                <x-adminlte-input name="invoice_number" label="{{ __('system.invoice_number') }}"
                    placeholder="{{ __('system.invoice_number') }}" fgroup-class="col-md" enable-old-support />
                <x-adminlte-input type="date" name="invoice_date" label="{{ __('system.invoice_date') }}"
                    placeholder="{{ __('system.invoice_date') }}" fgroup-class="col-md" enable-old-support
                    value="{{ date('Y-m-d') }}" min="2020-01-01" />
            </div>
            <div class="row">
                {{-- <x-adminlte-input id="provider" name="provider" label="{{__('system.provider')}}" placeholder="{{__('system.provider')}}" fgroup-class="col-md-9" class="search" enable-old-support/> --}}
                <x-adminlte-select2 name="provider" label="{{ __('system.provider') }}" fgroup-class="col-md-6"
                    enable-old-support>
                    <option value="">Selecione um fornecedor</option>
                    @foreach ($providers as $provider)
                        <option value="{{ $provider->name }}">{{ $provider->name }}</option>
                    @endforeach
                </x-adminlte-select2>
                <div class="col-m-3 value_field">
                    <h5>{{ __('system.invoice_value') }}: </h5> <input type="text" name="invoice_value"
                        class="invoice_value" value="{{ old('invoice_value') ?? 'R$ 0,00' }}" readonly>
                </div>
            </div>

            <hr>

            <h5>{{ __('system.items') }}</h5>

            <div class="row">
                {{-- <x-adminlte-input id="material" name="" label="{{__('system.material')}}" placeholder="{{__('system.material')}}" fgroup-class="col-md" class="search" enable-old-support/> --}}
                <x-adminlte-select2 id="add_material" name="" label="{{ __('system.material') }}"
                    fgroup-class="col-md" enable-old-support>
                    <option value="">Selecione um material</option>
                    <option value="ajuste">Ajuste do valor da nota</option>
                    @foreach ($materials as $material)
                        <option value="{{ $material->name }}">{{ $material->name }}</option>
                    @endforeach
                </x-adminlte-select2>
                <x-adminlte-input id="add_unid" name="" label="{{ __('system.unid') }}"
                    placeholder="{{ __('system.unid') }}" fgroup-class="col-md" enable-old-support />
                <x-adminlte-input id="add_qt" name="" label="{{ __('system.qt') }}"
                    placeholder="{{ __('system.only_numbers') }}" fgroup-class="col-md" enable-old-support />
                <x-adminlte-input id="add_unit_val" name="" label="{{ __('system.unit_val') }}"
                    placeholder="{{ __('system.only_numbers') }}" fgroup-class="col-md" enable-old-support>
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
                        <th>{{ __('system.material') }}</th>
                        <th>{{ __('system.unid') }}</th>
                        <th>{{ __('system.qt') }}</th>
                        <th>{{ __('system.unit_val') }}</th>
                        <th>{{ __('system.total_val') }}</th>
                        <th>{{ __('system.delete') }}</th>
                    </tr>
                </thead>
                <tbody id="add_tbody">
                    @if (old('materials'))
                        @for ($i = 0; $i < count(old('materials')['material']); $i++)
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
                                        <input type="text" name="materials[{{ $key }}][]" readonly=""
                                            class="{{ $key }}" value="{{ $item[$i] }}">
                                    </td>
                                @endforeach
                                <td class="total_val">{{ number_format($total_val, 2, ',', '.') }}</td>
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

            <div class="area-buttons">
                <x-adminlte-button type="submit" class="d-flex mr-auto" theme="success" label="Salvar" />
                <a href="{{ route('invoices.index') }}" class="btn btn-danger ml-auto">Sair</a>
            </div>

        </form>

    </x-adminlte-card>
@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
    <style>
        .table input.material {
            border: 0;
            outline: 0;
            width: 250px;
        }

        .table input.unid {
            border: 0;
            outline: 0;
            width: 100px;
        }

        .table input.qt {
            border: 0;
            outline: 0;
            width: 100px;
            text-align: right;
        }

        .table input.unit_val {
            border: 0;
            outline: 0;
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
            font-family: 'Courier New', Courier, monospace;
            border: 0;
            outline: 0;
            width: 250px;
            text-align: right;
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
        const calc_invoice_value = (value, action) => {
            let invoice_value = document.querySelector('.invoice_value').value || '0'
            invoice_value = invoice_value.replace(/[^0-9.,]/g, '').replace(/[.]/g, '').replace(/[,]/g, '.')

            present_value = (action == '+') ? parseFloat(invoice_value) + parseFloat(value) : parseFloat(
                invoice_value) - parseFloat(value)

            document.querySelector('.invoice_value').value = 'R$ ' + present_value.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })
        }

        const deleteLine = (e) => {
            if (confirm('Confirma a exclusão do material?')) {
                let line_value = e.parentNode.parentNode.querySelector('.total_val').innerHTML
                line_value = line_value.replace(/[^0-9.,]/g, '').replace(/[.]/g, '').replace(/[,]/g, '.')
                calc_invoice_value(line_value, '-')
                e.parentNode.parentNode.remove()
            }
        }

        let errors = document.querySelector('#errors').value
        if (errors == 1) {
            document.querySelector('#openModalErrors').click()
        }

        let add_material_btn = document.querySelectorAll('.add_material_btn')

        add_material_btn.forEach(el => {
            let action = el.getAttribute('data-action')
            el.addEventListener('click', () => {

                let material = document.querySelector('#' + action + 'material').value
                let unid = document.querySelector('#' + action + 'unid').value
                let qt = document.querySelector('#' + action + 'qt').value
                let unit_val = document.querySelector('#' + action + 'unit_val').value

                qt = qt.replace('.', '')
                qt = qt.replace(',', '.')
                unit_val = unit_val.replace('.', '')
                unit_val = unit_val.replace(',', '.')

                if (material && unid && qt && unit_val) {

                    let item = [material, unid, qt = parseFloat(qt), unit_val = parseFloat(unit_val)]
                    let cols = ['material', 'unid', 'qt', 'unit_val']

                    let row_1 = document.createElement('tr')

                    for (let i = 1; i < 5; i++) {

                        window['row_1_data_' + i] = document.createElement('td')
                        window['row_1_data_' + i + '_input'] = document.createElement('input')
                        window['row_1_data_' + i + '_input'].type = 'text'
                        window['row_1_data_' + i + '_input'].name = 'materials' + '[' + cols[i - 1] + ']' +
                            '[]'
                        window['row_1_data_' + i + '_input'].setAttribute('readonly', '')
                        window['row_1_data_' + i + '_input'].classList.add(cols[i - 1])
                        if (cols[i - 1] === 'qt') {
                            window['row_1_data_' + i + '_input'].value = item[i - 1].toLocaleString(
                                'pt-BR', {
                                    minimumFractionDigits: 2
                                })
                        } else if (cols[i - 1] === 'unit_val') {
                            window['row_1_data_' + i + '_input'].value = item[i - 1].toLocaleString(
                                'pt-BR', {
                                    minimumFractionDigits: 4
                                })
                        } else {
                            window['row_1_data_' + i + '_input'].value = item[i - 1]
                        }

                        row_1.appendChild(window['row_1_data_' + i]);
                        window['row_1_data_' + i].appendChild(window['row_1_data_' + i + '_input']);
                    }

                    let row_1_data_5 = document.createElement('td')
                    row_1_data_5.classList.add('total_val')
                    row_1_data_5.innerHTML = (qt * unit_val).toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        style: 'currency',
                        currency: 'BRL'
                    })

                    let row_1_data_6 = document.createElement('td')
                    row_1_data_6.innerHTML =
                        '<div class="btn btn-outline-danger btn-sm delete_line" onclick="deleteLine(this)"><i class="fas fa-lg fa-trash"></i></div>'

                    row_1.appendChild(row_1_data_5);
                    row_1.appendChild(row_1_data_6);

                    document.querySelector('#' + action + 'tbody').appendChild(row_1)

                    calc_invoice_value(qt * unit_val, '+')
                    // invoice_value += (qt * unit_val)
                    // document.querySelector('.invoice_value').innerHTML = invoice_value.toLocaleString('pt-BR', { minimumFractionDigits: 2 , style: 'currency', currency: 'BRL' })

                    // document.querySelector('#' + action + 'material').value = '';
                    document.querySelector('#' + action + 'unid').value = '';
                    document.querySelector('#' + action + 'qt').value = '';
                    document.querySelector('#' + action + 'unit_val').value = '';

                    let del_btn = document.querySelector('.delete_line');

                } else {
                    alert('Todos os campos devem ser preenchidos!');
                }

            })

            $(function() {
                $('#' + action + 'qt').mask('#.#00,00', {
                    reverse: true
                })
                $('#' + action + 'unit_val').mask('#.#00,0000', {
                    reverse: true
                })
            })
        })
    </script>
@stop
