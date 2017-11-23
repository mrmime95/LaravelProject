<?php

namespace App\Http\Controllers;

use App\FakeDb;
use Illuminate\Http\Request;

class FakeDbController extends Controller
{
    public function show()
    {
        $fakeusers = FakeDb::all();
        return view('showdb', ['users' => $fakeusers]);
    }
}
