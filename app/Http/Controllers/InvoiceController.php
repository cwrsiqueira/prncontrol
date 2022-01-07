<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\Invoice;
use App\Models\Invoice_material;

use Helper;

class InvoiceController extends Controller
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
        $invoices = Invoice::where('inactive', 0)->get();
        return view('invoices.index', ['invoices' => $invoices]);
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
                'created_at' => ['required'],
                'invoice_number' => ['required', 'max:255'],
                'invoice_date' => ['required'],
                'provider' => ['required', 'max:255'],
                'materials' => ['required'],
            ],
        )->validate();

        foreach ($data['materials'] as $field) {
            foreach ($field as $key => $item) {
                $invoices[$key][] = $item;
            }
        }

        unset($data['materials']);

        $invoice_id = Invoice::insertGetId($data);

        foreach($invoices as $item) {
            $item['company_id'] = $data['company_id'];
            $item['invoice_id'] = $invoice_id;
            $item['name'] = $item[0];
            $item['unid'] = $item[1];
            $item['qt'] = floatval(Helper::format_value($item[2]));
            $item['unit_value'] = floatval(Helper::format_value($item[3]));
            unset($item[0]);
            unset($item[1]);
            unset($item[2]);
            unset($item[3]);
            $material_id = Invoice_material::insertGetId($item);
        }

        $data['id'] = $invoice_id;
        $user_id = Auth::user()->id;
        Helper::saveLog($user_id, array($data, $invoices), 'add', $data['created_at']);

        return redirect()->route("invoices.index")->with('success', 'Nota cadastrada com sucesso!');
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

        $change_from = Invoice::find($id);
        Invoice::where('id', $id)->update($data);
        $change_for = Invoice::find($id);

        // $changes = array_unique(array_merge($change_from,$change_for), SORT_REGULAR);

        $data['id'] = $id;
        $user_id = Auth::user()->id;
        Helper::saveLog($user_id, array('change_from' => $change_from, 'change_for' => $change_for), 'edit', $data['updated_at']);

        return redirect()->route("invoices.index")->with('success', 'Nota alterada com sucesso');
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
