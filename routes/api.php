<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/aa', function () {
    return \App\FakeDb::all();
});

Route::post('/filterSaving', function (Request $request){

    $jsonreq = json_decode($request->getContent(), true);

    $tablesName = array_diff(array_keys($jsonreq), ["saved", "sex", "country", "proLevel", "profession"]);

    $savedId = json_decode(DB::table('saved')->select('id as savedId')
        ->where([
            ['savedName', '=', $jsonreq["saved"]["savedName"]],
            ['userId', '=', Auth::user()->id]
        ])->get(), true);

    $elementCount  = count($savedId);
    if($elementCount != 0)
        return json_encode("That name is on database right");

    DB::table("saved")->insert([
        "savedName" => $jsonreq["saved"]["savedName"],
        "userId" => Auth::user()->id,
        "sex" => $jsonreq["saved"]["sex"],
        "country" => $jsonreq["saved"]["country"],
        "proLevel" => $jsonreq["saved"]["proLevel"],
        "profession" => $jsonreq["saved"]["profession"]
        ]);

    $savedId = json_decode(DB::table('saved')->select('id as savedId')
        ->where([
            ['savedName', '=', $jsonreq["saved"]["savedName"]],
            ['userId', '=', Auth::user()->id]
        ])->get(), true)[0]["savedId"];

    foreach ($tablesName as $tableName){
        $dataSet = [];
        $columNames = array_keys($jsonreq[$tableName]);
        foreach ($columNames as $colName){
            $dataSet[$colName] = $jsonreq[$tableName][$colName];
        }

        $dataSet["userId"] = Auth::user()->id;
        $dataSet["savedId"] = $savedId;
        DB::table($tableName)->insert($dataSet);
    }

    return json_encode("COOL");
});
