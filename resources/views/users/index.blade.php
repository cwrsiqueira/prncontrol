@extends('adminlte::page')

@section('title', __('system.users'))

@section('content_header')
    <cw-header-title>
        <h1>Usuários</h1>

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

        <x-adminlte-button label="{{__('system.add_user')}}" data-toggle="modal" data-target="#modalAdd" class="bg-success" icon="fas fa-plus" id="openModalAdd"/>
        <x-adminlte-button data-toggle="modal" data-target="#modalEdit" id="openModalEdit" style="display:none;"/>
    </cw-header-title>
@stop

@section('content')
    <x-adminlte-card theme="success" theme-mode="outline" icon="fas fa-lg fa-users">
        {{-- Setup data for datatables --}}
        @php
        $system_edit = __('system.edit');
        $system_delete = __('system.delete');
        $system_details = __('system.details');
        $heads = [
            'ID',
            __('system.name'),
            __('system.email'),
            ['label' => __('system.actions'), 'no-export' => true, 'width' => 5],
        ];
        $data = [];
        foreach ($users as $key => $user) {
            if (Auth::user()->permission_group_id === 1 || $user['permission_group_id'] !== 1) {
                $data[$key]['id'] = $user['id'];
                $data[$key]['name'] = $user['name'];
                $data[$key]['email'] = $user['email'];
                $data[$key]['actions'] =
                "<nobr>
                    <button class='btn btn-xs btn-default text-primary mx-1 shadow btnAction edit' title='".$system_edit."' data-id='".$user["id"]."'>
                        <i class='fa fa-lg fa-fw fa-pen'></i>
                    </button>
                    <button class='btn btn-xs btn-default text-danger mx-1 shadow btnAction delete' title='".$system_delete."' data-id='".$user["id"]."'>
                        <i class='fa fa-lg fa-fw fa-trash'></i>
                    </button>
                    <button class='btn btn-xs btn-default text-teal mx-1 shadow btnAction details' title='".$system_details."' data-id='".$user["id"]."'>
                        <i class='fa fa-lg fa-fw fa-eye'></i>
                    </button>
                </nobr>";
            }
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
        <x-adminlte-modal id="modalAdd" title=" {{ __('system.add_user') }}" size="lg" theme="success" icon="fas fa-user" v-centered static-backdrop scrollable>
            <form action="{{route('users.store')}}" method="post" enctype="multipart/form-data" id="form_add_user">
                @csrf
                <input type="hidden" name="company_id" value="{{Auth::user()->company_id}}">
                <input type="hidden" name="password" value="8">
                <input type="hidden" name="inactive" value="0">
                <input type="hidden" name="avatar" value="images/default.png">
                <input type="hidden" name="created_at" value="{{date('Y-m-d H:m:i')}}">
                <input type="hidden" name="permission_group_id" value="4">

                <div class="avatar_field_area">
                    <img src="images/default.png" id="preview_avatar">
                    <div class="avatar_field_title">
                        <span>{{__('system.enter_avatar_insert')}}</span>
                        <i class="far fa-hand-pointer"></i>
                    </div>
                    <input type="file" id="input_avatar" type="file" accept="image/*" name="avatar" style="display: none;">
                </div>

                <div class="row">
                    <x-adminlte-input name="name" label="{{__('system.name')}}" placeholder="{{__('system.enter_name')}}" fgroup-class="col-md-12" enable-old-support/>
                </div>
                <div class="row">
                    <x-adminlte-input type="email" name="email" label="{{__('system.email')}}" placeholder="{{__('system.enter_email')}}" fgroup-class="col-md-12" enable-old-support/>
                </div>
            <x-slot name="footerSlot">
                <x-adminlte-button type="submit" class="mr-auto" theme="success" label="Salvar"/>
            </form>
                <x-adminlte-button theme="danger" label="Fechar" data-dismiss="modal"/>
            </x-slot>
        </x-adminlte-modal>

        {{-- Modal EDIT --}}
        <x-adminlte-modal id="modalEdit" title=" {{ __('system.edit_user') }}" size="lg" theme="success" icon="fas fa-user" v-centered static-backdrop scrollable>
            <form id="form_edit_user" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="company_id" value="{{Auth::user()->company_id}}">
                <input type="hidden" name="password" value="8">
                <input type="hidden" name="inactive" value="0">
                <input type="hidden" name="avatar" value="images/default.png">
                <input type="hidden" name="created_at" value="{{date('Y-m-d H:m:i')}}">
                <input type="hidden" name="permission_group_id" value="4">

                <div class="avatar_field_area">
                    <img src="images/default.png" id="preview_avatar">
                    <div class="avatar_field_title">
                        <span>{{__('system.enter_avatar_insert')}}</span>
                        <i class="far fa-hand-pointer"></i>
                    </div>
                    <input type="file" id="input_avatar" type="file" accept="image/*" name="avatar" style="display: none;">
                </div>

                <div class="row">
                    <x-adminlte-input type="checkbox" name="password" label="{{__('system.generate_new_password')}}" fgroup-class="col-md-12" enable-old-support/>
                </div>

                <div class="row">
                    <x-adminlte-input name="name" label="{{__('system.name')}}" placeholder="{{__('system.enter_name')}}" fgroup-class="col-md-12" enable-old-support/>
                </div>
                <div class="row">
                    <x-adminlte-input type="email" name="email" label="{{__('system.email')}}" placeholder="{{__('system.enter_email')}}" fgroup-class="col-md-12" enable-old-support/>
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
                let user

                var ajax = new XMLHttpRequest();
                ajax.open("GET", "{{route('getUser')}}/?id="+id, true);
                ajax.send();
                ajax.onreadystatechange = function() {
                    if (ajax.readyState == 4 && ajax.status == 200) {
                        user = JSON.parse(ajax.responseText)
                        switch (action) {
                            case 'edit':
                                edit_user(user)
                                break;
                            case 'delete':
                                delete_user(user)
                                break;
                            case 'details':
                                show_user(user)
                                break;
                        }
                    }
                }
            })
        })

        const edit_user = (user) => {
            let route_edit = "{{route('users.update', ['user' => 0])}}"
            document.querySelector('#form_edit_user').setAttribute('action', route_edit.replace('0', user))
            document.querySelector('#openModalEdit').click()
        }
        const delete_user = (user) => {
            if(confirm('system.delete_confirm')) {
                return false;
            }
            alert('system.user_deleted')
        }

        document.querySelector('.avatar_field_area').addEventListener('click', ()=>{
            document.querySelector('#input_avatar').click()
        })

        document.querySelector('#input_avatar').addEventListener('change', ()=>{
            if (document.querySelector('#input_avatar').files && document.querySelector('#input_avatar').files[0]) {
                console.log(document.querySelector('#input_avatar').files)
                var file = new FileReader();
                file.onload = function(e) {
                    document.getElementById("preview_avatar").src = e.target.result;
                };
                file.readAsDataURL(document.querySelector('#input_avatar').files[0]);
                document.querySelector('.avatar_field_title span').innerHTML = "{{__('system.enter_avatar_change')}}"
            }
        })
    </script>
@stop