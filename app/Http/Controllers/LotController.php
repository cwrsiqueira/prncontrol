<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\Lot;

use Helper;

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
                'name' => ['required', 'max:255'],
            ],
        )->validate();

        $id = Lot::insertGetId($data);

        $data['id'] = $id;
        $user_id = Auth::user()->id;
        Helper::saveLog($user_id, array($data), 'add', $data['created_at']);

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

        $change_from = Lot::find($id);
        Lot::where('id', $id)->update($data);
        $change_for = Lot::find($id);

        // $changes = array_unique(array_merge($change_from,$change_for), SORT_REGULAR);

        $data['id'] = $id;
        $user_id = Auth::user()->id;
        Helper::saveLog($user_id, array('change_from' => $change_from, 'change_for' => $change_for), 'edit', $data['updated_at']);

        return redirect()->route("lots.index")->with('success', 'Lote alterado com sucesso');
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
