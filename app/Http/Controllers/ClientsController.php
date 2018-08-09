<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    public function index()
    {
        return Client::all();
    }

    public function show($id)
    {
        if (!($client = Client::find($id))) {
            throw new ModelNotFoundException("Client requisitado não existe");
        } else {
            return $client;
        }
    }

    public function store(Request $request)
    {
        $client = Client::create($request->all());

        return response()->json($client, 201);
    }

    public function update(Request $request, $id)
    {
        if (!($client = Client::find($id))) {
            throw new ModelNotFoundException("Client requisitado não existe");
        } else {
            $client->fill($request->all());

            $client->save();

            return response()->json($client, 200);
        }
    }

    public function destroy($id)
    {
        if (!($client = Client::find($id))) {
            throw new ModelNotFoundException("Client requisitado não existe");
        } else {
            $client->delete();
            return response()->json("", 204);
        }
    }
}
