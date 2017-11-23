<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FakeDb extends Model
{
    //
    protected $fillable = [
        'Name',
        'Sex',
        'Birthday',
        'Phone number',
        'Adress',
        'Country',
        'Email',
        'Salary',
        'Profession',
        'Professional Level (1-5)'
    ];
}
