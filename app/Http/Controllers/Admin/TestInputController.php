<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Account, App\conAccountCompany, App\Company, App\Test, App\TestInput;
use App\ModuleConst;


class TestInputController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
         * @Mező létrehozása
        */
        $testinput = new TestInput;
        $testinput->testId = $input->testId;
        $testinput->rightAnswer = $input->selectedOption;
        $testinput->question = $input->testinput->question;
        /**
         * @Opciók tömbbe parseolása
        */
        $options = [];
        foreach($input->testinput->option as $option) {
            $options[] = $option->question;
        }
        $testinput->options = serialize($options);
        $testinput->creUserId = $request->session()->get('account');
        $testinput->save();

        return response()->json([
            "success" => true,
            "msg" => "Sikeresen hozzáadtad a mezőt a teszthez."
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

        /**
         * @Mező létrehozása
         */
        $testinput = TestInput::find($id);
        if(empty($testinput)) {
            return response()->json([
                "success" => false,
                "msg" => "Nincs ilyen teszt mező az adatbázisban!"
            ]);
        }
        $testinput->testId = $input->testId;
        $testinput->rightAnswer = $input->testinput->rightAnswer;
        $testinput->question = $input->testinput->question;
        /**
         * @Opciók tömbbe parseolása
         */
        $options = [];
        foreach($input->testinput->options as $option) {
            $options[] = $option->question;
        }
        $testinput->options = serialize($options);
        $testinput->creUserId = $request->session()->get('account');
        $testinput->save();

        return response()->json([
            "success" => true,
            "msg" => "Sikeresen hozzáadtad a mezőt a teszthez."
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
        $testinput = TestInput::find($id);
        if(empty($testinput)) {
            return response()->json([
                "success" => false,
                "msg" => "Nincs ilyen teszt mező az adatbázisban!"
            ]);
        }

        //$testinput->state = ModuleConst::STATE_REMOVED;
        //$testinput->save();
        $testinput->delete();

        return response()->json([
            "success" => true,
            "msg" => "Sikeresen kitörölted a mezőt az adatbázisból-"
        ]);
    }
}
