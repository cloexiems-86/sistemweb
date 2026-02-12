<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PendampingController extends Controller
{
    public function index()
    {
        return view('admin.data-pendamping');
    }
}
