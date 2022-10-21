<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Client;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::where('inactive', 0)->get();

        foreach ($clients as $client) {
            $client['addresses'] = $client->addresses;
        }
        foreach ($clients as $client) {
            $client['contacts'] = $client->contacts()->orderBy('preferencial', 'desc')->get();
        }

        return view('clients.index', ['clients' => $clients]);
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
        $client = $request->except('_token');

        $client['nome_razao_social'] = $client['nome'] ?? $client['razao_social'];
        unset($client['nome']);
        unset($client['razao_social']);
        unset($client['contacts']);
        unset($client['preferencialContact']);
        unset($client['address']);

        Validator::make($client, [
            'company_id' => 'required',
            'nome_razao_social' => 'required',
            'pessoa' => 'required',
        ]);

        $contacts = $request->only([
            'preferencialContact',
            'contacts',
        ]);

        $address = $request->only([
            'address'
        ]);

        dd($client, $contacts, $address);
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
        //
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