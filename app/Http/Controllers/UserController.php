<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Permission_group;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('can:menu-cadastro');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('inactive', 0)->where('company_id', Auth::user()->company_id)->get();
        return view(
            'users.index',
            [
                'users' => $users,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Generate Password
     *
     * @param integer $qtyCaraceters
     * @return string
     */
    private function generatePassword($qtyCaraceters = 8): string
    {
        //Letras minúsculas embaralhadas
        $smallLetters = str_shuffle('abcdefghijklmnopqrstuvwxyz');

        //Letras maiúsculas embaralhadas
        $capitalLetters = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');

        //Números aleatórios
        $numbers = (((date('Ymd') / 12) * 24) + mt_rand(800, 9999));
        $numbers .= 1234567890;

        //Caracteres Especiais
        $specialCharacters = str_shuffle('!@#$%*-');

        //Junta tudo
        $characters = $capitalLetters . $smallLetters . $numbers . $specialCharacters;

        //Embaralha e pega apenas a quantidade de caracteres informada no parâmetro
        $password = substr(str_shuffle($characters), 0, $qtyCaraceters);

        //Retorna a senha
        return $password;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('_token');

        Validator::make(
            $data,
            [
                'name' => ['required', 'max:255'],
                'email' => ['required', 'max:255', 'unique:users'],
                'password' => ['max:255', 'required'],
            ],
        )->validate();

        if ($request->hasFile('avatar')) {
            $img = $request->avatar;
            Validator::make(
                $data,
                [
                    'avatar' => 'image'
                ]
            )->validate();
            $path = $img->store('images');
            $data['avatar'] = $path;
        }

        $password = UserController::generatePassword($data['password']);

        $data['password'] = hash::make($password);

        $id = User::insertGetId($data);

        $data['password'] = '***';
        $data['id'] = $id;
        $user_id = Auth::user()->id;
        Helper::saveLog($user_id, array('change_from' => '', 'change_to' => $data), 'users', 'add', $data['created_at']);

        return redirect()->route("users.index")->with('success', 'Atenção!!! Cadastro efetuado com sucesso! A senha cadastrada foi: <br><h3>' . $password . '</h3> copie e cole em algum lugar seguro antes de fechar a janela.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);

        if ($data['password'] !== null) {
            $data['password'] = hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $change_from = User::find($id);
        User::where('id', $id)->update($data);
        $change_to = User::find($id);

        // $changes = array_unique(array_merge($change_from,$change_to), SORT_REGULAR);

        $data['password'] = '***';
        $data['id'] = $id;
        $user_id = Auth::user()->id;
        Helper::saveLog($user_id, array('change_from' => $change_from, 'change_to' => $change_to), 'users', 'edit', $data['updated_at']);

        return redirect()->route("users.index")->with('success', 'Cadastro Alterado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}