<?php

namespace App\Http\Controllers;

use App\Client;

class ClientsController extends Controller
{
    public function index()
    {
        return Client::all();
    }
}
