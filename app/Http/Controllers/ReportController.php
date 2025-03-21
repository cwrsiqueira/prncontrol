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
use App\Models\Material_category;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('can:menu-cadastro');
    }

    public function index()
    {
        $constructions = Construction::where('company_id', Auth::user()->company_id)->where('inactive', 0)->get();
        $materials = Material::where('company_id', Auth::user()->company_id)->where('inactive', 0)->get();
        $invoices = Invoice::where('company_id', Auth::user()->company_id)->where('inactive', 0)->get();
        $providers = Provider::where('company_id', Auth::user()->company_id)->where('inactive', 0)->get();
        $categories = Material_category::where('inactive', 0)->get();

        $first_invoice = Invoice::where('company_id', Auth::user()->company_id)->where('inactive', 0)->orderBy('invoice_date', 'asc')->first();
        $last_invoice = Invoice::where('company_id', Auth::user()->company_id)->where('inactive', 0)->orderBy('invoice_date', 'desc')->first();

        return view(
            'reports.index',
            [
                'constructions' => $constructions,
                'materials' => $materials,
                'categories' => $categories,
                'invoices' => $invoices,
                'providers' => $providers,
                'first_invoice' => $first_invoice,
                'last_invoice' => $last_invoice,
            ]
        );
    }

    public function generate_report(Request $request)
    {
        $data = $request->except('_token');

        // Remove valores nulos
        $data = array_filter($data, fn($value) => !is_null($value));

        // Converte o período de datas
        $dateRange = explode(' - ', $data['dtRange'] ?? '');
        $init_date = date('Y-m-d', strtotime($dateRange[0] ?? ''));
        $fin_date = date('Y-m-d', strtotime($dateRange[1] ?? ''));

        // Consulta as invoices no banco de dados
        $invoices = Invoice::select(
            'invoices.*',
            'invoice_materials.material_id as id_material',
            'constructions.name as construction_name',
            'providers.name as provider_name',
            'materials.name as material_name',
            'material_categories.name as category_name',
            'invoice_materials.unid as material_unid',
            'invoice_materials.qt as material_qt',
            'invoice_materials.unit_value as material_unit_value'
        )
            ->join('invoice_materials', 'invoices.id', 'invoice_materials.invoice_id')
            ->join('constructions', 'constructions.id', 'invoices.construction_id')
            ->join('providers', 'providers.id', 'invoices.provider_id')
            ->join('materials', 'materials.id', 'invoice_materials.material_id')
            ->leftJoin('material_categories', 'material_categories.id', 'materials.category_id') // Alterado para LEFT JOIN
            ->where('invoices.company_id', Auth::user()->company_id)
            ->where('invoices.inactive', 0)
            ->whereBetween('invoice_date', [$init_date, $fin_date]);

        // Aplica filtros se estiverem preenchidos
        foreach (['construction_id', 'provider_id', 'material_id', 'invoice_id', 'category_id'] as $filter) {
            if (!empty($data[$filter])) {
                switch($filter) {                     
                    case 'category_id':                         
                        $invoices->where('materials.category_id', $data[$filter]);
                        break;
                    case 'material_id':
                        $invoices->where('materials.id', $data[$filter]);
                        break;
                    default:
                        $invoices->where("invoices.$filter", $data[$filter]);
                }
            }
        }

        // Aplicar filtro por categoria
        if (!empty($data['category_id'])) {
            $invoices->where('materials.category_id', $data['category_id']);
        }

        $invoices = $invoices->orderBy('invoice_date', 'asc')->get();

        // Se não houver dados, inicializa valores padrão
        if ($invoices->isEmpty()) {
            return view('reports.generate', [
                'reportData' => [
                    'invoices' => [],
                    'total_materials' => 0,
                    'total_cost' => 0,
                    'construction' => 'Todas',
                    'provider' => 'Todos',
                    'material' => 'Todos',
                    'category' => 'Todas',
                    'invoice' => 'Todas',
                    'dtRange' => $data['dtRange'] ?? '',
                ],
            ]);
        }

        // Cálculo de totais
        $total_materials = $invoices->sum('material_qt');
        $total_cost = $invoices->sum(fn($item) => $item->material_qt * $item->material_unit_value);

        // Define nomes para os filtros
        $construction = $data['construction_id'] ?? 'Todas';
        $provider = $data['provider_id'] ?? 'Todos';
        $material = $data['material_id'] ?? 'Todos';
        $category = $data['category_id'] ?? 'Todas';
        $invoice = $data['invoice_id'] ?? 'Todas';

        if ($invoices->isNotEmpty()) {
            $construction = $invoices->first()->construction_name;
            $provider = $data['provider_id'] ?? null ? $invoices->first()->provider_name . ' = ' . number_format($total_cost, 2, ',', '.') : 'Todos';
            $material = $data['material_id'] ?? null ? $invoices->first()->material_name : 'Todos';
            $category = $data['category_id'] ?? null ? $invoices->first()->category_name : 'Todas';
            $invoice = $data['invoice_id'] ?? null ? $invoices->first()->invoice_number . ' = ' . number_format($total_cost, 2, ',', '.') : 'Todas';
        }

        // Se o usuário deseja agrupar por material
        if (!empty($data['group_by_material'])) {
            $invoices = $invoices->groupBy('material_name')->map(function ($group) {
                $total_qt = $group->sum('material_qt');
                $total_cost = $group->sum(fn($item) => $item->material_qt * $item->material_unit_value);
                
                $unit_value = $group->first()->material_unit_value;
                
                return [
                    'material_name' => $group->first()->material_name,
                    'total_qt' => $total_qt,
                    'total_cost' => $total_cost,
                    'category_name' => $group->first()->category_name ?? '',
                    'items' => $group->toArray(),
                    'material_unit_value' => $unit_value,  // Adiciona o unit_value
                ];
            })->values();
        }

        // Retorno dos dados do relatório
        $reportData = [
            'invoices' => $invoices,
            'total_materials' => $total_materials,
            'total_cost' => $total_cost,
            'construction' => $construction,
            'provider' => $provider,
            'material' => $material,
            'category' => $category,
            'invoice' => $invoice,
            'dtRange' => $data['dtRange'] ?? '',
        ];

        return view('reports.generate', ['reportData' => $reportData]);
    }

}
