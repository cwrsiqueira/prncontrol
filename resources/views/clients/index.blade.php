@extends('adminlte::page')

@section('title', __('system.clients'))

@section('content_header')
    <cw-header-title>
        <h1><i class="fas fa-user"></i> {{ __('system.clients') }}</h1>

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

        <x-adminlte-button label="{{ __('system.add_client') }}" data-toggle="modal" data-target="#modalAdd"
            class="bg-success" icon="fas fa-plus" id="openModalAdd" />
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
            $heads = ['ID', __('system.name'), __('system.contact'), ['label' => __('system.actions'), 'no-export' => true, 'width' => 5]];
            $data = [];
            foreach ($clients as $key => $client) {
                $data[$key]['id'] = $client['id'];
                $data[$key]['nome_razao_social'] = $client['nome_razao_social'];
                $data[$key]['contact'] = $client->contacts[0]->descricao_contato . ': ' . $client->contacts[0]->dados_contato;
                $data[$key]['actions'] =
                    "<nobr>
                <button class='btn btn-xs btn-default text-primary mx-1 shadow btnAction edit' title='" .
                    $system_edit .
                    "' data-id='" .
                    $client['id'] .
                    "'>
                    <i class='fa fa-lg fa-fw fa-pen'></i>
                </button>
                <button class='btn btn-xs btn-default text-danger mx-1 shadow btnAction delete' title='" .
                    $system_delete .
                    "' data-id='" .
                    $client['id'] .
                    "'>
                    <i class='fa fa-lg fa-fw fa-trash'></i>
                </button>
                <button class='btn btn-xs btn-default text-teal mx-1 shadow btnAction details' title='" .
                    $system_details .
                    "' data-id='" .
                    $client['id'] .
                    "'>
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
            <form action="{{ route('clients.store') }}" method="post" enctype="multipart/form-data" id="form_add_lot">
                @csrf
                <input type="hidden" name="company_id" value="{{ Auth::user()->company_id }}">
                <input type="hidden" name="created_at" value="{{ date('Y-m-d H:m:i') }}">

                <div class="row">
                    <x-adminlte-input name="name" label="{{ __('system.name') }}"
                        placeholder="{{ __('system.enter_name') }}" fgroup-class="col-md-12" enable-old-support />
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
        <x-adminlte-modal id="modalEdit" title=" {{ __('system.edit_client') }}" size="lg" theme="success"
            icon="fas fa-th" v-centered static-backdrop scrollable>
            <form id="form_edit_client" method="post" enctype="multipart/form-data">
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
                let client

                var ajax = new XMLHttpRequest();
                ajax.open("GET", "{{ route('getClient') }}/?id=" + id, true);
                ajax.send();
                ajax.onreadystatechange = function() {
                    if (ajax.readyState == 4 && ajax.status == 200) {
                        client = JSON.parse(ajax.responseText)
                        switch (action) {
                            case 'edit':
                                edit_client(client)
                                break;
                            case 'delete':
                                delete_client(client)
                                break;
                            case 'details':
                                show_client(client)
                                break;
                        }
                    }
                }
            })
        })

        const edit_client = (client) => {
            let route_edit = "{{ route('clients.update', ['client' => 'client_id']) }}"
            document.querySelector('#form_edit_client').setAttribute('action', route_edit.replace('client_id', client
                .id))
            document.querySelector('#edit_input_name').value = client.name
            document.querySelector('#edit_input_obs').value = client.obs
            document.querySelector('#openModalEdit').click()
        }

        const delete_client = (client) => {
            if (!confirm('system.delete_confirm')) {
                return false;
            }
            let id = client.id

            var ajax = new XMLHttpRequest();
            ajax.open("GET", "{{ route('delClient') }}/?id=" + id, true);
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
