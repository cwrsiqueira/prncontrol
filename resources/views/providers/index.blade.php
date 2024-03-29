@extends('adminlte::page')

@section('title', 'PRNCONTROL | ' . __('system.providers'))

@section('content_header')
    <cw-header-title>
        <h1><i class="fas fa-truck"></i> {{ __('system.providers') }}</h1>

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

        <x-adminlte-button label="{{ __('system.add_provider') }}" data-toggle="modal" data-target="#modalAdd"
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
            $heads = [__('system.name'), __('system.obs'), ['label' => __('system.actions'), 'no-export' => true, 'width' => 5]];
            $data = [];
            foreach ($providers as $key => $provider) {
                $data[$key]['name'] = $provider['name'];
                $data[$key]['obs'] = $provider['obs'];
                $data[$key]['actions'] =
                    "<nobr>
                <button class='btn btn-xs btn-default text-primary mx-1 shadow btnAction edit' title='" .
                    $system_edit .
                    "' data-id='" .
                    $provider['id'] .
                    "'>
                    <i class='fa fa-lg fa-fw fa-pen'></i>
                </button>
                <button class='btn btn-xs btn-default text-teal mx-1 shadow btnAction details' title='" .
                    $system_details .
                    "' data-id='" .
                    $provider['id'] .
                    "'>
                    <i class='fa fa-lg fa-fw fa-eye'></i>
                </button>
                <button class='btn btn-xs btn-default text-danger mx-1 shadow btnAction delete' title='" .
                    $system_delete .
                    "' data-id='" .
                    $provider['id'] .
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
        <x-adminlte-modal id="modalAdd" title=" {{ __('system.add_provider') }}" size="lg" theme="success"
            icon="fas fa-truck" v-centered static-backdrop scrollable>
            <form action="{{ route('providers.store') }}" method="post" enctype="multipart/form-data"
                id="form_add_provider">
                @csrf
                <input type="hidden" name="company_id" value="{{ Auth::user()->company_id }}">

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
        <x-adminlte-modal id="modalEdit" title=" {{ __('system.edit_provider') }}" size="lg" theme="success"
            icon="fas fa-truck" v-centered static-backdrop scrollable>
            <form id="form_edit_provider" method="post" enctype="multipart/form-data">
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
                ajax.open("GET", "{{ route('getProvider') }}/?id=" + id, true);
                ajax.send();
                ajax.onreadystatechange = function() {
                    if (ajax.readyState == 4 && ajax.status == 200) {
                        provider = JSON.parse(ajax.responseText)
                        switch (action) {
                            case 'edit':
                                edit_provider(provider)
                                break;
                            case 'delete':
                                delete_provider(provider)
                                break;
                            case 'details':
                                show_provider(provider)
                                break;
                        }
                    }
                }
            })
        })

        const edit_provider = (provider) => {
            let route_edit = "{{ route('providers.update', ['provider' => 'provider_id']) }}"
            document.querySelector('#form_edit_provider').setAttribute('action', route_edit.replace('provider_id',
                provider.id))
            document.querySelector('#edit_input_name').value = provider.name
            document.querySelector('#edit_input_obs').value = provider.obs
            document.querySelector('#openModalEdit').click()
        }

        const delete_provider = (provider) => {
            if (!confirm('system.delete_confirm')) {
                return false;
            }
            let id = provider.id

            var ajax = new XMLHttpRequest();
            ajax.open("GET", "{{ route('delProvider') }}/?id=" + id, true);
            ajax.send();
            ajax.onreadystatechange = function() {
                if (ajax.readyState == 4 && ajax.status == 200) {
                    alert(ajax.responseText)
                    location.reload()
                }
            }
        }

        const show_provider = (provider) => {
            let route_show = "{{ route('providers.show', ['provider' => 'provider_id']) }}";
            window.location = route_show.replace('provider_id', provider.id);
        }
    </script>
@stop
