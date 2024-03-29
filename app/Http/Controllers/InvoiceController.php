<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\Invoice;
use App\Models\Invoice_material;
use App\Models\Construction;
use App\Models\Provider;
use App\Models\Material;

// use Helper;

class InvoiceController extends Controller
{
    /**
     * Class InvoiceController constructor
     */
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
        $invoices = Invoice::select('invoices.*', 'constructions.name as construction_name', 'providers.name as provider_name')
            ->join('constructions', 'constructions.id', 'invoices.construction_id')
            ->join('providers', 'providers.id', 'invoices.provider_id')
            ->where('invoices.company_id', Auth::user()->company_id)
            ->where('invoices.inactive', 0)
            ->orderBy('invoices.invoice_date', 'DESC')
            ->get();

        $constructions = Construction::where('company_id', Auth::user()->company_id)->where('inactive', 0)->get();
        $providers = Provider::where('company_id', Auth::user()->company_id)->where('inactive', 0)->get();
        $materials = Material::where('company_id', Auth::user()->company_id)->where('inactive', 0)->get();

        return view(
            'invoices.index',
            [
                'invoices' => $invoices,
                'constructions' => $constructions,
                'providers' => $providers,
                'materials' => $materials,
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
        $constructions = Construction::where('company_id', Auth::user()->company_id)->where('inactive', 0)->get();
        $providers = Provider::where('company_id', Auth::user()->company_id)->where('inactive', 0)->get();
        $materials = Material::where('company_id', Auth::user()->company_id)->where('inactive', 0)->get();

        return view(
            'invoices.add_invoice',
            [
                'constructions' => $constructions,
                'providers' => $providers,
                'materials' => $materials,
            ]
        );
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
                'construction' => ['required', 'max:255'],
                'invoice_number' => ['required', 'max:255'],
                'invoice_date' => ['required', 'date'],
                'provider' => ['required', 'max:255'],
                'materials' => ['required'],
            ],
        )->validate();

        foreach ($data['materials'] as $field) {
            foreach ($field as $key => $item) {
                $invoices[$key][] = $item;
            }
        }

        $total_invoice = 0;
        foreach ($invoices as $value) {
            $qt = Helper::format_value($value[2]);
            $vlr_unit = Helper::format_value($value[3]);

            $total_invoice += ((floatval($qt) * floatval($vlr_unit)) * 100) / 100;
        }

        $data['invoice_value'] = preg_replace('/[^0-9.,]/', '', $data['invoice_value']);
        $data['invoice_value'] = Helper::format_value($data['invoice_value']);
        $data['invoice_value'] = round(floatval($data['invoice_value']), 2);

        $data['invoice_value_confirmation'] = round($total_invoice, 2);

        Validator::make(
            $data,
            [
                'invoice_value' => ['required', 'same:invoice_value_confirmation'],
                'invoice_value_confirmation' => ['required'],
            ],
            $messages = [
                'same' => 'Valor da Nota e Soma dos Materiais não conferem'
            ]
        )->validate();

        // INCLUIR CONSTRUCTION E PROVIDER SE NÃO EXISTIREM
        // NÃO ESTÁ FUNCIONANDO POIS OS DADOS ESTÃO VINDO DE UM SELECT E NÃO TEM COMO VIR EM BRANCO
        $construction = Construction::select('id')->where('name', $data['construction'])->first();
        $provider = Provider::select('id')->where('name', $data['provider'])->first();

        if (!$construction) {
            $construction['id'] = Construction::insertGetId([
                'company_id' => $data['company_id'],
                'name' => $data['construction'],
            ]);
        }

        if (!$provider) {
            $provider['id'] = Provider::insertGetId([
                'company_id' => $data['company_id'],
                'name' => $data['provider'],
            ]);
        }

        $data['construction_id'] = $construction['id'];
        $data['provider_id'] = $provider['id'];
        // FINAL DE INCLUIR CONSTRUCTION E PROVIDER SE NÃO EXISTIREM

        unset($data['construction']);
        unset($data['provider']);
        unset($data['materials']);
        unset($data['invoice_value']);
        unset($data['invoice_value_confirmation']);

        $invoice_id = Invoice::insertGetId($data);

        foreach ($invoices as $item) {
            $item['company_id'] = $data['company_id'];
            $item['invoice_id'] = $invoice_id;

            $material = Material::select('id')->where('name', $item[0])->first();
            if (!$material) {
                $material['id'] = Material::insertGetId([
                    'company_id' => $data['company_id'],
                    'name' => $item[0],
                ]);
            }

            $item['material_id'] = $material['id'];
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
        Helper::saveLog($user_id, array('change_from' => '', 'change_to' => $data), 'Notas', 'Inclusão');

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
        $invoice = Invoice::select('invoices.*', 'constructions.name as construction_name', 'providers.name as provider_name')
            ->join('constructions', 'constructions.id', 'invoices.construction_id')
            ->join('providers', 'providers.id', 'invoices.provider_id')
            ->where('invoices.id', $id)
            ->first();

        $invoice_materials = Invoice_material::select('invoice_materials.*', 'materials.name as material_name')
            ->leftJoin('materials', 'materials.id', 'invoice_materials.material_id')
            ->where('invoice_materials.company_id', Auth::user()->company_id)
            ->where('invoice_materials.inactive', 0)
            ->where('invoice_materials.invoice_id', $id)
            ->get();

        $total_invoice_value = 0;
        foreach ($invoice_materials as $item) {
            $total_invoice_value += $item['qt'] * $item['unit_value'];
        }

        return view(
            'invoices.view_invoice',
            [
                'invoice' => $invoice,
                'invoice_materials' => $invoice_materials,
                'total_invoice_value' => $total_invoice_value
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $constructions = Construction::where('company_id', Auth::user()->company_id)->where('inactive', 0)->get();
        $providers = Provider::where('company_id', Auth::user()->company_id)->where('inactive', 0)->get();
        $materials = Material::where('company_id', Auth::user()->company_id)->where('inactive', 0)->get();

        $invoice = Invoice::find($id);

        $invoice_materials = Invoice_material::select('invoice_materials.*', 'materials.name as material_name')
            ->leftJoin('materials', 'materials.id', 'invoice_materials.material_id')
            ->where('invoice_materials.company_id', Auth::user()->company_id)
            ->where('invoice_materials.inactive', 0)
            ->where('invoice_materials.invoice_id', $id)
            ->get();

        return view(
            'invoices.edit_invoice',
            [
                'constructions' => $constructions,
                'providers' => $providers,
                'materials' => $materials,
                'invoice' => $invoice,
                'invoice_materials' => $invoice_materials,
            ]
        );
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
                'invoiceId' => ['required'],
                'company_id' => ['required'],
                'construction' => ['required', 'max:255'],
                'invoice_number' => ['required', 'max:255'],
                'invoice_date' => ['required', 'date'],
                'provider' => ['required', 'max:255'],
                'materials' => ['required'],
            ],
        )->validate();

        foreach ($data['materials'] as $field) {
            foreach ($field as $key => $item) {
                $invoices[$key][] = $item;
            }
        }

        $total_invoice = 0;
        foreach ($invoices as $value) {
            $qt = Helper::format_value($value[2]);
            $vlr_unit = Helper::format_value($value[3]);

            $total_invoice += ((floatval($qt) * floatval($vlr_unit)) * 100) / 100;
        }

        $data['invoice_value'] = preg_replace('/[^0-9.,]/', '', $data['invoice_value']);
        $data['invoice_value'] = Helper::format_value($data['invoice_value']);
        $data['invoice_value'] = round(floatval($data['invoice_value']), 2);

        $data['invoice_value_confirmation'] = round($total_invoice, 2);

        Validator::make(
            $data,
            [
                'invoice_value' => ['required', 'same:invoice_value_confirmation'],
                'invoice_value_confirmation' => ['required'],
            ],
            $messages = [
                'same' => 'Valor da Nota e Soma dos Materiais não conferem'
            ]
        )->validate();

        // INCLUIR CONSTRUCTION E PROVIDER SE NÃO EXISTIREM
        // NÃO ESTÁ FUNCIONANDO POIS OS DADOS ESTÃO VINDO DE UM SELECT E NÃO TEM COMO VIR EM BRANCO
        $construction = Construction::select('id')->where('name', $data['construction'])->first();
        $provider = Provider::select('id')->where('name', $data['provider'])->first();

        if (!$construction) {
            $construction['id'] = Construction::insertGetId([
                'company_id' => $data['company_id'],
                'name' => $data['construction'],
            ]);
        }

        if (!$provider) {
            $provider['id'] = Provider::insertGetId([
                'company_id' => $data['company_id'],
                'name' => $data['provider'],
            ]);
        }

        $data['construction_id'] = $construction['id'];
        $data['provider_id'] = $provider['id'];
        // FINAL DE INCLUIR CONSTRUCTION E PROVIDER SE NÃO EXISTIREM

        unset($data['construction']);
        unset($data['provider']);
        unset($data['materials']);
        unset($data['invoiceId']);
        unset($data['invoice_value']);
        unset($data['invoice_value_confirmation']);

        $change_from = Invoice::find($id);
        Invoice::where('id', $id)->update($data);
        $change_to = Invoice::find($id);

        Invoice_material::where('invoice_id', $id)->delete();
        foreach ($invoices as $item) {
            $item['company_id'] = $data['company_id'];
            $item['invoice_id'] = $id;

            $material = Material::select('id')->where('name', $item[0])->first();
            if (!$material) {
                $material['id'] = Material::insertGetId([
                    'company_id' => $data['company_id'],
                    'name' => $item[0],
                ]);
            }

            $item['material_id'] = $material['id'];
            $item['unid'] = $item[1];
            $item['qt'] = floatval(Helper::format_value($item[2]));
            $item['unit_value'] = floatval(Helper::format_value($item[3]));
            unset($item[0]);
            unset($item[1]);
            unset($item[2]);
            unset($item[3]);
            $material_id = Invoice_material::insertGetId($item);
        }

        $data['id'] = $id;
        $user_id = Auth::user()->id;
        Helper::saveLog($user_id, array('change_from' => $change_from, 'change_to' => $change_to), 'Notas', 'Alteração');

        return redirect()->route("invoices.edit", ['invoice' => $id])->with('success', 'Nota alterada com sucesso!');
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


