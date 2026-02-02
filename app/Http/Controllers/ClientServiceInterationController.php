<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientServiceInterationController extends Controller
{
    public function index()
    {
        return view('admin.ClientServiceInteraction');
    }
}
