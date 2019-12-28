<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Account, App\conAccountCompany, App\Company;
use App\ModuleConst;
use App\BlockedEmail;
use Mail;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    protected $company;

    public function __construct() {
        $this->company = new Company();
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
         * @Mezők
         * "company": {#273
                +"account": {#274
                +"firstName": null
                +"lastName": null
                +"email": null
                +"phoneNumber": null
            }
            +"name": null
            +"license": null
            +"content": null
         */

        /** @Validáció */
        $validate = [
            'name' => $input->company->name,
            'license' => $input->company->license,
            'firstName' => $input->company->account->firstName,
            'lastName' => $input->company->account->lastName,
            'email' => $input->company->account->email
        ];

        $validator = Validator::make($validate, [
            'name' => 'required|min:4',
            'license' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email'
        ])->setAttributeNames([
            'name' => 'cégnév',
            'license' => 'licensz',
            'firstName' => 'cégvezető keresztnév',
            'lastName' => 'cégvezető vezetéknév',
            'email' => 'cégvezető e-mail cím'
        ]);

        /** @Ref ellenőrzése */
        if($this->company->findCompanyByRef(str_slug($input->company->name, "-"))) {
            $validator->after(function($validator) {
                $validator->errors()->add('name', 'Ez a cégnév már használatban van!');
            });
        }


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validator' => [
                    'fieldName' => 'name',
                    'msg' => $validator->errors()->first()
                ]
            ]);
        }

        DB::beginTransaction();
        try {
            $company = new Company;
            $company->name = $input->company->name;
            $company->content = $input->company->content;
            $company->ref = str_slug($input->company->name, "-");
            $company->licenseDate = date('Y-m-d H:i:s', time() + $input->company->license);
            $company->creUserId = $request->session()->get('account');
            $company->state = ModuleConst::STATE_ACTIVE;
            $company->save();


            /**
             * @Cégtulajdonos létrehozása
            */

            $randomHash = md5($input->company->account->email);
            $randomHash = substr($randomHash, 0, 8);
            $cachePassword = $randomHash;


            $account = new Account;
            $account->email = $input->company->account->email;
            $account->password = md5($randomHash);
            $account->firstName = $input->company->account->firstName;
            $account->lastName = $input->company->account->lastName;
            $account->state = ModuleConst::STATE_ACTIVE;
            $account->creUserId = $request->session()->get('account');
            $account->save();

            /**
             * @Kapcsolat létrehozása
            */

            $con = new conAccountCompany;
            $con->accountId = $account->id;
            $con->companyId = $company->id;
            $con->permission = ModuleConst::PERMISSION_COMPANY_OWNER;
            $con->state = ModuleConst::STATE_ACTIVE;
            $con->creUserId = $request->session()->get('account');
            $con->save();

            DB::commit();

            /**
             * @Cég információ
             */
            $company = Company::query()->where('id', $company->id)->firstOrFail();
            $creUser = Account::find($request->session()->get('account'))->firstOrFail();

            /**
             * @comment "Csekkolás hogy az alkalmazott kikapcsolta-e az e-mail küldést."
             * @modDate "2018.11.07"
             */

            $blockedemail = BlockedEmail::query()->where('email','=',$account->email)->first();
            if($blockedemail==null) {
                $blacklistEmail = md5($account->email);
                Mail::send('email.uj-cegvezeto', [
                    "account" => $account,
                    "company" => $company,
                    "creUser" => $creUser,
                    "password" => $cachePassword,
                    "blacklistEmail" => $blacklistEmail
                ], function ($message) use ($account) {
                    $message->from('oktatas@emailcimed.hu', 'Tűz és Munkavédelmi Oktatás');
                    $message->to($account->email);
                    //if(\App\Providers\Helper::isTest()) {
                    //}
                    $message->subject('Új felhasználó');
                });
            }
            return response()->json([
               "success" => true,
               "msg" => "Sikeresen létrehoztad a céget!"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "success" => false,
                "msg" => $e->getMessage()
            ]);
        }
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
        $company = Company::find($id);
        if($company->id==null) {
            return response()->json([
                "success" => false,
                "msg" => "Nincs ilyen cég az adatbázisban!"
            ]);
        }

        $company->name = $input->company->name;
        $company->ref = str_slug($input->company->name, "-");
        $company->content = $input->company->content;
        $company->licenseDate = $input->company->license ? date('Y-m-d H:i:s', time() + $input->company->license) : $company->licenseDate;
        $company->save();

        return response()->json([
            "success" => true,
            "msg" => "Sikeresen szerkesztetted a céget!"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $input = json_decode($request->getContent());
        $company = Company::find($id);
        if(empty($company)) {
            return response()->json([
                "success" => false,
                "msg" => "Nincs ilyen cég az adatbázisban!"
            ]);
        }

        /**
         * @Kapcsolat(ok) törlése
        */

        $con = conAccountCompany::where('companyId','=',$company->id)
            ->update(['state' => ModuleConst::STATE_REMOVED]);

        /**
         * @Cég törlése
         */
        //$company->state = ModuleConst::STATE_REMOVED;
        //$company->save();

        $company->delete();

        return response()->json([
            "success" => true,
            "msg" => "Sikeresen törölted a céget!"
        ]);
    }
}
