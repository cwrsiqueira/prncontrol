<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Mpdf\Mpdf as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Construction;
use App\Models\Material;
use App\Models\Invoice;
use App\Models\Provider;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('can:menu-cadastro');
    }

    public function index() {

        $constructions = Construction::where('company_id', Auth::user()->company_id)->where('inactive', 0)->get();
        $materials = Material::where('company_id', Auth::user()->company_id)->where('inactive', 0)->get();
        $invoices = Invoice::where('company_id', Auth::user()->company_id)->where('inactive', 0)->get();
        $providers = Provider::where('company_id', Auth::user()->company_id)->where('inactive', 0)->get();

        $first_invoice = Invoice::where('company_id', Auth::user()->company_id)->where('inactive', 0)->orderBy('invoice_date', 'asc')->first();
        $last_invoice = Invoice::where('company_id', Auth::user()->company_id)->where('inactive', 0)->orderBy('invoice_date', 'desc')->first();

        return view('reports.index',
            [
                'constructions' => $constructions,
                'materials' => $materials,
                'invoices' => $invoices,
                'providers' => $providers,
                'first_invoice' => $first_invoice,
                'last_invoice' => $last_invoice,
            ]
        );
    }

    public function generate_report(Request $request) {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            if($value === null) {
                unset($data[$key]);
            }
        }

        $init_date = date('Y-m-d', strtotime(explode(' - ', $data['dtRange'])[0]));
        $fin_date = date('Y-m-d', strtotime(explode(' - ', $data['dtRange'])[1]));

        $invoices = Invoice::select(
            'invoices.*',
            'invoice_materials.material_id as id_material',
            'constructions.name as construction_name',
            'providers.name as provider_name',
            'materials.name as material_name',
            'invoice_materials.unid as material_unid',
            'invoice_materials.qt as material_qt',
            'invoice_materials.unit_value as material_unit_value',
            )
        ->join('invoice_materials', 'invoices.id', 'invoice_materials.invoice_id')
        ->join('constructions', 'constructions.id', 'invoices.construction_id')
        ->join('providers', 'providers.id', 'invoices.provider_id')
        ->join('materials', 'materials.id', 'invoice_materials.material_id')
        ->where('invoices.construction_id', 'LIKE', $data['construction_id'] ?? '%')
        ->where('invoices.provider_id', 'LIKE', $data['provider_id'] ?? '%')
        ->where('invoice_materials.material_id', 'LIKE', $data['material_id'] ?? '%')
        ->where('invoices.id', 'LIKE', $data['invoice_id'] ?? '%')
        ->whereBetween('invoice_date', [$init_date, $fin_date])
        ->where('invoices.company_id', Auth::user()->company_id)->where('invoices.inactive', 0)
        ->orderBy('invoice_date', 'DESC')
        ->get();

        $total_materials = 0;
        foreach ($invoices as $key => $value) {
            $total_materials += $value->material_qt;
        }

        $total_cost = 0;
        foreach ($invoices as $key => $value) {
            $total_cost += $value->material_qt * $value->material_unit_value;
        }

        $construction = 'Todas';
        $provider = 'Todos';
        $material = 'Todos';
        $invoice = 'Todas';

        if(isset($data['construction_id'])) {
            $construction_total_cost = 0;
            foreach ($invoices as $key => $value) {
                $construction_total_cost += $value->material_qt * $value->material_unit_value;
            }
            $construction = $invoices[0]->construction_name.' = '.number_format($construction_total_cost, 2, ',', '.');
        }

        if(isset($data['provider_id'])) {
            $provider_total_cost = 0;
            foreach ($invoices as $key => $value) {
                $provider_total_cost += $value->material_qt * $value->material_unit_value;
            }
            $provider = $invoices[0]->provider_name.' = '.number_format($provider_total_cost, 2, ',', '.');
        }

        if(isset($data['material_id'])) {
            $material_total = 0;
            $material_total_cost = 0;
            foreach ($invoices as $key => $value) {
                $material_total += $value->material_qt;
                $material_total_cost += $value->material_qt * $value->material_unit_value;
            }
            $material = $invoices[0]->material_name.' = '.number_format($material_total, 2, ',', '.').' = '.number_format($material_total_cost, 2, ',', '.');
        }

        if(isset($data['invoice_id'])) {
            $invoice_total_cost = 0;
            foreach ($invoices as $key => $value) {
                $invoice_total_cost += $value->material_qt * $value->material_unit_value;
            }
            $invoice = $invoices[0]->invoice_number.' = '.number_format($invoice_total_cost, 2, ',', '.');
        }

        $dtRange = $data['dtRange'] ?? '';

        // Setup a filename
        $documentFileName = "fun.pdf";

        // Create the mPDF document
        $document = new PDF( [
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_header' => '3',
            'margin_top' => '20',
            'margin_bottom' => '20',
            'margin_footer' => '2',
        ]);

        // Set some header informations for output
        $header = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$documentFileName.'"'
        ];

        // Write some simple Content
        $document->WriteHTML(view('reports.generate',
            [
                'invoices' => $invoices,
                'construction' => $construction,
                'provider' => $provider,
                'material' => $material,
                'invoice' => $invoice,
                'dtRange' => $dtRange,
                'total_materials' => $total_materials,
                'total_cost' => $total_cost,
            ]
        ));

        // Use Blade if you want
        //$document->WriteHTML(view('fun.testtemplate'));

        // Save PDF on your public storage
        Storage::disk('public')->put($documentFileName, $document->Output($documentFileName, "S"));

        // Get file back from storage with the give header informations
        return Storage::disk('public')->download($documentFileName, 'Request', $header); //

    }
}
