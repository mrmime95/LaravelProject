@extends('layouts.app')
<style>
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>
@section('content')
    <div class="container">
        <div class="row">
            <div  style="width: 100%;">
                <div class="panel panel-default" >
                    <div class="panel-heading">Show Data Base</div>

                    <div class="panel-body">
                        <table style="width: 100%;">
                            <tr style="width: 100%;">
                                <th>Name</th>
                                <th>Sex<th>
                                <th>Birthday<th>
                                <th>Phone number<th>
                                <th>Adress<th>
                                <th>Country<th>
                                <th>Email<th>
                                <th>Salary<th>
                                <th>Profession<th>
                                <th>Pro Level</th>
                            </tr>
                            @foreach($users as $user)
                                <tr>
                                    <th style="width: 12%;">{{ $user->Name }}</th>
                                    <th>{{ $user->Sex}}<th>
                                    <th style="width: 9%;">{{ $user->Birthday}}<th>
                                    <th style="width: 12%;">{{ $user['Phone number']}}<th>
                                    <th style="width: 17%;">{{ $user->Adress}}<th>
                                    <th>{{ $user->Country}}<th>
                                    <th>{{ $user->Email}}<th>
                                    <th>{{ $user->Salary}}<th>
                                    <th>{{ $user->Profession}}<th>
                                    <th style="width: 5%;">{{ $user['Professional Level (1-5)']}}</th>
                                <tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection