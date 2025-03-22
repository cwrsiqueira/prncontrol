@extends('adminlte::page')

@section('title', 'PRNCONTROL | Relatório')

@section('content_header')
    <cw-header-title>
        <h1><i class="fas fa-boxes"></i> {{ __('system.materials') }}</h1>

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

        <x-adminlte-button label="{{ __('system.add_material') }}" data-toggle="modal" data-target="#modalAdd"
            class="bg-success" icon="fas fa-plus" id="openModalAdd" />
        <x-adminlte-button data-toggle="modal" data-target="#modalEdit" id="openModalEdit" style="display:none;" />
    </cw-header-title>
@stop

@section('content')
    <x-adminlte-card theme="success" theme-mode="outline">
        @php
            $obra = $reportData['construction'];
            $material = $reportData['material'];
            $categoria = $reportData['category'];
            $periodo = $reportData['dtRange'];
            $total_materials = $reportData['total_materials'];
            $total_cost = number_format($reportData['total_cost'], 2, ',', '.');

            $heads = ['NotaNr.', 'Data', 'Material', 'Categoria', 'Unid', 'Quant', 'Vlr Unit', 'Vlr Total'];
            $data = [];
            foreach ($reportData['invoices'] as $key => $value) {
                $invoice_date = !empty($value['invoice_date']) ? date('d/m/Y', strtotime($value['invoice_date'])) : '';
                $material_qt = $value['material_qt'] ?? $value['total_qt'];
                $material_unit_value = $value['material_unit_value'] ?? 1;
                $total_value = $value['items'] ? $value['total_cost'] : $material_qt * $material_unit_value;

                $data[$key][] = $value['invoice_number'] ?? '';
                $data[$key][] = $invoice_date ?? '';
                $data[$key][] = $value['material_name'] ?? '';
                $data[$key][] = $value['category_name'] ?? '';
                $data[$key][] = $value['material_unid'] ?? '';
                $data[$key][] = number_format($material_qt, 0) ?? '';
                $data[$key][] = number_format($material_unit_value, 2) ?? '';
                $data[$key][] = number_format($total_value, 2) ?? '';
            }
            $config = [
                'data' => $data,
                'order' => [[1, 'asc']],
                'columns' => [null, null, null, null, null, null, null, null],
                'buttons' => [
                    [
                        'extend' => 'pdfHtml5',
                        'title' => 'Relatório de Gastos com Construção',
                        'messageTop' => "Obra: $obra - Material: $material \n
                            Período: $periodo \n
                            Total de materiais: $total_materials - Valor Total: $total_cost",
                    ],
                    [
                        'extend' => 'excelHtml5',
                        'title' => 'Relatório de Gastos com Construção',
                        'messageTop' => "Obra: $obra - Material: $material - Período: $periodo - Total de materiais: $total_materials - Valor Total: $total_cost",
                    ],
                    [
                        'extend' => 'print',
                        'title' => 'Relatório de Gastos com Construção',
                        'messageTop' => "Obra: $obra - Material: $material - Período: $periodo - Total de materiais: $total_materials - Valor Total: $total_cost",
                    ],
                ],
            ];
        @endphp

        <div class="row">
            <div class="col-sm-2">
                @php
                    $dtRange = explode(' - ', $reportData['dtRange']);
                    $dtRange = str_replace('-', '/', $dtRange[0]) . ' a ' . str_replace('-', '/', $dtRange[1]);
                @endphp
                <x-adminlte-card theme="secondary" theme-mode="outline" title="Relatório Filtrado" icon="fas fa-filter">

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Obra:</strong> <br> {{ $reportData['construction'] ?? 'Todas' }}
                        </li>
                        {{-- <li class="list-group-item">
                            <strong>Fornecedor:</strong> <br> {{ $reportData['provider'] ?? 'Todos' }}
                        </li> --}}
                        <li class="list-group-item">
                            <strong>Material:</strong> <br> {{ $reportData['material'] ?? 'Todos' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Categoria:</strong> <br> {{ $reportData['category'] ?? 'Todas' }}
                        </li>
                        {{-- <li class="list-group-item">
                            <strong>Nota Fiscal:</strong> <br> {{ $reportData['invoice'] ?? 'Todas' }}
                        </li> --}}
                        {{-- <li class="list-group-item">
                            <strong>Quant. materiais:</strong> <br> {{ $reportData['total_materials'] ?? 0 }}
                        </li> --}}
                        {{-- <li class="list-group-item">
                            <strong>Valor materiais:</strong> <br> R$
                            {{ number_format($reportData['total_cost'] ?? 0, 2, ',', '.') }}
                        </li> --}}
                        <li class="list-group-item">
                            <strong>Período:</strong> <br> {{ $dtRange ?? 'Não definido' }}
                        </li>
                    </ul>

                </x-adminlte-card>
            </div>
            <div class="col-sm-10">
                {{-- Compressed with style options / fill data using the plugin config --}}
                <x-adminlte-datatable id="table2" :heads="$heads" head-theme="dark" :config="$config" striped hoverable
                    bordered compressed withButtons />
            </div>
        </div>

        {{-- Modal ADD --}}
        <x-adminlte-modal id="modalAdd" title=" {{ __('system.add_material') }}" size="lg" theme="success"
            icon="fas fa-boxes" v-centered static-backdrop scrollable>
            <form action="{{ route('materials.store') }}" method="post" enctype="multipart/form-data"
                id="form_add_material">
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
        <x-adminlte-modal id="modalEdit" title=" {{ __('system.edit_material') }}" size="lg" theme="success"
            icon="fas fa-boxes" v-centered static-backdrop scrollable>
            <form id="form_edit_material" method="post" enctype="multipart/form-data">
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
