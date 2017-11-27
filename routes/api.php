<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\User;
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
    //return $request->getContent();
    //DB::table('users')->select('id as userId')
      //  ->where('email', '=', Auth::user()->email)
        //->get();
    //dd( \Auth::user()->id);
    /*return DB::table('users')->select('id as userId')
        ->where('email', '=', "admin")
        ->get();*/
    Auth::user()->id;
    /*DB::table('users')->insert(
        ['email' => 'john@example.com', 'votes' => 0]
    );*/

    // dd($request->getContent());

    $a = json_decode($request->getContent(), true);

    $tablesName = array_diff(array_keys($a), ["saved"]);

    $savedId = json_decode(DB::table('saved')->select('id as savedId')
        ->where('savedName', '=', $a["saved"]["savedName"])
        ->get(), true);

    $elementCount  = count($savedId);
    if($elementCount != 0)
        return json_encode("That name is on database right");

    DB::table("saved")->insert(["savedName" => $a["saved"]["savedName"], "userId" => Auth::user()->id]);

    $savedId = json_decode(DB::table('saved')->select('id as savedId')
        ->where('savedName', '=', $a["saved"]["savedName"])
        ->get(), true)[0]["savedId"];

    foreach ($tablesName as $tableName){
        $dataSet = [];
        $columNames = array_keys($a[$tableName]);
        foreach ($columNames as $colName){
            $dataSet[$colName] = $a[$tableName][$colName];
        }

        $dataSet["userId"] = Auth::user()->id;
        $dataSet["savedId"] = $savedId;
        DB::table($tableName)->insert($dataSet);
    }
    return json_encode("COOL");

});
