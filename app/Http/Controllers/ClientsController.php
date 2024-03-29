<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
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
        $clients = Client::where('inactive', 0)->where('company_id', Auth::user()->company_id)->get();

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

        if ($client['pessoa'] == 'fisica') {
            $nome_razao_social = 'nome';
            $title = 'Nome';
        } else {
            $nome_razao_social = 'razao_social';
            $title = 'Razão Social';
        }

        Validator::make(
            $client,
            [
                $nome_razao_social => 'required'
            ],
            [
                'required' => "O campo {$title} é obrigatório",
            ]
        )->validate();

        unset($client['nome']);
        unset($client['razao_social']);
        unset($client['contacts']);
        unset($client['preferencialContact']);
        unset($client['address']);

        $client_id = Client::insertGetId($client);

        $contacts = $request->only([
            'preferencialContact',
            'contacts',
        ]);

        if (empty($contacts['preferencialContact'])) {
            $contacts['preferencialContact'] = 1;
        }

        for ($i = 1; $i <= count($contacts['contacts']); $i++) {
            $contacts['contacts']['contact' . $i]['company_id'] = $client['company_id'];
            $contacts['contacts']['contact' . $i]['client_id'] = $client_id;

            if ($contacts['preferencialContact'] == $i) {
                $contacts['contacts']['contact' . $i]['preferencial'] = 1;
            } else {
                $contacts['contacts']['contact' . $i]['preferencial'] = 0;
            }
        }

        $loop = 1;
        foreach ($contacts['contacts'] as $contact) {
            if (($contact['descricao_contato'] || $contact['dados_contato']) || $loop == 1) {
                Contact::insert($contact);
            }
            $loop++;
        }

        $address = $request->only([
            'address'
        ]);

        $address['address']['company_id'] = $client['company_id'];
        $address['address']['client_id'] = $client_id;

        Address::insert($address['address']);

        Helper::saveLog(Auth::user()->id, $client, 'Clientes', 'Inclusão');

        return redirect()->route("clients.index")->with('success', 'Cadastro efetuado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::find($id);
        $client['contacts'] = Client::find($id)->contacts;
        $client['addresses'] = Client::find($id)->addresses;

        return view('clients.show', ['client' => $client]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client::find($id);
        $client['contacts'] = Client::find($id)->contacts;
        $client['addresses'] = Client::find($id)->addresses;

        return view('clients.edit', ['client' => $client]);
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
        $client = $request->except(['_token', '_method']);
        $client['nome_razao_social'] = $client['nome'] ?? $client['razao_social'];

        if ($client['pessoa'] == 'fisica') {
            $nome_razao_social = 'nome';
            $title = 'Nome';
        } else {
            $nome_razao_social = 'razao_social';
            $title = 'Razão Social';
        }

        Validator::make(
            $client,
            [
                $nome_razao_social => 'required'
            ],
            [
                'required' => "O campo {$title} é obrigatório",
            ]
        )->validate();

        unset($client['nome']);
        unset($client['razao_social']);
        unset($client['contacts']);
        unset($client['preferencialContact']);
        unset($client['address']);

        $client_id = $id;
        Client::find($client_id)->update($client);
        Contact::where('client_id', $client_id)->delete();
        Address::where('client_id', $client_id)->delete();

        $contacts = $request->only([
            'preferencialContact',
            'contacts',
        ]);

        if (empty($contacts['preferencialContact'])) {
            $contacts['preferencialContact'] = 1;
        }

        for ($i = 1; $i <= count($contacts['contacts']); $i++) {
            $contacts['contacts']['contact' . $i]['company_id'] = $client['company_id'];
            $contacts['contacts']['contact' . $i]['client_id'] = $client_id;

            if ($contacts['preferencialContact'] == $i) {
                $contacts['contacts']['contact' . $i]['preferencial'] = 1;
            } else {
                $contacts['contacts']['contact' . $i]['preferencial'] = 0;
            }
        }

        $loop = 1;
        foreach ($contacts['contacts'] as $contact) {
            if (($contact['descricao_contato'] || $contact['dados_contato']) || $loop == 1) {
                Contact::insert($contact);
            }
            $loop++;
        }

        $address = $request->only([
            'address'
        ]);

        $address['address']['company_id'] = $client['company_id'];
        $address['address']['client_id'] = $client_id;

        Address::insert($address['address']);

        Helper::saveLog(Auth::user()->id, $client, 'Clientes', 'Alteração');

        return redirect()->route("clients.edit", ['client' => $id])->with('success', 'Cadastro alterado com sucesso!');
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