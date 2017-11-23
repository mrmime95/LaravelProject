<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        $users = [
            '0' => [
                "first_name" => "Gyorgy",
                "last_name" => "Szilard"
            ],
            '1' => [
                "first_name" => "Gyorgy",
                "last_name" => "Bence"
            ]
        ];
        return view('admin.users.index', compact("users"));
    }

    public function create()
    {
        return view("admin.users.create");
    }

    public function store(Request $request){
        User::create($request->all());
        return "sucess";
    }
}
