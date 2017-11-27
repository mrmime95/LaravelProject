<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function create()
    {
        return view("admin.users.create");
    }

    public function store(Request $request){
        User::create($request->all());
        return "sucess";
    }
}
