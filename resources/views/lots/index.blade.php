@extends('adminlte::page')

@section('title', 'PRNCONTROL | ' . __('system.lots'))

@section('content_header')
    <cw-header-title>
        <h1><i class="fas fa-th"></i> {{ __('system.lots') }}</h1>

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

        <x-adminlte-button label="{{ __('system.add_lot') }}" data-toggle="modal" data-target="#modalAdd" class="bg-success"
            icon="fas fa-plus" id="openModalAdd" />
        <x-adminlte-button data-toggle="modal" data-target="#modalEdit" id="openModalEdit" style="display:none;" />
    </cw-header-title>
@stop

@section('content')
    <x-adminlte-card theme="success" theme-mode="outline">
        {{-- Setup data for datatables --}}
        @php
            $system_edit = __('system.edit');
            $system_delete = __('system.delete');
            $system_details = __('system.details');
            $heads = [__('system.name'), __('system.obs'), ['label' => __('system.actions'), 'no-export' => true, 'width' => 5]];
            $data = [];
            foreach ($lots as $key => $lot) {
                $data[$key]['name'] = $lot['name'];
                $data[$key]['obs'] = $lot['obs'];
                $data[$key]['actions'] =
                    "<nobr>
                <button class='btn btn-xs btn-default text-primary mx-1 shadow btnAction edit' title='" .
                    $system_edit .
                    "' data-id='" .
                    $lot['id'] .
                    "'>
                    <i class='fa fa-lg fa-fw fa-pen'></i>
                </button>
                <button class='btn btn-xs btn-default text-teal mx-1 shadow btnAction details' title='" .
                    $system_details .
                    "' data-id='" .
                    $lot['id'] .
                    "'>
                    <i class='fa fa-lg fa-fw fa-eye'></i>
                </button>
                <button class='btn btn-xs btn-default text-danger mx-1 shadow btnAction delete' title='" .
                    $system_delete .
                    "' data-id='" .
                    $lot['id'] .
                    "'>
                    <i class='fa fa-lg fa-fw fa-trash'></i>
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
            @foreach ($config['data'] as $row)
                <tr>
                    @foreach ($row as $cell)
                        <td>{!! $cell !!}</td>
                    @endforeach
                </tr>
            @endforeach
        </x-adminlte-datatable>

        {{-- Modal ADD --}}
        <x-adminlte-modal id="modalAdd" title=" {{ __('system.add_lot') }}" size="lg" theme="success" icon="fas fa-th"
            v-centered static-backdrop scrollable>
            <form action="{{ route('lots.store') }}" method="post" enctype="multipart/form-data" id="form_add_lot">
                @csrf
                <input type="hidden" name="company_id" value="{{ Auth::user()->company_id }}">

                <h4>Localização</h4>
                <div class="row">
                    <x-adminlte-input name="loteamento" label="Loteamento"
                        placeholder="Loteamento Conjunto Agrovilla Hortifrutigranjeiro, Loteamento Conjunto Fazendinha Alfaville"
                        fgroup-class="col-md-12" enable-old-support />
                </div>
                <div class="row">
                    <x-adminlte-input name="quadra" label="Quadra" placeholder="Ex.: 45" fgroup-class="col-md-12"
                        enable-old-support />
                </div>
                <div class="row">
                    <x-adminlte-input name="lote" label="Lote" placeholder="Ex.: 10" fgroup-class="col-md-12"
                        enable-old-support />
                </div>

                <h4>Endereço</h4>
                <div class="row">
                    <x-adminlte-input name="cep" label="CEP" placeholder="Ex.: 12.234-56" fgroup-class="col-md-12"
                        enable-old-support />
                </div>
                <div class="row">
                    <x-adminlte-input name="logradouro_nome" label="Endereço" placeholder="Ex.: Rua Jardim das Flores"
                        fgroup-class="col-md-12" enable-old-support />
                </div>
                <div class="row">
                    <x-adminlte-input name="logradouro_número" label="Número" placeholder="Ex.: 123"
                        fgroup-class="col-md-12" enable-old-support />
                </div>
                <div class="row">
                    <x-adminlte-input name="bairro" label="Bairro" placeholder="Ex.: Jardins" fgroup-class="col-md-12"
                        enable-old-support />
                </div>
                <div class="row">
                    <x-adminlte-input name="cidade" label="Cidade" placeholder="Ex.: Floripa" fgroup-class="col-md-12"
                        enable-old-support />
                </div>
                <div class="row">
                    <x-adminlte-input name="estado" label="Estado" placeholder="Ex.: SC" fgroup-class="col-md-12"
                        enable-old-support />
                </div>

                <h4>Informações</h4>
                <div class="row">
                    <x-adminlte-input name="area_m2" label="Área (m²)" placeholder="Ex.: 300" fgroup-class="col-md-12"
                        enable-old-support />
                </div>
                <div class="row">
                    <x-adminlte-input name="formato" label="Formato"
                        placeholder="Ex.: retangular, quadrado, irregular, outros" fgroup-class="col-md-12"
                        enable-old-support />
                </div>
                <div class="row">
                    <x-adminlte-input name="aprovacao_orgao" label="Aprovado pelo órgão"
                        placeholder="Ex.: Prefeitura Municipal de Floripa-SC" fgroup-class="col-md-12"
                        enable-old-support />
                </div>
                <div class="row">
                    <x-adminlte-input name="aprovacao_documento" label="Documento de aprovação"
                        placeholder="Ex.: Alvará, Decreto, outros" fgroup-class="col-md-12" enable-old-support />
                </div>
                <div class="row">
                    <x-adminlte-input name="aprovacao_numero" label="Número da aprovação" placeholder="Ex.: 1.234/2020"
                        fgroup-class="col-md-12" enable-old-support />
                </div>
                <div class="row">
                    <x-adminlte-input name="aprovacao_data" label="Data da Aprovação" placeholder="Ex.: 10/10/2020"
                        fgroup-class="col-md-12" enable-old-support />
                </div>
                <div class="row">
                    <x-adminlte-input name="registro_cartorio_nome" label="Cartório de Registro"
                        placeholder="Ex.: Cartório de Registro de Imóveis de Floripa-SC" fgroup-class="col-md-12"
                        enable-old-support />
                </div>
                <div class="row">
                    <x-adminlte-input name="registro_cartorio_numero" label="Número do registro"
                        placeholder="Ex.: 1.234 L-1/A" fgroup-class="col-md-12" enable-old-support />
                </div>
                <div class="row">
                    <x-adminlte-input name="confrontacoes" label="Confrontações"
                        placeholder="Ex.: Frente para Rua 01 com 12m, fundos para o lote 02 com 12m, lado direito para o lote 03 com 25m e lado esquerdo para a Rua 02 com 25m"
                        fgroup-class="col-md-12" enable-old-support />
                </div>
                <div class="row">
                    <x-adminlte-input name="valor" label="Valor" placeholder="Ex.: 150.000,00"
                        fgroup-class="col-md-12" enable-old-support />
                </div>

                <div class="row">
                    <x-adminlte-input name="obs" label="{{ __('system.obs') }}"
                        placeholder="{{ __('system.enter_obs') }}" fgroup-class="col-md-12" enable-old-support />
                </div>
                <x-slot name="footerSlot">
                    <x-adminlte-button type="submit" class="mr-auto" theme="success" label="Salvar" />
            </form>
            <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal" />
            </x-slot>
        </x-adminlte-modal>

        {{-- Modal EDIT --}}
        <x-adminlte-modal id="modalEdit" title=" {{ __('system.edit_lot') }}" size="lg" theme="success"
            icon="fas fa-th" v-centered static-backdrop scrollable>
            <form id="form_edit_lot" method="post" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <input type="hidden" name="updated_at" value="{{ date('Y-m-d H:m:i') }}">
                <div class="row">
                    <x-adminlte-input id="edit_input_name" name="name" label="{{ __('system.name') }}"
                        placeholder="{{ __('system.enter_name') }}" fgroup-class="col-md-12" enable-old-support />
                </div>
                <div class="row">
                    <x-adminlte-input id="edit_input_obs" name="obs" label="{{ __('system.obs') }}"
                        placeholder="{{ __('system.enter_obs') }}" fgroup-class="col-md-12" enable-old-support />
                </div>
                <x-slot name="footerSlot">
                    <x-adminlte-button type="submit" class="mr-auto" theme="success" label="Salvar" />
            </form>
            <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal" />
            </x-slot>
        </x-adminlte-modal>

    </x-adminlte-card>
@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop

@section('js')
    <script>
        let messages = document.querySelector('#messages').value
        if (messages !== '') {
            document.querySelector('#openModalMessages').click()
        }

        let errors = document.querySelector('#errors').value
        if (errors == 1) {
            document.querySelector('#openModalErrors').click()
        }

        let btnAction = document.querySelectorAll('.btnAction')
        btnAction.forEach((el) => {
            el.addEventListener('click', (ev) => {
                let id = el.getAttribute('data-id')
                let action = el.classList.contains('edit') ? 'edit' : el.classList.contains('delete') ?
                    'delete' : el.classList.contains('details') ? 'details' : ''
                let lot

                var ajax = new XMLHttpRequest();
                ajax.open("GET", "{{ route('getLot') }}/?id=" + id, true);
                ajax.send();
                ajax.onreadystatechange = function() {
                    if (ajax.readyState == 4 && ajax.status == 200) {
                        lot = JSON.parse(ajax.responseText)
                        switch (action) {
                            case 'edit':
                                edit_lot(lot)
                                break;
                            case 'delete':
                                delete_lot(lot)
                                break;
                            case 'details':
                                show_lot(lot)
                                break;
                        }
                    }
                }
            })
        })

        const edit_lot = (lot) => {
            let route_edit = "{{ route('lots.update', ['lot' => 'lot_id']) }}"
            document.querySelector('#form_edit_lot').setAttribute('action', route_edit.replace('lot_id', lot.id))
            document.querySelector('#edit_input_name').value = lot.name
            document.querySelector('#edit_input_obs').value = lot.obs
            document.querySelector('#openModalEdit').click()
        }

        const delete_lot = (lot) => {
            if (!confirm('system.delete_confirm')) {
                return false;
            }
            let id = lot.id

            var ajax = new XMLHttpRequest();
            ajax.open("GET", "{{ route('delLot') }}/?id=" + id, true);
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
