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
            $heads = [__('system.name'), __('system.contact'), ['label' => __('system.actions'), 'no-export' => true, 'width' => 5]];
            $data = [];
            foreach ($clients as $key => $client) {
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
        <x-adminlte-modal id="modalAdd" title=" {{ __('system.add_client') }}" size="lg" theme="success"
            icon="fas fa-user" v-centered static-backdrop scrollable>
            <form action="{{ route('clients.store') }}" method="post" enctype="multipart/form-data" id="form_add_client">
                @csrf
                <input type="hidden" name="company_id" value="{{ Auth::user()->company_id }}">

                <div class="row">
                    <x-adminlte-select onchange="selectPessoa(this)" name="pessoa" enable-old-support>
                        <x-adminlte-options :options="['fisica' => 'Pessoa Física', 'juridica' => 'Pessoa Jurídica']" />
                    </x-adminlte-select>
                </div>

                <div class="area-pessoa-fisica @if (old('pessoa') == 'fisica' || old('pessoa') == '') @else hidden @endif">
                    <hr>
                    <h4>{{ __('system.client_informations') }}</h4>

                    <div class="row">
                        <x-adminlte-input name="nome" label="{{ __('system.name') }}"
                            placeholder="{{ __('system.enter_name') }}" fgroup-class="col-md-12" enable-old-support />
                    </div>

                    <div class="row">
                        <x-adminlte-input name="cpf" label="{{ __('system.cpf') }}" placeholder="000.000.000-00"
                            fgroup-class="col-md-12" enable-old-support />
                    </div>

                    <div class="row">
                        <x-adminlte-input name="nacionalidade" label="{{ __('system.nationality') }}"
                            placeholder="{{ __('system.nationality') }}" fgroup-class="col-md" enable-old-support />
                        <x-adminlte-input name="estado_civil" label="{{ __('system.marital_status') }}"
                            placeholder="{{ __('system.marital_status') }}" fgroup-class="col-md" enable-old-support />
                        <x-adminlte-input name="profissao" label="{{ __('system.profession') }}"
                            placeholder="{{ __('system.profession') }}" fgroup-class="col-md" enable-old-support />
                    </div>

                    <div class="row">
                        <x-adminlte-input title="{{ __('system.document_types') }}" style="cursor:help"
                            name="documento_tipo" label="{{ __('system.document_type') }}"
                            placeholder="{{ __('system.document_types') }}" fgroup-class="col-md" enable-old-support />
                        <x-adminlte-input name="documento_numero" label="{{ __('system.document_number') }}"
                            placeholder="{{ __('system.document_number') }}" fgroup-class="col-md" enable-old-support />
                        <x-adminlte-input name="documento_orgao_emissor" label="{{ __('system.document_issuer-UF') }}"
                            placeholder="{{ __('system.document_issuer-UF') }}" fgroup-class="col-md"
                            enable-old-support />
                    </div>

                </div>

                <div class="area-pessoa-juridica @if (old('pessoa') == 'fisica' || old('pessoa') == '') hidden @endif">
                    <hr>
                    <h4>{{ __('system.client_informations') }}</h4>

                    <div class="row">
                        <x-adminlte-input name="razao_social" label="{{ __('system.corporate_name') }}"
                            placeholder="{{ __('system.corporate_name') }}" fgroup-class="col-md-12"
                            enable-old-support />
                    </div>

                    <div class="row">
                        <x-adminlte-input name="cnpj" label="{{ __('system.cnpj') }}"
                            placeholder="00.000.000/0000-00" fgroup-class="col-md-3" enable-old-support />
                        <x-adminlte-input name="nome_fantasia" label="{{ __('system.trade_name') }}"
                            placeholder="{{ __('system.trade_name') }}" fgroup-class="col-md" enable-old-support />
                    </div>

                    <div class="row">
                        <x-adminlte-input style="cursor:help" title="{{ __('system.legal_natures') }}"
                            name="natureza_juridica" label="{{ __('system.legal_nature') }}"
                            placeholder="{{ __('system.legal_natures') }}" fgroup-class="col-md" enable-old-support />
                        <x-adminlte-input name="inscricao_estadual" label="{{ __('system.state_registration') }}"
                            placeholder="{{ __('system.state_registration') }}" fgroup-class="col-md"
                            enable-old-support />
                    </div>

                    <div class="row">
                        <x-adminlte-input name="socios_ids" label="{{ __('system.partners') }}"
                            placeholder="{{ __('system.partners') }}" fgroup-class="col-md" enable-old-support />
                    </div>

                </div>

                <div class="contacts">

                    <hr>
                    <div class="d-flex justify-content-between w-100%">
                        <h4>{{ __('system.contact_informations') }}</h4>
                        <x-adminlte-button class="bg-success" icon="fas fa-plus" onclick="add_field_contact()" />
                    </div>

                    <div class="row">
                        <div class="col-md">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input custom-control-input-success" type="radio"
                                    id="contact-data1" name="preferencialContact" value="1" enable-old-support
                                    @if (empty(old('preferencialContact')) || old('preferencialContact') == 1) checked @endif />
                                <label for="contact-data1"
                                    class="custom-control-label">{{ __('system.preferencial_contact') }}</label>
                            </div>
                        </div>
                        <x-adminlte-input style="cursor:help" title="{{ __('system.contact_descriptions') }}"
                            name="contacts[contact1][descricao_contato]" label="{{ __('system.contact_description') }}"
                            placeholder="{{ __('system.contact_descriptions') }}" fgroup-class="col-md"
                            enable-old-support />
                        <x-adminlte-input style="cursor:help" title="{{ __('system.contact_datas') }}"
                            name="contacts[contact1][dados_contato]" label="{{ __('system.contact_data') }}"
                            placeholder="{{ __('system.contact_datas') }}" fgroup-class="col-md" enable-old-support />
                    </div>

                    @if (old('contacts'))
                        @for ($o = 2; $o <= count(old('contacts')); $o++)
                            <div class="row">
                                <div class="col-md">
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input custom-control-input-success" type="radio"
                                            id="{{ 'contact-data' . $o }}" name="preferencialContact"
                                            value="{{ $o }}"
                                            @if (old('preferencialContact') == $o) checked @endif />
                                        <label for="{{ 'contact-data' . $o }}"
                                            class="custom-control-label">{{ __('system.preferencial_contact') }}</label>
                                    </div>
                                </div>
                                <x-adminlte-input style="cursor:help" title="{{ __('system.contact_descriptions') }}"
                                    name="{{ 'contacts[contact' . $o . '][descricao_contato]' }}"
                                    label="{{ __('system.contact_description') }}"
                                    placeholder="{{ __('system.contact_descriptions') }}" fgroup-class="col-md"
                                    value="{{ old('contacts[contact' . $o . '][descricao_contato]') }}"
                                    enable-old-support />
                                <x-adminlte-input style="cursor:help" title="{{ __('system.contact_datas') }}"
                                    name="{{ 'contacts[contact' . $o . '][dados_contato]' }}"
                                    label="{{ __('system.contact_data') }}"
                                    placeholder="{{ __('system.contact_datas') }}" fgroup-class="col-md"
                                    value="{{ old('contacts[contact' . $o . '][dados_contato]') }}" enable-old-support />
                            </div>
                        @endfor
                    @endif;

                </div>

                <div class="addresses">

                    <hr>
                    <div class="d-flex justify-content-between w-100%">
                        <h4>{{ __('system.address_informations') }}</h4>
                    </div>

                    <div class="container">
                        <div class="row">
                            <x-adminlte-input class="postal_code_field" name="address[cep]"
                                label="{{ __('system.postal_code') }}" placeholder="{{ __('system.postal_code') }}"
                                fgroup-class="col-md" enable-old-support />
                        </div>
                        <div class="row">
                            <x-adminlte-input class="logradouro_tipo" name="address[logradouro_tipo]"
                                label="{{ __('system.address_type') }}" placeholder="{{ __('system.address_type') }}"
                                fgroup-class="col-md" enable-old-support />
                            <x-adminlte-input class="logradouro_nome" name="address[logradouro_nome]"
                                label="{{ __('system.address_name') }}" placeholder="{{ __('system.address_name') }}"
                                fgroup-class="col-md" enable-old-support />
                            <x-adminlte-input class="numero" name="address[numero]"
                                label="{{ __('system.address_number') }}"
                                placeholder="{{ __('system.address_number') }}" fgroup-class="col-md"
                                enable-old-support />
                        </div>
                        <div class="row">
                            <x-adminlte-input class="complemento" name="address[complemento]"
                                label="{{ __('system.address_complement') }}"
                                placeholder="{{ __('system.address_complement') }}" fgroup-class="col-md"
                                enable-old-support />
                            <x-adminlte-input class="bairro" name="address[bairro]" label="{{ __('system.district') }}"
                                placeholder="{{ __('system.district') }}" fgroup-class="col-md" enable-old-support />
                            <x-adminlte-input class="municipio" name="address[municipio]"
                                label="{{ __('system.city') }}" placeholder="{{ __('system.city') }}"
                                fgroup-class="col-md" enable-old-support />
                            <x-adminlte-input class="estado" name="address[estado]" label="{{ __('system.state') }}"
                                placeholder="{{ __('system.state') }}" fgroup-class="col-md" enable-old-support />
                        </div>
                    </div>

                </div>

                <hr>

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

        const selectPessoa = (e) => {
            document.querySelector('.area-pessoa-fisica').classList.toggle('hidden');
            document.querySelector('.area-pessoa-juridica').classList.toggle('hidden');
        }

        var contactNumber = 2

        function add_field_contact() {
            let newContact =
                `<div class="row">
                <div class="col-md">
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input custom-control-input-success" type="radio"
                            id="contact-data${contactNumber}" name="preferencialContact" value="${contactNumber}">
                        <label for="contact-data${contactNumber}"
                            class="custom-control-label">{{ __('system.preferencial_contact') }}</label>
                    </div>
                </div>
                <x-adminlte-input style="cursor:help" title="{{ __('system.contact_descriptions') }}"
                    name="contacts[contact${contactNumber}][descricao_contato]" label="{{ __('system.contact_description') }}"
                    placeholder="{{ __('system.contact_descriptions') }}" fgroup-class="col-md"
                    enable-old-support />
                <x-adminlte-input style="cursor:help" title="{{ __('system.contact_datas') }}"
                    name="contacts[contact${contactNumber}][dados_contato]" label="{{ __('system.contact_data') }}"
                    placeholder="{{ __('system.contact_datas') }}" fgroup-class="col-md" enable-old-support />
            </div>`;
            const newDiv = document.createElement("div");
            newDiv.innerHTML = newContact;
            document.querySelector(".contacts").appendChild(newDiv);
            contactNumber++;
        }

        document.querySelector('.postal_code_field').addEventListener('keyup', (e) => {
            if (e.target.value.length >= 10) {
                let cep = e.target.value.replace(/[^0-9]/g, '');
                console.log(cep);
                fetch("https://viacep.com.br/ws/" + cep + "/json")
                    .then((response) => response.json())
                    .then((data) => {
                        if (!data.erro) {
                            // console.log('Success:', data);
                            let logradouro_tipo = data.logradouro.split(' ').shift();
                            let logradouro_nome = data.logradouro.replace(logradouro_tipo, '').trim();
                            let complemento = data.complemento
                            let bairro = data.bairro
                            let municipio = data.localidade
                            let estado = data.uf

                            document.querySelector('.logradouro_tipo').value = logradouro_tipo;
                            document.querySelector('.logradouro_nome').value = logradouro_nome;
                            document.querySelector('.complemento').value = complemento;
                            document.querySelector('.bairro').value = bairro;
                            document.querySelector('.municipio').value = municipio;
                            document.querySelector('.estado').value = estado;

                            return;
                        }
                        console.log('Error:', 'CEP não encontrado');
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
            }
        })

        $('input[name=cpf]').mask('000.000.000-00');
        $('input[name=cnpj]').mask('00.000.000/0000-00');
        $('.postal_code_field').mask('00.000-000');
    </script>
@stop
