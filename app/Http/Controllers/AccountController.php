<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use App\Company;
use App\conAccountCompany;
use Mail;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\DB;
use App\ModuleConst;
use App\Test;
use App\Answer;
use App\ChangePassword;
use App\BlockedEmail;
use App\EmailNotification;
use \Session;
use App\DiaryLog;
use App\Group;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade as PDF;

class AccountController extends Controller
{
    const STATE_PASSWORD = 0;
    const STATE_SETTINGS = 1;

    /**
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function isLoggedIn(Request $request) {
        return response()->json([
            "success" => true,
            "loggedIn" => $request->session()->has('account')
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function validateSession(Request $request) {
        return response()->json([
            "success" => true,
            "data" => [
                'account' => (int)$request->session()->get('account'),
                'role' => (int)$request->session()->get('role'),
                'keepalive' => (int)$request->session()->get('keepalive')
            ]
        ]);
    }

    /**
     * @comment Felhasználó beléptetése
     *
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        $input = json_decode($request->getContent());
        $account = Account::where('email', '=', $input->email)
            ->where('password','=',md5($input->password))
            ->first();

        $validate = [
            'email' => $input->email,
            'password' => $input->password
        ];

        $validator = Validator::make($validate, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        /**
         * @comment "Saját hiba üzenetek."
         */
        if(empty($account)) {
            $validator->after(function($validator) {
                $validator->errors()->add('email', 'Hibás e-mail cím vagy jelszó.');
            });
        }

        if($account!=null && $account->state==ModuleConst::STATE_REMOVED) {
            $validator->after(function($validator) {
                $validator->errors()->add('email', 'A felhasználó eltávolításra került a rendszerünkből, értesítsd a főnöködet.');
            });
        }

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "msg" => $validator->errors()->first()
            ]);
        }


        $highestRole = 1;

        if(!empty($account)) {
            $highestRole = conAccountCompany::where('accountId','=',$account->id)->max('permission');
            $request->session()->put('account',$account->id);
            $request->session()->put('role',$highestRole);
        }

        if($input->keepalive) {
            $request->session()->put('keepalive', (time() + (3600*24*30)));
        } else {
            $request->session()->put('keepalive', (time() + (3600*12)));
        }

        return response()->json([
            "success" => !empty($account),
            "expireTime" => $request->session()->get('keepalive'),
            "account" => !empty($account) ? $account->id : null,
            "role" => $highestRole
        ]);
    }

    /**
     * @comment Felhasználó kiléptetése
     *
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {
        $request->session()->forget('account');
        $request->session()->forget('role');
        $request->session()->forget('keepalive');

        return response()->json([
            "success" => "true"
        ]);
    }

    /**
     * @comment Felhasználó regisztrálása
     *
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        $input = json_decode($request->getContent());
        /*
         * @security
        */
        $account = Account::where('email','=',$input->email)->first();
        if(count($account)) {
            return response()->json([
                "success" => false,
                "msg" => "Ez az e-mail cím már használatban van!"
            ]);
        }
        /*
         * @insert
        */
        $account = new Account;
        $account->email = $input->email;
        $account->password = md5($input->password);
        $account->ip = $request->ip();
        $account->save();

        return response()->json([
            "success" => true
        ]);
    }

    /**
     * @comment Felhasználó cégeinek lekérdezése
     */
    public function fetchCompanies(Request $request) {
        $input = json_decode($request->getContent());
        $accountId = $request->session()->get('account');

        if($input->fetchAll && (int)$request->session()->get('role')==ModuleConst::PERMISSION_ADMIN) {
            $companies = conAccountCompany::query()
                ->select('companyId')
                // ->where('accountId','=',$accountId)
                ->where('state','<>',ModuleConst::STATE_REMOVED)
                ->get();
        } else {
            $companies = conAccountCompany::query()
                ->select('companyId')
                ->where('accountId','=',$accountId)
                ->where('state','<>',ModuleConst::STATE_REMOVED)
                ->get();
        }


        $ids = [];
        if(!empty($companies)) {
            foreach($companies as $company) {
                $ids[] = $company->companyId;
            }
        }

        $companies = Company::query()
            ->whereIn('id', $ids)
            ->with(['con.users'])
            ->get();

        foreach($companies as $key => $company) {
            $companies[$key]->countDiaries = Company::countDiaries($company->id());
            $companies[$key]->latestDiaryDate = Company::latestDiaryDate($company->id());
        }

        return response()->json([
            "success" => true,
            "companies" => $companies
        ]);
    }

    /**
     * @comment Felhasználó egy adott cégének a lekérdezése
     */
    public function deleteCompanyMember(Request $request) {
        $input = json_decode($request->getContent());

        /**
         * @annotation
         *
         * @var $input->account
         * @var $input->companyId
         */

        $con = conAccountCompany::where('accountId',$input->account->id)
            ->where('companyId',$input->companyId)
            ->first();

        if(count($con)) {
            /**
             * @Ha van kapcsolat
            */
            //$con->state = conAccountCompany::STATE_REMOVED;
            //$con->save();
            $con->delete();

            /** @Felhasználó törlése amennyiben egy céghez sincs már hozzákötve. */
            $count = conAccountCompany::where('accountId',$input->account->id)->count();
            if(!$count) {
                $account = Account::find($input->account->id);
                $account->delete();
            }

            return response()->json([
                "success" => true,
                "msg" => "Sikeresen törölted a felhasználót!"
            ]);
        }

        /**
         * @comment "A későbbiekben ha a felhasználó különálló lesz, akkor a cégtől vaó eltávolításnál tegye inaktív státuszba a felhasználót amennyiben nincs semmilyen céghez kapcsolata.
         * @lastModDate 2018.10.03 22:20
         */

        /*
            $account = Account::with('con')->where('id',$input->account->id)->first();
            die(var_dump($account));
        */

        return response()->json([
            "success" => false,
            "msg" => "Hiba a felhasználó törlése során!"
        ]);
    }

    public function editCompanyMember(Request $request) {
        $input = json_decode($request->getContent());

        /**
         * @annotation
         *
         * @var $input->account
         * @var $input->companyId
         */

        $con = conAccountCompany::where('accountId',$input->account->id)
            ->where('companyId',$input->companyId)
            ->first();

        if(count($con)) {
            /**
             * @Ha van kapcsolat
             */
            $account = Account::find($input->account->id);
            /** @Csoportok */
            $filter = function($value) {
                return $value->id;
            };

            $groups = array_map($filter, $input->groups);
            $account->groups = serialize($groups);

            /** @Egyéb */
            $account->firstName = $input->account->firstName;
            $account->lastName = $input->account->lastName;
            $account->educationReason = $input->account->educationReason;
            // $account->email = $input->account->email;
            $account->save();

            return response()->json([
                "success" => true,
                "msg" => "Sikeresen szerkesztetted a felhasználót!"
            ]);
        }

        /**
         * @comment "A későbbiekben ha a felhasználó különálló lesz, akkor a cégtől vaó eltávolításnál tegye inaktív státuszba a felhasználót amennyiben nincs semmilyen céghez kapcsolata.
         * @lastModDate 2018.10.03 22:20
         */

        /*
            $account = Account::with('con')->where('id',$input->account->id)->first();
            die(var_dump($account));
        */

        return response()->json([
            "success" => false,
            "msg" => "Hiba a felhasználó szerkesztés során!"
        ]);
    }

    public function fetchCompanyGroups(Request $request) {
        $input = json_decode($request->getContent());
        $groups = Group::query();



        $groups->where(function ($q) use ($input) {
            if(isset($input->companyId)) {
                $q->where('companyId', $input->companyId);
            }
            if(isset($input->companyRef)) {
                $companyId = Company::query()->where('ref','=',$input->companyRef)->pluck('id');
                /** @Csatlakozás a cégek táblába. */
                $q->where('companyId',$companyId);
            }
        });

        $groups = $groups->get();

        return response()->json([
            "success" => true,
            "groups" => $groups
        ]);
    }

    /**
     * @comment Felhasználó egy adott cégének a lekérdezése
     */
    public function fetchCompany(Request $request) {
        $input = json_decode($request->getContent());
        $accountId = $request->session()->get('account');

        $companies = conAccountCompany::query();
        if(!$input->data->fetchAll && (int)$request->session()->get('role')!=ModuleConst::PERMISSION_ADMIN) {
            $companies->where('accountId','=',$accountId);
        }
        $companies->where(function ($q) use ($input) {
            if(isset($input->data->companyId)) {
                $q->where('companyId','=',$input->data->companyId);
            }
            if(isset($input->data->companyRef)) {
                /** @Csatlakozás a cégek táblába. */
                $q->leftJoin('company', function($join) use($input){
                    $join->on('company.id','=','conaccountcompany.companyId');
                    $join->where('company.ref','=',$input->data->companyRef);
                });
            }
        });

        $companies->where('state','<>',ModuleConst::STATE_REMOVED);
        $companies = $companies->get();

        if(!count($companies)) {
            return response()->json([
                "success" => false,
                "msg" => "Ez nem a te céged!"
            ]);
        }

        /** @Cég információk */
        $company = Company::with(['con' => function($q) use($request, $input) {
            $q->where('conaccountcompany.state', '<>', 3);
            if(!$input->data->fetchAll && (int)$request->session()->get('role')!=ModuleConst::PERMISSION_ADMIN) {
                $q->where('conaccountcompany.permission', [ModuleConst::PERMISSION_NORMAL]);
            }
        },'con.users' => function($q) use($input) {
            if(isset($input->data->filter)) {
                /** @Név alapján szűrő */

                /*$q->orderByRaw('CASE
                    WHEN account.firstName LIKE "%'.$input->data->filter.'%" THEN 1
                    WHEN account.lastName LIKE  "%'.$input->data->filter.'%" THEN 2
                    WHEN CONCAT(account.lastName," ",account.firstName) LIKE "%'.$input->data->filter.'%" THEN 3
                    ELSE 4
                END');*/

                $q->where('account.firstName','like', '%'.$input->data->filter.'%');
                $q->orWhere('account.lastName','like', '%'.$input->data->filter.'%');
                $q->orWhere(DB::raw('CONCAT(account.lastName," ",account.firstName)'),'like', '%'.$input->data->filter.'%');
                $q->groupBy('account.id');
            }
        }]);
        if(isset($input->data->companyId)) {
            $company->where('id','=',$input->data->companyId);
        }
        if(isset($input->data->companyRef)) {
            $company->orWhere('ref','=',$input->data->companyRef);
        }
        $company = $company->first();


        /** @User Groups */
        $groups = [];
        if(isset($company->con)) {
            foreach($company->con as $key => $user) {
                if(!isset($user->users)) {
                    /** @Ha nincs ilyen felhasználó, de a conaccountcompany táblában jelen van, akkor kitöröljük. */
                    unset($company->con[$key]);
                    continue;
                }
                /** @Felhasználó jogosultságának megnevezése az "Admin" oldalra. */
                $company->con[$key]->users['userPermission'] = $user->getUserPermissionName();

                $company->con[$key]->users['userGroups'] = $user->getUserGroups();
                $company->con[$key]->users->groups = is_array($user->users->groups) ? $user->users->groups : unserialize($user->users->groups);

            }

            /** @Céghez kötött csoportok, */
            $companyId = isset($input->data->companyId) ? $input->data->companyId : null;
            if($companyId==null) {
                $companyId = Company::query()->where('ref',$input->data->companyRef)->pluck('id');
            }
            $groups = Group::query()->where('companyId', $companyId)->get()->all();
        }

        return response()->json([
            "success" => true,
            "company" => $company,
            "groups" => $groups,
            "filter" => (isset($input->data->filter) && $input->data->filter)
        ]);
    }

    public function korlevelTiltas(Request $request, $token) {

        $account = Account::where(DB::raw('md5(email)'), $token)->first();

        if($account!=null) {
            $blockedEmail = BlockedEmail::query()->where('email','=',$account->email)->first();
            if($blockedEmail==null) {
                $blockedEmail = new BlockedEmail;
                $blockedEmail->email = $account->email;
                $blockedEmail->ip = $request->ip();
                $blockedEmail->save();

                return response()->json([
                    "success" => true,
                    "msg" => "Mostantól nem fogsz levelet kapni a szolgáltatással kapcsolatosan."
                ]);
            }
        }

        return response()->json([
            "success" => false,
            "msg" => "Hibás token, vagy az e-mail már a tiltólistán van."
        ]);
    }

    /**
     * @comment "Teszt beküldése"
    */
    public function sendTest(Request $request) {
        /**
         * @comment "Az összes teszt lekérdezése, foreach majd id szerint az @var $answer->testId-t kicserélni, lekérdezni az adott cég alapján a tulajt majd annak
         * kiküldeni a válaszokat."
         * @modDate "2018.11.02"
         */


        $input = json_decode($request->getContent());

        /**
         * @template [{question: 'Kérdés', answer: 'Ez a válaszom', correct: true/false}]
         * @modDate 2018.10.13
        */

        $account = Account::query()->where('id', $request->session()->get('account'))->firstOrFail();

        foreach($input->answers as $key => $answered) {
            /**
             * @comment "Adatbázisba beillesztés."
             */
            $answer = new Answer;
            $answer->testId = $input->question->id;
            $answer->testInputId = $input->question->inputs[$key]->id;
            $answer->question = $input->question->inputs[$key]->question;
            $answer->answer = $input->question->inputs[$key]->options[$answered];
            $answer->educationReason = $account->educationReason;
            $answer->correct = (bool)($input->question->inputs[$key]->rightAnswer == $answered);
            $answer->creUserId = $request->session()->get('account');
            $answer->save();
        }

        /** @Ha az összes teszt ki van töltve, akkor küldjön sikeres kitöltés e-mailt. */
        $companyId = conAccountCompany::where('accountId',$answer->creUserId)->pluck('companyId');
        $questions = Test::query()
            ->with(['inputs'])
            ->where('state','<>',ModuleConst::STATE_REMOVED)
            ->get();

        /** @A kérdőívekhez tötött kérdések számának meghatározása. */
        /**
         * @var $countQuestion (Meghatározza az összes teszt kérdéseinek számát.)
        */

        $countQuestion = 0;
        foreach($questions as $question) {
            //foreach($question->inputs as $input) {
                $countQuestion += count($question->inputs);
            //}
        }

        $tests = conAccountCompany::query()
            ->with(['user.answers'])
            ->where('conaccountcompany.companyId', '=', $companyId)
            ->where('conaccountcompany.state', '<>', ModuleConst::STATE_REMOVED)
            ->where('conaccountcompany.permission', '=', ModuleConst::PERMISSION_NORMAL)
            ->get();

        /** @Megszorozzuk annyival, ahány darab munkatárs tartozik a céghez, a pontos mérések érdekében. */
        $countQuestion *= count($tests);

        $correctWorkerAnswers = 0;
        $workerAnswers = 0;
        foreach($tests as $test) {
            if(isset($test->user->answers) && count($test->user->answers)) {
                foreach($test->user->answers as $answer) {
                    ++$workerAnswers;
                    if($answer->correct) {
                        ++$correctWorkerAnswers;
                    }
                }
            }
        }
        $successRate = 0;
        if($workerAnswers>=$correctWorkerAnswers) {
            $successRate = number_format(($correctWorkerAnswers / $countQuestion) * 100, 0);
        }

        /** @Az összesített adatok százaléko arányban való meghatározása. */
        $overallCount = Answer::query()
            ->groupBy('testId')
            ->where('creUserId',$request->session()->get('account'))
            ->get();


        $questions = Test::query()->get()->all();

        $questionIds = [];
        foreach($questions as $question) {
            $questionIds[] = $question->id;
        }

        /** @Ha a tesztek sikeressége nagyobb mint 85%, akkor e-mailt küldünk a cégvezetőnek. */
        if($successRate>=85 && count($overallCount)==count($questionIds)) {

            $company = Company::query()->where('id', $companyId)->firstOrFail();
            $users = conAccountCompany::query()
                ->select('account.*')
                ->leftJoin('account', 'account.id', '=', 'conaccountcompany.accountId')
                ->where('conaccountcompany.companyId', '=', DB::raw($company->id))
                ->where('conaccountcompany.permission', '=', ModuleConst::PERMISSION_COMPANY_OWNER)
                ->get()->all();

            $emails = [];
            foreach($users as $id => $user) {
                $emails[$id] = $user->email;
            }

            $workerIds = [];
            foreach($tests as $test) {
                $workerIds[] = $test->user->id;
            }

            $educationDates = [];
            foreach($workerIds as $workerId) {
                $educationDates[$workerId] = Answer::query()->select('creDate')->where('creUserId', $workerId)->orderByDesc('creDate')->first();
            }


            /** Kiküldött naplók logolása */
            $diaryLog = new DiaryLog();
            $diaryLog->companyId = $company->id();
            $diaryLog->creDate = date('Y-m-d H:i:s');
            $diaryLog->save();

            $testNames = Test::query()->select('name')->get();

            $testNameArray = [];
            foreach($testNames as $test) {
                $testNameArray[] = $test->name;
            }

            foreach($emails as $id => $email) {
                $pdf = PDF::loadView('pdf.teszteredmeny', [
                    "testNames" => $testNameArray,
                    "company" => $company,
                    "tests" => $tests,
                    "account" => $users[$id],
                    "educationDates" => $educationDates
                ]);

                $blacklistEmail = md5($email);

                Mail::send('email.sikeres-teszteredmenyek', [
                    "company" => $company,
                    "account" => $users[$id],
                    "blacklistEmail" => $blacklistEmail
                ], function ($message) use ($user, $email, $pdf, $company) {
                    $message->from('emailcimed@gmail.com', 'Tűz és Munkavédelmi Oktatás');
                    $message->to($email);
                    //if(\App\Providers\Helper::isTest()) {
                    //}
                    $message->subject('Sikeresen kitöltötte mindenki a teszteket!');
                    $message->attachData($pdf->output(), $company->name." - Teszteredmények.pdf");
                });
            }
        }

        return response()->json([
            "success" => true,
            "msg" => "Sikeresen kitöltötted a tesztet."
        ]);
    }

    /**
     * @param Request $request
     * @param $token
     * @return int
     */
    public function alkalmazottErtesites(Request $request, $token) {
        $emailnotification = EmailNotification::where(DB::raw('md5(id)'), $token)->first();

        if($emailnotification==null) {
            return response()->json([
                "success" => false,
                "msg" => "Hiba történt a körlevél küldése közben."
            ]);
        }

        /*if($emailnotification->creUserId != $request->session()->get('account') ||
            (int)$request->session()->get('role') == ModuleConst::PERMISSION_ADMIN) {
            return response()->json([
                "success" => false,
                "msg" => "Nincs jogosultságod a körlevél kiküldéséhez."
            ]);
        }*/

        if($emailnotification->state==ModuleConst::STATE_REMOVED) {
            return response()->json([
                "success" => false,
                "msg" => "Már értesítetted az alkalmazottjaidat."
            ]);
        }

        $token = json_decode($emailnotification->data);

        $from = [];
        $from[] = 'Tűz és Munkavédelmi Oktatás';
        $from[] = $token->company->name;
        $from[] = 'Értesítés';

        foreach($token->workers as $worker) {
            /**
             * @comment "Csekkolás hogy az alkalmazott kikapcsolta-e az e-mail küldést."
             * @modDate "2018.11.07"
             */
            $blockedemail = BlockedEmail::query()->where('email','=',$worker->email)->first();
            if($blockedemail!=null) {
                continue;
            }

            /** @Tesztek törlése amennyiben 85% alatt teljesített. */
            $tests = [];
            foreach($worker->answers as $answer) {
                if(!isset($tests[$answer->testId]['count'])) {
                    $tests[$answer->testId]['count'] = 0;
                }
                if(!isset($tests[$answer->testId]['correct'])) {
                    $tests[$answer->testId]['correct'] = 0;
                }
                ++$tests[$answer->testId]['count'];
                if($answer->correct) {
                    ++$tests[$answer->testId]['correct'];
                }
            }

            foreach($tests as $key => $test) {
                $success = number_format(($test['correct'] / $test['count']) * 100, 0);
                if($success<85) {
                    Answer::query()
                        ->where('testId', $key)
                        ->where('creUserId', $worker->id)
                        ->delete();
                }
            }

            $blacklistEmail = md5($worker->email);
            Mail::send('email.toltsd-ki-a-tesztet', [
                "company" => $token->company,
                "account" => $worker,
                "blacklistEmail" => $blacklistEmail
            ], function ($message) use ($worker, $from) {
                $message->from('oktatas@emailcimed.hu', 'Tűz és Munkavédelmi Oktatás');
                $message->to($worker->email);
                //if (\App\Providers\Helper::isTest()) {
                //}
                $message->subject('Tesztek kitöltésével kapcsolatos értesítés');
            });
        }

        $emailnotification->state = ModuleConst::STATE_REMOVED;
        $emailnotification->save();
        //$emailnotification->delete();

        return response()->json([
            "success" => true
        ]);
    }

    /**
     * @comment "Kérdés lekérdezése"
    */
    public function fetchQuestion(Request $request) {
        $accountId = $request->session()->get('account');

        $filledTests = Answer::query()->select('testId')->where('creUserId','=',$accountId)->get();

        $tests = Test::select(['test.*','test.id'])->with(['inputs' => function($q) {
            $q->where('testinput.state', '<>', ModuleConst::STATE_REMOVED);
        }])
        ->leftJoin('answer', function($join) use($accountId){
            $join->on('answer.creUserId','=','answer.creUserId')
                ->where('answer.creUserId','=',$accountId);
        })
        ->where('test.state','<>',ModuleConst::STATE_REMOVED)
        ->whereNotIn('test.id', $filledTests)
        ->first();


        /**
         * @comment "Refaktorálni SQL-re nem sikerült múltkor ..."
         * @modDAte 2018.10.13
        */


        if(empty($tests)) {
            return response()->json([
                "success" => false,
                "msg" => "Nincsenek további tesztjeid."
            ]);
        }

        foreach($tests->inputs as $key => $input) {
            $tests->inputs[$key]->options = unserialize($input->options);
        }

        if(empty($tests)) {
            return response()->json([
                "success" => false,
                "data" => []
            ]);
        }

        return response()->json([
            "success" => true,
            "data" => $tests
        ]);
    }

    /**
     * @comment Felhasználó létrehozása és hozzáadása a céghez.
    */
    public function addCompanyMember(Request $request) {
        $input = json_decode($request->getContent());

        /** @Validáció */
        $validate = [
            'firstName' => $input->account->firstName,
            'lastName' => $input->account->lastName,
            'email' => $input->account->email,
            'educationReason' => $input->account->educationReason
        ];

        $validator = Validator::make($validate, [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email|unique:account,email',
            'educationReason' => 'required'
        ])->setAttributeNames([
            'firstName' => 'munkatárs keresztnév',
            'lastName' => 'munkatárs vezetéknév',
            'email' => 'munkatárs e-mail cím',
            'educationReason' => 'oktatás indoka'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'validator' => [
                    'fieldName' => 'email',
                    'msg' => $validator->errors()->first()
                ]
            ]);
        }


        DB::beginTransaction();
        try {
            $randomHash = md5($input->account->email);
            $randomHash = substr($randomHash, 0, 8);
            $cachePassword = $randomHash;

            /**
             * @Felhasználó létrehozása
             */
            $account = new Account;
            $account->email = $input->account->email;
            $account->password = md5($randomHash);
            $account->firstName = $input->account->firstName;
            $account->lastName = $input->account->lastName;
            $account->phoneNumber = $input->account->phoneNumber;
            $account->educationReason = $input->account->educationReason;
            $account->creUserId = $request->session()->get('account');
            $account->save();

            /**
             * @Kapcsolat létrehozása
             */
            $con = new conAccountCompany;
            $con->accountId = $account->id;
            $con->companyId = $input->companyId;
            $con->state = conAccountCompany::STATE_ACTIVE;
            $con->permission = ModuleConst::PERMISSION_NORMAL; // * Sima felhasználói jogosultság.
            $con->creUserId = $request->session()->get('account');
            $con->save();

            /**
             * @Cég információ
             */
            $company = Company::where('id', '=', $input->companyId)->firstOrFail();
            $creUser = Account::where('id', '=', $request->session()->get('account'))->firstOrFail();

            DB::commit();

            /**
             * @comment "Csekkolás hogy az alkalmazott kikapcsolta-e az e-mail küldést."
             * @modDate "2018.11.07"
             */
            $blockedemail = BlockedEmail::query()->where('email','=',$account->email)->first();
            if($blockedemail==null) {
                $blacklistEmail = md5($account->email);
                Mail::send('email.uj-munkatars', [
                    "account" => $account,
                    "company" => $company,
                    "creUser" => $creUser,
                    "password" => $cachePassword,
                    "blacklistEmail" => $blacklistEmail
                ], function ($message) use ($account) {
                    $message->from('oktatas@emailcimed.hu', 'Tűz és Munkavédelmi Oktatás');
                    $message->to($account->email);
                    $message->subject('Új felhasználó');
                });
            }

            return response()->json([
                "success" => true,
                "msg" => "Sikeresen hozzárendeltél egy új munkatársat a cégedhez. A(z) {$input->account->email} e-mail címre kiküldtük a rendszer által generált jelszót."
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
     * @comment Felhasználói adatok megváltoztatása
    */
    public function savesettings(Request $request) {
        $input = json_decode($request->getContent());
        $account = Account::find($request->session()->get('account'));

        if($account->id!=null) {
            if($input->state==AccountController::STATE_PASSWORD) {
                /**
                 * @Jelszó megváltoztatása
                */
                if($input->account->password!=null && $input->account->password_again!=null) {
                    if($input->account->password!=$input->account->password_again) {
                        return response()->json([
                            "success" => false,
                            "msg" => "A két jelszó nem egyezik!"
                        ]);
                    }
                    $account->password = md5($input->account->password);
                    $account->save();
                    return response()->json([
                        "success" => true,
                        "msg" => "Sikeresen megváltoztattad a jelszavad!"
                    ]);
                } else {
                    return response()->json([
                        "success" => true,
                        "msg" => "Nincs kitöltve a jelszó mező!"
                    ]);
                }
            } else if($input->state==AccountController::STATE_SETTINGS) {
                /**
                 * @Adatok mentése
                */
                $account->firstName = $input->account->firstName;
                $account->lastName = $input->account->lastName;
                $account->phoneNumber = $input->account->phoneNumber;
                $account->save();

                return response()->json([
                    "success" => true,
                    "msg" => "Sikeresen megváltoztattad az adataidat!"
                ]);
            }
        }
        return response()->json([
            "success" => false,
            "msg" => "Nincs ilyen felhasználó az adatbázisban"
        ]);
    }

    public function passwordchange(Request $request) {
        $input = $request->request->all();
        $token = json_decode(base64_decode($input['token']));

        ChangePassword::query()
            ->where('ip','=',$request->ip())
            ->where('email','=',$token->email)
            ->where('creDate','<=', date('Y-m-d H:i:s'))
            ->delete();

        $pc = ChangePassword::query()
            ->where('ip','=',$request->ip())
            ->where('email','=',$token->email)
            ->where('creDate','>=', date('Y-m-d H:i:s'))
            ->first();

        $validate = ["ip" => $token->ip, "email" => $token->email];
        $validator = Validator::make($validate, [
            'ip' => 'required',
            'email' => 'required|email'
        ]);

        /**
         * @comment "Lekérdezések"
         * @modDate "2018.10.18"
         */
        $account = Account::query()->where('email', $token->email)->first();
        $validator->after(function($validator) use($account, $pc) {
            if(empty($pc)) {
                $validator->errors()->add('ip', 'Ez a token már lejárt! Igényelj újat a weboldalon!');
            } else if(empty($account)) {
                $validator->errors()->add('ip', 'Nincs ilyen felhasználó!');
            }
        });

        $pw = uniqid();
        $account->password = md5($pw);
        $account->save();

        return response()->json([
           "success" => true,
            "msg" => "Sikeresen megváltoztattad a jelszavad.",
            "pw" => $pw
        ]);
    }

    /**
     * @comment Jelszó megváltoztatása
     *
     * @param array $request
     * @return \Illuminate\Http\Response
     */
    public function forgotpassword(Request $request) {
        $input = json_decode($request->getContent());

        ChangePassword::query()
            ->where('ip','=',$request->ip())
            ->where('email','=',$input->email)
            ->where('creDate','<=', date('Y-m-d H:i:s'))
            ->delete();

        $pc = ChangePassword::query()
            ->where('ip','=',$request->ip())
            ->where('creDate','>=', date('Y-m-d H:i:s'))
            ->first();

        $account = Account::where('email','=',$input->email)
            ->first();

        $validate = ['email' => $input->email];
        $validator = Validator::make($validate, [
            'email' => 'required|email'
        ]);

        /**
         * @comment "Lekérdezések"
         * @modDate "2018.10.18"
        */
        $validator->after(function($validator) use($account, $pc) {
            if(empty($account)) {
                $validator->errors()->add('email', 'Nincs ilyen e-mail cím az adatbázisban!');
            } else if(count($pc)) {
                $validator->errors()->add('email', '30 percenként csak egy jelszóemlékeztetőt kérhetsz.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "msg" => $validator->errors()->first()
            ]);
        }

        $randomHash = md5($account->id + rand(0,20000));
        $randomHash = substr($randomHash, 0,8);
        $cachePassword = $randomHash;

        $pc = new ChangePassword;
        $pc->email = $input->email;
        $pc->creUserId = $request->session()->get('account');
        $pc->creDate = date('Y-m-d H:i:s', strtotime("+30 minutes", time()));
        $pc->save();

        $token = base64_encode(json_encode([
            "ip" => $request->ip(),
            "email" => $input->email
        ]));

        $url = $request->root().'/jelszo-valtoztatas?token='.$token;

        /*
         * @comment "Létre kell hozni egy cég specifikus e-mail címet, érdemes lenne domaint rendelni hozzá ..."
         *
         * @template
         * * MAIL_DRIVER=smtp
         * * MAIL_HOST=smtp.gmail.com
         * * MAIL_PORT=587
         * * MAIL_USERNAME=MyUsername@gmail.com
         * * MAIL_PASSWORD=MyPassword
        */

        $blacklistEmail = md5($input->email);
        Mail::send('email.forgot-password', [
            "account" => $account,
            "password" => $cachePassword,
            "url" => $url,
            "blacklistEmail" => $blacklistEmail
        ], function ($message) use ($input) {
            $message->from('oktatas@emailcimed.hu', 'Jelszó megváltoztatás');
            $message->to($input->email);
            //if(\App\Providers\Helper::isTest()) {
            //}
        });

        return response()->json([
            "success" => true
        ]);
    }

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $accountId = !$id ? $request->session()->get('account') : $id;
        $account = Account::find($accountId);
        return response()->json([
           "success" => true,
           "account" => $account
        ]);

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
