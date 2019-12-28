<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Account, App\conAccountCompany, App\Company, App\Test, App\TestInput;
use App\ModuleConst;

class TestController extends Controller
{
    public function fetchTests(Request $request) {
        $test = Test::where('state','<>',ModuleConst::STATE_REMOVED)->get();
        return response()->json([
            "success" => true,
            "tests" => $test
        ]);
    }

    public function fetchTest(Request $request) {
        $input = json_decode($request->getContent());
        $test = Test::with(['inputs' => function($q) {
            $q->where('testinput.state', '<>', ModuleConst::STATE_REMOVED);
        }])->where('state','<>',ModuleConst::STATE_REMOVED)->where('id','=',$input->testId)->first();




        /**
         * @unserialize
        */
        foreach($test->inputs as $key => $input) {
            $test->inputs[$key]->options = unserialize($input->options);
        }

        return response()->json([
            "success" => true,
            "test" => $test
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = json_decode($request->getContent());

        /**
         * @Teszt létrehozása
        */

        $test = new Test;
        $test->videoUrl = $input->test->videoUrl;
        $test->name = $input->test->name;
        $test->creUserId = $request->session()->get('account');
        $test->state = ModuleConst::STATE_ACTIVE;
        $test->save();

        return response()->json([
            "success" => true,
            "msg" => "A tesztet sikeresen létrehoztad!",
            "id" => $test->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = json_decode($request->getContent());
        $test = Test::find($id);
        if(empty($test)) {
            return response()->json([
                "success" => true,
                "msg" => "Hiba a teszt mentése során!"
            ]);
        }

        $test->name = $input->test->name;
        $test->videoUrl = $input->test->videoUrl;
        $test->save();

        return response()->json([
            "success" => true,
            "msg" => "Sikeresen szerkesztetted a tesztet!"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $test = Test::find($id);
        if(empty($test)) {
            return response()->json([
                "success" => false,
                "msg" => "Nincs ilyen tesz az adatbázisban!"
            ]);
        }

        //$test->state = ModuleConst::STATE_REMOVED;
        //$test->save();
        $test->delete();

        return response()->json([
            "success" => true,
            "msg" => "Sikeresen törölted a tesztet!"
        ]);
    }
}
