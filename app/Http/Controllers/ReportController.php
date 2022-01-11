<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Construction;
use App\Models\Material;
use App\Models\Invoice;

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

        return view('reports.index',
            [
                'constructions' => $constructions,
                'materials' => $materials,
                'invoices' => $invoices,
            ]
        );
    }
}
