@extends('adminlte::page')

@section('title', 'PRNCONTROL | ' . __('system.edit_client'))

@section('content_header')
    <cw-header-title>
        <h1><i class="fas fa-user"></i> {{ __('system.edit_client') }}</h1>
        <div>
            <a href="{{ route('clients.index') }}">{{ __('system.clients') }}</a> | {{ __('system.edit_client') }}
        </div>
    </cw-header-title>
@stop

@section('content')
    <x-adminlte-card theme="success" theme-mode="outline">
        <form action="{{ route('clients.update', ['client' => $client['id']]) }}" method="post" enctype="multipart/form-data"
            id="form_add_client">
            @method('PUT')
            @csrf
            <input type="hidden" name="company_id" value="{{ Auth::user()->company_id }}">

            <div class="row">
                <x-adminlte-select onchange="selectPessoa(this)" name="pessoa" enable-old-support>
                    <x-adminlte-options :selected="$client['pessoa']" :options="['fisica' => 'Pessoa Física', 'juridica' => 'Pessoa Jurídica']" />
                </x-adminlte-select>
            </div>

            <div class="area-pessoa-fisica @if ((old('pessoa') == 'fisica' || old('pessoa') == '') && $client['pessoa'] == 'fisica') @else hidden @endif">
                <hr>
                <h4>{{ __('system.client_informations') }}</h4>

                <div class="row">
                    <x-adminlte-input name="nome" value="{{ $client['nome_razao_social'] }}"
                        label="{{ __('system.name') }}" placeholder="{{ __('system.enter_name') }}" fgroup-class="col-md-12"
                        enable-old-support />
                </div>

                <div class="row">
                    <x-adminlte-input name="cpf" value="{{ $client['cpf'] }}" label="{{ __('system.cpf') }}"
                        placeholder="000.000.000-00" fgroup-class="col-md-12" enable-old-support />
                </div>

                <div class="row">
                    <x-adminlte-input name="nacionalidade" value="{{ $client['nacionalidade'] }}"
                        label="{{ __('system.nationality') }}" placeholder="{{ __('system.nationality') }}"
                        fgroup-class="col-md" enable-old-support />
                    <x-adminlte-input name="estado_civil" value="{{ $client['estado_civil'] }}"
                        label="{{ __('system.marital_status') }}" placeholder="{{ __('system.marital_status') }}"
                        fgroup-class="col-md" enable-old-support />
                    <x-adminlte-input name="profissao" value="{{ $client['profissao'] }}"
                        label="{{ __('system.profession') }}" placeholder="{{ __('system.profession') }}"
                        fgroup-class="col-md" enable-old-support />
                </div>

                <div class="row">
                    <x-adminlte-input title="{{ __('system.document_types') }}" style="cursor:help" name="documento_tipo"
                        value="{{ $client['documento_tipo'] }}" label="{{ __('system.document_type') }}"
                        placeholder="{{ __('system.document_types') }}" fgroup-class="col-md" enable-old-support />
                    <x-adminlte-input name="documento_numero" value="{{ $client['documento_numero'] }}"
                        label="{{ __('system.document_number') }}" placeholder="{{ __('system.document_number') }}"
                        fgroup-class="col-md" enable-old-support />
                    <x-adminlte-input name="documento_orgao_emissor" value="{{ $client['documento_orgao_emissor'] }}"
                        label="{{ __('system.document_issuer-UF') }}" placeholder="{{ __('system.document_issuer-UF') }}"
                        fgroup-class="col-md" enable-old-support />
                </div>

            </div>

            <div class="area-pessoa-juridica @if ((old('pessoa') == 'fisica' || old('pessoa') == '') && $client['pessoa'] == 'fisica') hidden @endif">
                <hr>
                <h4>{{ __('system.client_informations') }}</h4>

                <div class="row">
                    <x-adminlte-input name="razao_social" value="{{ $client['nome_razao_social'] }}"
                        label="{{ __('system.corporate_name') }}" placeholder="{{ __('system.corporate_name') }}"
                        fgroup-class="col-md-12" enable-old-support />
                </div>

                <div class="row">
                    <x-adminlte-input name="cnpj" value="{{ $client['cnpj'] }}" label="{{ __('system.cnpj') }}"
                        placeholder="00.000.000/0000-00" fgroup-class="col-md-3" enable-old-support />
                    <x-adminlte-input name="nome_fantasia" value="{{ $client['nome_fantasia'] }}"
                        label="{{ __('system.trade_name') }}" placeholder="{{ __('system.trade_name') }}"
                        fgroup-class="col-md" enable-old-support />
                </div>

                <div class="row">
                    <x-adminlte-input style="cursor:help" title="{{ __('system.legal_natures') }}" name="natureza_juridica"
                        value="{{ $client['natureza_juridica'] }}" label="{{ __('system.legal_nature') }}"
                        placeholder="{{ __('system.legal_natures') }}" fgroup-class="col-md" enable-old-support />
                    <x-adminlte-input name="inscricao_estadual" value="{{ $client['inscricao_estadual'] }}"
                        label="{{ __('system.state_registration') }}" placeholder="{{ __('system.state_registration') }}"
                        fgroup-class="col-md" enable-old-support />
                </div>

                <div class="row">
                    <x-adminlte-input name="socios_ids" value="{{ $client['socios_ids'] }}"
                        label="{{ __('system.partners') }}" placeholder="{{ __('system.partners') }}"
                        fgroup-class="col-md" enable-old-support />
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
                            <input style="cursor:pointer" class="custom-control-input custom-control-input-success"
                                type="radio" id="contact-data1" name="preferencialContact" value="1"
                                enable-old-support @if (
                                    ($client['contacts'][0]['preferencial'] == 1 && empty(old('preferencialContact'))) ||
                                        old('preferencialContact') == 1) checked @endif />
                            <label style="cursor:pointer" for="contact-data1"
                                class="custom-control-label">{{ __('system.preferencial_contact') }}</label>
                        </div>
                    </div>
                    <x-adminlte-input style="cursor:help" title="{{ __('system.contact_descriptions') }}"
                        name="contacts[contact1][descricao_contato]"
                        value="{{ $client['contacts'][0]['descricao_contato'] }}"
                        label="{{ __('system.contact_description') }}"
                        placeholder="{{ __('system.contact_descriptions') }}" fgroup-class="col-md" enable-old-support />
                    <x-adminlte-input style="cursor:help" title="{{ __('system.contact_datas') }}"
                        name="contacts[contact1][dados_contato]" value="{{ $client['contacts'][0]['dados_contato'] }}"
                        label="{{ __('system.contact_data') }}" placeholder="{{ __('system.contact_datas') }}"
                        fgroup-class="col-md" enable-old-support />
                </div>

                @if (old('contacts'))
                    @for ($o = 2; $o <= count(old('contacts')); $o++)
                        <div class="row">
                            <div class="col-md">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input custom-control-input-success" type="radio"
                                        id="{{ 'contact-data' . $o }}" name="preferencialContact"
                                        value="{{ $o }}" @if (old('preferencialContact') == $o) checked @endif />
                                    <label for="{{ 'contact-data' . $o }}"
                                        class="custom-control-label">{{ __('system.preferencial_contact') }}</label>
                                </div>
                            </div>
                            <x-adminlte-input style="cursor:help" title="{{ __('system.contact_descriptions') }}"
                                name="{{ 'contacts[contact' . $o . '][descricao_contato]' }}"
                                label="{{ __('system.contact_description') }}"
                                placeholder="{{ __('system.contact_descriptions') }}" fgroup-class="col-md"
                                value="{{ old('contacts[contact' . $o . '][descricao_contato]') }}" enable-old-support />
                            <x-adminlte-input style="cursor:help" title="{{ __('system.contact_datas') }}"
                                name="{{ 'contacts[contact' . $o . '][dados_contato]' }}"
                                label="{{ __('system.contact_data') }}" placeholder="{{ __('system.contact_datas') }}"
                                fgroup-class="col-md" value="{{ old('contacts[contact' . $o . '][dados_contato]') }}"
                                enable-old-support />
                        </div>
                    @endfor
                @elseif ($client['contacts'])
                    @for ($o = 2; $o <= count($client['contacts']); $o++)
                        <div class="row">
                            <div class="col-md">
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input custom-control-input-success" type="radio"
                                        id="{{ 'contact-data' . $o }}" name="preferencialContact"
                                        value="{{ $o }}"
                                        @if ($client['contacts'][$o - 1]['preferencial']) == 1) checked @endif />
                                    <label for="{{ 'contact-data' . $o }}"
                                        class="custom-control-label">{{ __('system.preferencial_contact') }}</label>
                                </div>
                            </div>
                            <x-adminlte-input style="cursor:help" title="{{ __('system.contact_descriptions') }}"
                                name="{{ 'contacts[contact' . $o . '][descricao_contato]' }}"
                                label="{{ __('system.contact_description') }}"
                                placeholder="{{ __('system.contact_descriptions') }}" fgroup-class="col-md"
                                value="{{ old('contacts[contact' . $o . '][descricao_contato]') ?? $client['contacts'][$o - 1]['descricao_contato'] }}"
                                enable-old-support />
                            <x-adminlte-input style="cursor:help" title="{{ __('system.contact_datas') }}"
                                name="{{ 'contacts[contact' . $o . '][dados_contato]' }}"
                                label="{{ __('system.contact_data') }}" placeholder="{{ __('system.contact_datas') }}"
                                fgroup-class="col-md"
                                value="{{ old('contacts[contact' . $o . '][dados_contato]') ?? $client['contacts'][$o - 1]['dados_contato'] }}"
                                enable-old-support />
                        </div>
                    @endfor
                @endif

            </div>

            <div class="addresses">

                <hr>
                <div class="d-flex justify-content-between w-100%">
                    <h4>{{ __('system.address_informations') }}</h4>
                </div>

                <div class="container">
                    <div class="row">
                        <x-adminlte-input class="postal_code_field" name="address[cep]"
                            value="{{ $client['addresses'][0]['cep'] }}" label="{{ __('system.postal_code') }}"
                            placeholder="{{ __('system.postal_code') }}" fgroup-class="col-md" enable-old-support />
                    </div>
                    <div class="row">
                        <x-adminlte-input class="logradouro_tipo" name="address[logradouro_tipo]"
                            value="{{ $client['addresses'][0]['logradouro_tipo'] }}"
                            label="{{ __('system.address_type') }}"
                            placeholder="{{ __('system.address_type_examples') }}" fgroup-class="col-md"
                            enable-old-support />
                        <x-adminlte-input class="logradouro_nome" name="address[logradouro_nome]"
                            value="{{ $client['addresses'][0]['logradouro_nome'] }}"
                            label="{{ __('system.address_name') }}" placeholder="{{ __('system.address_name') }}"
                            fgroup-class="col-md" enable-old-support />
                        <x-adminlte-input class="numero" name="address[numero]"
                            value="{{ $client['addresses'][0]['numero'] }}" label="{{ __('system.address_number') }}"
                            placeholder="{{ __('system.address_number') }}" fgroup-class="col-md" enable-old-support />
                    </div>
                    <div class="row">
                        <x-adminlte-input class="complemento" name="address[complemento]"
                            value="{{ $client['addresses'][0]['complemento'] }}"
                            label="{{ __('system.address_complement') }}"
                            placeholder="{{ __('system.address_complement') }}" fgroup-class="col-md"
                            enable-old-support />
                        <x-adminlte-input class="bairro" name="address[bairro]"
                            value="{{ $client['addresses'][0]['bairro'] }}" label="{{ __('system.district') }}"
                            placeholder="{{ __('system.district') }}" fgroup-class="col-md" enable-old-support />
                        <x-adminlte-input class="municipio" name="address[municipio]"
                            value="{{ $client['addresses'][0]['municipio'] }}" label="{{ __('system.city') }}"
                            placeholder="{{ __('system.city') }}" fgroup-class="col-md" enable-old-support />
                        <x-adminlte-input class="estado" name="address[estado]"
                            value="{{ $client['addresses'][0]['estado'] }}" label="{{ __('system.state') }}"
                            placeholder="{{ __('system.state') }}" fgroup-class="col-md" enable-old-support />
                    </div>
                </div>

            </div>

            <hr>

            <div class="row">
                <x-adminlte-input name="obs" value="{{ $client['obs'] }}" label="{{ __('system.obs') }}"
                    placeholder="{{ __('system.enter_obs') }}" fgroup-class="col-md-12" enable-old-support />
            </div>
            <x-slot name="footerSlot">
                <x-adminlte-button type="submit" class="mr-auto" theme="success" label="Salvar" />
            </x-slot>

            <hr>

            <div class="area-buttons">
                <x-adminlte-button type="submit" class="d-flex mr-auto" theme="success" label="Salvar" />
                <a href="#" onclick="window.history.back()" class="btn btn-warning ml-auto">Voltar</a>
                <a href="{{ route('clients.index') }}" class="btn btn-danger ml-auto">Sair</a>
            </div>

        </form>
    </x-adminlte-card>
@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop

@section('js')
    <script>
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
                        <input style="cursor:pointer" class="custom-control-input custom-control-input-success" type="radio"
                            id="contact-data${contactNumber}" name="preferencialContact" value="${contactNumber}">
                        <label style="cursor:pointer" for="contact-data${contactNumber}"
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
