<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Invoice;
use App\Models\Invoice_material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\Provider;

class ProviderController extends Controller
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
        $providers = Provider::where('company_id', Auth::user()->company_id)->where('inactive', 0)->get();
        return view('providers.index', ['providers' => $providers]);
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
                'name' => ['required', 'max:255', 'unique:providers'],
            ],
        )->validate();

        $id = Provider::insertGetId($data);

        $data['id'] = $id;
        $user_id = Auth::user()->id;
        Helper::saveLog($user_id, array('inclusão: ' => $data), 'Fornecedores', 'Inclusão');

        return redirect()->route("providers.index")->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $provider = Provider::where('company_id', Auth::user()->company_id)
            ->where('inactive', 0)
            ->where('id', $id)
            ->first();

        $invoices = Invoice::select('invoices.*', 'constructions.name as construction_name')
            ->join('constructions', 'constructions.id', 'invoices.construction_id')
            ->where('invoices.company_id', Auth::user()->company_id)
            ->where('invoices.inactive', 0)
            ->where('invoices.provider_id', $id)
            ->get();

        foreach ($invoices as $i) {
            $invoice_materials = Invoice_material::where('invoice_id', $i->id)->get();
            $total = 0;
            foreach ($invoice_materials as $im) {
                $total += $im->qt * $im->unit_value;
            }
            $i->total = $total;
        }

        return view('providers.show', [
            'provider' => $provider,
            'invoices' => $invoices
        ]);
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

        $change_from = Provider::find($id);
        Provider::where('id', $id)->update($data);
        $change_to = Provider::find($id);

        // $changes = array_unique(array_merge($change_from,$change_to), SORT_REGULAR);

        $data['id'] = $id;
        $user_id = Auth::user()->id;
        Helper::saveLog($user_id, array('change_from' => $change_from, 'change_to' => $change_to), 'Fornecedores', 'Alteração');

        return redirect()->route("providers.index")->with('success', 'Cadastro Alterado com sucesso');
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