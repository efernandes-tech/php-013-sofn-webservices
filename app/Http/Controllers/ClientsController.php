<?php

namespace App\Http\Controllers;

use App\Client;

class ClientsController extends Controller
{
    public function index()
    {
        return Client::all();
    }

    public function show($id)
    {
        return Client::find($id);
    }
}
