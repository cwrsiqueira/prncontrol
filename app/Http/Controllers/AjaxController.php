<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Lot;
use App\Models\Construction;
use App\Models\Provider;
use App\Models\Material;
use App\Models\Invoice;

class AjaxController extends Controller
{
    // USER
    public function getUser(Request $request)
    {
        $user = User::find($request->id);
        return $user;
    }

    public function delUser(Request $request)
    {
        User::where('id', $request->id)->update(['inactive' => 1]);
        echo 'Usuário deletado com sucesso!';
    }

    // LOTS
    public function getLot(Request $request)
    {
        $lot = Lot::find($request->id);
        return $lot;
    }

    public function delLot(Request $request)
    {
        Lot::where('id', $request->id)->update(['inactive' => 1]);
        echo 'Lote deletado com sucesso!';
    }

    // CONTRUCTIONS
    public function getConstruction(Request $request)
    {
        $construction = Construction::find($request->id);
        return $construction;
    }

    public function delConstruction(Request $request)
    {
        Construction::where('id', $request->id)->update(['inactive' => 1]);
        echo 'Obra deletada com sucesso!';
    }

    // PROVIDERS
    public function getProvider(Request $request)
    {
        $provider = Provider::find($request->id);
        return $provider;
    }

    public function delProvider(Request $request)
    {
        Provider::where('id', $request->id)->update(['inactive' => 1]);
        echo 'Fornecedor deletada com sucesso!';
    }

    // MATERIALS
    public function getMaterial(Request $request)
    {
        $material = Material::find($request->id);
        return $material;
    }

    public function delMaterial(Request $request)
    {
        Material::where('id', $request->id)->update(['inactive' => 1]);
        echo 'Material deletado com sucesso!';
    }

    // INVOICES
    public function getInvoice(Request $request)
    {
        $invoice = Invoice::find($request->id);
        return $invoice;
    }

    public function delInvoice(Request $request)
    {
        Invoice::where('id', $request->id)->update(['inactive' => 1]);
        echo 'Nota deletada com sucesso!';
    }
}
