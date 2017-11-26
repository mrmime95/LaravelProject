<?php

namespace App\Http\Controllers;

use App\FakeDb;
use Illuminate\Http\Request;

class FakeDbController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        //$fakeusers = FakeDb::all();
        return view('showdb'/*, ['users' => $fakeusers]*/);
    }
}
