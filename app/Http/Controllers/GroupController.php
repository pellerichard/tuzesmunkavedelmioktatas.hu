<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Account;
use App\Group;

class GroupController extends Controller
{
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = json_decode($request->getContent());
        $group = new Group;
        $group->name = $input->group->name;
        $group->companyId = $input->companyId;
        $group->creUserId = $request->session()->get('account');
        $group->save();

        return response()->json([
            'success' => true,
            'msg' => 'Sikeresen létrehoztad a felhasználói csoportot!'
        ]);
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
        $group = Group::find($input->group->id);
        $group->name = $input->group->name;
        $group->companyId = $input->companyId;
        $group->modUserId = $request->session()->get('account');
        $group->save();

        return response()->json([
            'success' => true,
            'msg' => 'Sikeresen szerkesztetted a felhasználói csoportot!'
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
        $properties = (object)['groupId','companyId'];

        DB::beginTransaction();
        try {

            $group = Group::find($id);
            /** @Propertyk kimentése, majd törlés után kezelése. */
            $properties->groupId = $group->id;
            $properties->companyId = $group->companyId;
            /** @Törlés */
            $group->delete();

            /** @Felhasználók eltávolítása a csoportból. */


            $users = Account::query()->get()->all();
            foreach($users as $key => $user) {
                $groups = unserialize($user->groups);
                if(is_array($groups)) {
                    $groups = array_filter($groups, function($v) use($properties) {
                        return $v!=$properties->groupId;
                    });
                }
                $user->groups = serialize($groups);
                $user->save();
            }


            DB::commit();

            return response()->json([
                'success' => true,
                'msg' => 'Sikeresen szerkesztetted a felhasználói csoportot!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "success" => false,
                "msg" => $e->getMessage()
            ]);
        }
    }
}
