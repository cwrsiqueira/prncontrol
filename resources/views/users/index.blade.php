@extends('adminlte::page')

@section('title', 'Usuários')

@section('content_header')
    <cw-header-title>
        <h1>Usuários</h1>

        @if(count($errors) > 0)

            <input type="hidden" id="errors" value="{{$errors}}">

            <x-adminlte-modal id="modalErrors" title="Atenção!" size="lg" theme="danger" icon="fas fa-ban" v-centered static-backdrop scrollable>
                <ul>
                    @foreach ($errors as $error)
                        <li>{{$errors}}</li>
                    @endforeach
                </ul>
                <x-slot name="footerSlot">
                    <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal" data-toggle="modal" data-target="#modalAdd"/>
                </x-slot>
            </x-adminlte-modal>

            <x-adminlte-button label="Open Modal" data-toggle="modal" data-target="#modalErrors" id="openModalErrors" style="display:none;"/>

        @endif

        <x-adminlte-button label="Adicionar Usuário" data-toggle="modal" data-target="#modalAdd" class="bg-success" icon="fas fa-plus"/>
    </cw-header-title>
@stop

@section('content')
    <x-adminlte-card theme="success" theme-mode="outline" icon="fas fa-lg fa-users">
        {{-- Setup data for datatables --}}
        @php
        $heads = [
            'ID',
            'Name',
            'Email',
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];
        $data = [];
        foreach ($users as $key => $user) {
            if (Auth::user()->permission_group_id === 1 || $user['permission_group_id'] !== 1) {
                $data[$key]['id'] = $user['id'];
                $data[$key]['name'] = $user['name'];
                $data[$key]['email'] = $user['email'];
                $data[$key]['actions'] =
                '<nobr>
                    <button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" data-id="'.$user['id'].'">
                        <i class="fa fa-lg fa-fw fa-pen"></i>
                    </button>
                    <button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" data-id="'.$user['id'].'">
                        <i class="fa fa-lg fa-fw fa-trash"></i>
                    </button>
                    <button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details" data-id="'.$user['id'].'">
                        <i class="fa fa-lg fa-fw fa-eye"></i>
                    </button>
                </nobr>';
            }
        }

        $config = [
            'data' => $data,
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, ['orderable' => false]],
        ];
        @endphp
        {{-- Minimal example / fill data using the component slot --}}
        <x-adminlte-datatable id="table1" :heads="$heads">
            @foreach($config['data'] as $row)
                <tr>
                    @foreach($row as $cell)
                        <td>{!! $cell !!}</td>
                    @endforeach
                </tr>
            @endforeach
        </x-adminlte-datatable>

        {{-- Custom --}}
        <x-adminlte-modal id="modalAdd" title="Adicionar Usuário" size="lg" theme="success" icon="fas fa-user" v-centered static-backdrop scrollable>
            <form action="{{route('users.store')}}" method="post">
                @csrf
                <div class="row">
                    <x-adminlte-input name="name" label="Nome" placeholder="Digite o nome" fgroup-class="col-md-6" enable-old-support/>
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
@stop

@section('js')
    <script>
        window.onload = () => {
            let errors = document.querySelector('#errors').value
            if(errors !== '') {
                document.querySelector('#openModalErrors').click();
            }
        }
    </script>
@stop
