<?php

namespace App\Http\Controllers;

use App\FakeDb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
class FakeDbController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        return view('showdb');
    }

    public  function getData(){
        return FakeDb::all();
    }

    public function filterSaving(Request $request){
        $finalMessage = "";

        $jsonreq = json_decode($request->getContent(), true);

        $tablesName = array_diff(array_keys($jsonreq), ["saved", "sex", "country", "proLevel", "profession"]);


        $savedId = json_decode(DB::table('saved')->select('id as savedId')
            ->where([
                ['savedName', '=', $jsonreq["saved"]["savedName"]],
                ['userId', '=', Auth::user()->id]
            ])->get(), true);

        $elementCount = count($savedId);
        if ($elementCount != 0){
            DB::table('phoneNumber')->where([
                ["userId", '=', Auth::user()->id],
                ['savedId', '=', $savedId[0]["savedId"]]])->delete();
            DB::table('name')->where([
                ["userId", '=', Auth::user()->id],
                ['savedId', '=', $savedId[0]["savedId"]]])->delete();
            DB::table('email')->where([
                ["userId", '=', Auth::user()->id],
                ['savedId', '=', $savedId[0]["savedId"]]])->delete();
            DB::table('adress')->where([
                ["userId", '=', Auth::user()->id],
                ['savedId', '=', $savedId[0]["savedId"]]])->delete();
            DB::table('salary')->where([
                ["userId", '=', Auth::user()->id],
                ['savedId', '=', $savedId[0]["savedId"]]])->delete();
            DB::table('birthday')->where([
                ["userId", '=', Auth::user()->id],
                ['savedId', '=', $savedId[0]["savedId"]]])->delete();
            DB::table('sorting')->where([
                ["userId", '=', Auth::user()->id],
                ['savedId', '=', $savedId[0]["savedId"]]])->delete();

            DB::table("saved")->where([
                ['savedName', '=', $jsonreq["saved"]["savedName"]],
                ['userId', '=', Auth::user()->id]
            ])->update([
                "savedName" => $jsonreq["saved"]["savedName"],
                "userId" => Auth::user()->id,
                "sex" => $jsonreq["saved"]["sex"],
                "country" => $jsonreq["saved"]["country"],
                "proLevel" => $jsonreq["saved"]["proLevel"],
                "profession" => $jsonreq["saved"]["profession"]
            ]);
            $finalMessage = "Filter Updated";
        }
        else {
            DB::table("saved")->insert([
                "savedName" => $jsonreq["saved"]["savedName"],
                "userId" => Auth::user()->id,
                "sex" => $jsonreq["saved"]["sex"],
                "country" => $jsonreq["saved"]["country"],
                "proLevel" => $jsonreq["saved"]["proLevel"],
                "profession" => $jsonreq["saved"]["profession"]
            ]);
            $finalMessage = "Filter Saved";
        }
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

        return json_encode($finalMessage);
    }

    public function columnModelSaving(Request $request)
    {

        $finalMessage = "";

        $jsonreq = json_decode($request->getContent(), true);

        $savedId = json_decode(DB::table('savedcolumn')->select('id as savedId')
            ->where([
                ['savedName', '=', $jsonreq["savedName"]],
                ['userId', '=', Auth::user()->id]
            ])->get(), true);


        $elementCount = count($savedId);
        if ($elementCount != 0) {
            DB::table("savedColumn")->where([
                ['savedName', '=', $jsonreq["savedName"]],
                ['userId', '=', Auth::user()->id]
            ])->update([
                "savedName" => $jsonreq["savedName"],
                "userId" => Auth::user()->id,
                "isVisible" => $jsonreq["isVisible"],
            ]);
            $finalMessage = "Filter Updated";
        } else {
            DB::table("savedColumn")->insert([
                "savedName" => $jsonreq["savedName"],
                "userId" => Auth::user()->id,
                "isVisible" => $jsonreq["isVisible"],
            ]);
            $finalMessage = "Filter Saved";
        }
        return json_encode($finalMessage);
    }

    public  function filterLoading (Request $request){

        $savedName = json_decode($request->getContent(), true)["savedName"];
        $savedId = json_decode(DB::table('saved')->select('id as savedId')
            ->where([
                ['savedName', '=', $savedName],
                ['userId', '=', Auth::user()->id]
            ])->get(), true)[0]["savedId"];

        $arrays =  DB::table('saved')->select('proLevel', 'profession', 'sex', 'country')
            ->where([
                ['savedName', '=', $savedName],
                ['userId', '=', Auth::user()->id]
            ])->get();

        $jsonArray = json_encode($arrays[0]);
        $workingArray = json_decode($jsonArray, true);
        $arrayKeys = array_keys(json_decode($jsonArray, true));
        $willBeJsonInFinal = [];
        foreach ($arrayKeys as $savedArray) {
            if (isset($workingArray[$savedArray])) {
                $willBeJsonInFinal[$savedArray] = explode(",", $workingArray[$savedArray]);
            }
        }

        $arrays =  json_decode(DB::table('phoneNumber')->select('type', 'filter', 'filterType')
            ->where([
                ['savedId', '=', $savedId],
                ['userId', '=', Auth::user()->id]
            ])->get(), true);
        if(isset($arrays[0]))
            $willBeJsonInFinal["phoneNumber"] = $arrays[0];


        $arrays =  json_decode(DB::table('name')->select('type', 'filter', 'filterType')
            ->where([
                ['savedId', '=', $savedId],
                ['userId', '=', Auth::user()->id]
            ])->get(), true);
        if(isset($arrays[0]))
            $willBeJsonInFinal["name"] = $arrays[0];

        $arrays =  json_decode(DB::table('email')->select('type', 'filter', 'filterType')
            ->where([
                ['savedId', '=', $savedId],
                ['userId', '=', Auth::user()->id]
            ])->get(), true);
        if(isset($arrays[0]))
            $willBeJsonInFinal["email"] = $arrays[0];

        $arrays =  json_decode(DB::table('adress')->select('type', 'filter', 'filterType')
            ->where([
                ['savedId', '=', $savedId],
                ['userId', '=', Auth::user()->id]
            ])->get(), true);
        if(isset($arrays[0]))
            $willBeJsonInFinal["adress"] = $arrays[0];

        $arrays =  json_decode(DB::table('salary')->select('type', 'filter', 'filterType', 'filterTo')
            ->where([
                ['savedId', '=', $savedId],
                ['userId', '=', Auth::user()->id]
            ])->get(), true);
        if(isset($arrays[0]))
            $willBeJsonInFinal["salary"] = $arrays[0];

        $arrays =  json_decode(DB::table('birthday')->select('type', 'dateFrom', 'filterType', 'dateTo')
            ->where([
                ['savedId', '=', $savedId],
                ['userId', '=', Auth::user()->id]
            ])->get(), true);
        if(isset($arrays[0]))
            $willBeJsonInFinal["birthday"] = $arrays[0];

        $arrays =  json_decode(DB::table('sorting')->select('colId', 'sort')
            ->where([
                ['savedId', '=', $savedId],
                ['userId', '=', Auth::user()->id]
            ])->get(), true);
        if(isset($arrays[0]))
            $willBeJsonInFinal["sorting"] = $arrays[0];

        return $willBeJsonInFinal;
    }

    public function columnModelLoading (Request $request){
        $savedName = json_decode($request->getContent(), true)["savedName"];
        $arrayy =  json_decode(DB::table('savedcolumn')->select('isVisible')
            ->where([
                ['savedName', '=', $savedName],
                ['userId', '=', Auth::user()->id]
            ])->get(), true)[0]['isVisible'];
        return explode(",", $arrayy);
    }

    public function loadSavedFiltersName() {
        $savedName = json_decode(DB::table('saved')->select('savedName')
            ->where([
                ['userId', '=', Auth::user()->id]
            ])->get(), true);
        $savedNameArray = [];
        foreach ($savedName as $name){
            array_push($savedNameArray, $name['savedName']);
        }
        return $savedNameArray;
    }

    public function loadSavedColumnModelsName () {
        $savedName = json_decode(DB::table('savedColumn')->select('savedName')
            ->where([
                ['userId', '=', Auth::user()->id]
            ])->get(), true);
        $savedNameArray = [];
        foreach ($savedName as $name){
            array_push($savedNameArray, $name['savedName']);
        }
        return $savedNameArray;
    }
}
