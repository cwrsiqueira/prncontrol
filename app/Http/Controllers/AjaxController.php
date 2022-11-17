<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Lot;
use App\Models\Construction;
use App\Models\Provider;
use App\Models\Material;
use App\Models\Invoice;
use App\Models\Invoice_material;

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
        Helper::saveLog($request->id, 'change inactive to 1', 'users', 'inactivate');
    }

    // CLIENT
    public function getClient(Request $request)
    {
        $client = Client::find($request->id);
        return $client;
    }

    public function delClient(Request $request)
    {
        Client::where('id', $request->id)->update(['inactive' => 1]);
        echo 'Cliente deletado com sucesso!';

        $user_id = Auth::user()->id;
        Helper::saveLog($user_id, 'change inactive to 1', 'users', 'inactivate');
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

        $user_id = Auth::user()->id;
        Helper::saveLog($user_id, 'change inactive to 1', 'lots', 'inactivate');
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

        $user_id = Auth::user()->id;
        Helper::saveLog($user_id, 'change inactive to 1', 'constructions', 'inactivate');
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
        echo 'Fornecedor deletado com sucesso!';

        $user_id = Auth::user()->id;
        Helper::saveLog($user_id, 'change inactive to 1', 'providers', 'inactivate');
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

        $user_id = Auth::user()->id;
        Helper::saveLog($user_id, 'change inactive to 1', 'materials', 'inactivate');
    }

    // INVOICES
    public function getInvoice(Request $request)
    {
        $invoice = Invoice::select('invoices.*', 'constructions.name as construction_name', 'providers.name as provider_name')
            ->leftJoin('constructions', 'constructions.id', 'invoices.construction_id')
            ->leftJoin('providers', 'providers.id', 'invoices.provider_id')
            ->where('invoices.id', $request->id)
            ->first();

        $invoice_materials = Invoice_material::select('invoice_materials.*', 'materials.name as material_name')
            ->leftJoin('materials', 'materials.id', 'invoice_materials.material_id')
            ->where('invoice_materials.company_id', Auth::user()->company_id)
            ->where('invoice_materials.inactive', 0)
            ->where('invoice_materials.invoice_id', $invoice->id)
            ->get();

        foreach ($invoice_materials as $item) {
            $material = $invoice->materials;
            $material['material'][] = $item->material_name;
            $material['unid'][] = $item->unid;
            $material['qt'][] = $item->qt;
            $material['unit_val'][] = $item->unit_value;
            $invoice->materials = $material;
        }

        return $invoice;
    }

    public function delInvoice(Request $request)
    {
        Invoice::where('id', $request->id)->update(['inactive' => 1]);
        Invoice_material::where('invoice_id', $request->id)->update(['inactive' => 1]);
        echo 'Nota deletada com sucesso!';

        $user_id = Auth::user()->id;
        Helper::saveLog($user_id, 'change inactive to 1', 'invoices', 'inactivate');
    }
}