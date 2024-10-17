<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\Lot;

class LotController extends Controller
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
        $lots = Lot::where('inactive', 0)->get();

        foreach($lots as $lot){
            $lot['movimento'] = 'Última movimentação';
        }
        return view('lots.index', ['lots' => $lots]);
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
                'company_id' => ['required'],
                'loteamento' => ['required'],
                'quadra' => ['required'],
                'lote' => ['required'],
            ],
        )->validate();

        $data['aprovacao_data'] = ($data['aprovacao_data']) ? implode('-', array_reverse(explode('/', $data['aprovacao_data']))) : null;
        $data['valor'] = (Helper::format_value($data['valor']));

        $id = Lot::insertGetId($data);

        $data['id'] = $id;
        $user_id = Auth::user()->id;
        Helper::saveLog($user_id, array('inclusão: ' => $data), 'Lotes', 'Inclusão');

        return redirect()->route("lots.index")->with('success', 'Lote cadastrado com sucesso!');
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

        Validator::make(
            $data,
            [
                'company_id' => ['required'],
                'loteamento' => ['required'],
                'quadra' => ['required'],
                'lote' => ['required'],
            ],
        )->validate();

        $data['aprovacao_data'] = ($data['aprovacao_data']) ? implode('-', array_reverse(explode('/', $data['aprovacao_data']))) : null;
        $data['valor'] = (Helper::format_value($data['valor']));

        Lot::find($id)->update($data);

        $user_id = Auth::user()->id;
        Helper::saveLog($user_id, array('alteração: ' => $data), 'Lotes', 'Alteração');

        return redirect()->route("lots.index")->with('success', 'Lote editado com sucesso!');
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
