<?php

namespace App\Http\Controllers;

use App\Address;
use App\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AddressesController extends Controller
{
    public function index($clientId)
    {
        if (!($client = Client::find($clientId))) {
            throw new ModelNotFoundException("Client requisitado não existe");
        }

        return son_response()->make(Address::where('client_id', $clientId)->get());
    }

    public function show($id, $clientId)
    {
        if (!(Client::find($clientId))) {
            throw new ModelNotFoundException("Client requisitado não existe");
        }

        if (!(Address::find($id))) {
            throw new ModelNotFoundException("Endereço requisitado não existe");
        }

        $result = Address::where('client_id', $clientId)->where('id', $id)->get()->first();

        return son_response()->make($result);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        $client = Client::create($request->all());

        return son_response()->make($client, 201);
    }

    public function update(Request $request, $id)
    {
        if (!($client = Client::find($id))) {
            throw new ModelNotFoundException("Client requisitado não existe");
        } else {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
            ]);

            $client->fill($request->all());

            $client->save();

            return son_response()->make($client, 200);
        }
    }

    public function destroy($id)
    {
        if (!($client = Client::find($id))) {
            throw new ModelNotFoundException("Client requisitado não existe");
        } else {
            $client->delete();

            return son_response()->make("", 204);
        }
    }
}
