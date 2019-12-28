<?php

namespace App\Console\Commands;

use App\TestInput;
use Illuminate\Console\Command;
use App\ModuleConst;
use App\Test;
use App\Answer;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\DB;
use App\Account;
use App\Company;
use App\conAccountCompany;
use App\EmailNotification;
use App\BlockedEmail;
use Mail;

class cronEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Körlevél a kitöltetlen tesztekről.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /**
         * @comment "Az összes teszt lekérdezése, foreach majd id szerint az @var $answer->testId-t kicserélni, lekérdezni az adott cég alapján a tulajt majd annak
         * kiküldeni a válaszokat."
         * @modDate "2018.11.02"
         */

        $questions = Test::query()->get()->all();
        $companies = Company::query()->get()->all();

        foreach($companies as $company) {
            $processedTests = [];
            $success = [];
            $overallTests = [];


            $token = [];
            $token['workers'] = [];

            $testemails = [];
            $gatherTestIds = [];

            /** @Kérdés + Helyes válasz arány */
            $questionAndAnswers = [];
            $sendEmail = false;
            foreach($questions as $qkey => $uquestion) {
                $tests = conAccountCompany::query()
                    ->with(['user.answers' => function ($q) use ($uquestion) {
                        //$q->where('answer.testId', '=', DB::raw($uquestion->id));
                        //$q->where('answer.state', '<>', ModuleConst::STATE_REMOVED);
                    }])
                    ->where('conaccountcompany.companyId', '=', $company->id)
                    ->where('conaccountcompany.state', '<>', ModuleConst::STATE_REMOVED)
                    ->where('conaccountcompany.permission', '=', ModuleConst::PERMISSION_NORMAL)
                    ->get();

                foreach($tests as $accountKey => $test) {

                    /**
                     * @Tesztek szummázása
                     *
                     * @var $test->user->answers (Felhasználó válaszai)
                     * @var $test->user (Felhasználó adatai)
                     *
                     * @lastModDate "2018.11.18"
                     */


                    /** @Ha megegyezik a megválaszolt kérdés testId-je, a kérdés Id-vel. */




                    if(!isset($questionAndAnswers[$accountKey]['correct'])) {
                        $questionAndAnswers[$accountKey]['correct'] = 0;
                    }
                    if(!isset($questionAndAnswers[$accountKey]['question'])) {
                        $questionAndAnswers[$accountKey]['question'] = 0;
                    }


                    if(count($test->user->answers)) {
                        foreach($test->user->answers as $answer) {
                            if($uquestion->id == $answer->testId) {
                                $questionAndAnswers[$accountKey]['questionIds'] = $answer->testId;
                                if(isset($answer->testId)) {
                                    ++$questionAndAnswers[$accountKey]['question'];
                                    /** @Ha a válasz korrekt, akkor +1 értéket adunk hozzá. */
                                    if($answer->correct) {
                                        ++$questionAndAnswers[$accountKey]['correct'];
                                    }
                                } else {
                                    $inputCount = TestInput::query()->where('testId',$answer->testId)->where('state','<>',ModuleConst::STATE_REMOVED)->count();
                                    $questionAndAnswers[$accountKey]['question'] += $inputCount;
                                }
                            }
                        }
                    }

                    $questionIds = [];
                    foreach($questions as $question) {
                        $questionIds[] = $question->id;
                    }
                }
            }

            foreach($questionAndAnswers as $accountId => $qa) {

                /** @Az összesített adatok százaléko arányban való meghatározása. */
                $overallCount = Answer::query()
                    ->groupBy('testId')
                    ->where('creUserId',$tests[$accountId]->user->id)
                    ->get();



                if(!count($tests[$accountId]->user->answers)) {
                    /** @Ha nem töltött ki egy tesztet se. */
                    $tests[$accountId]['notFilledAtAll'] = true;
                    $sendEmail = true;
                    if(!isset($token['workers'][$tests[$accountId]->user->id])) {
                        $token['workers'][$accountId] = $tests[$accountId]->user;
                    }
                } else if(count($overallCount) < count($questionIds)) {
                    /** @Ha részlegesen töltötte ki a teszteket. */
                    $tests[$accountId]['notFilledCompletely'] = true;
                    $sendEmail = true;
                    if(!isset($token['workers'][$tests[$accountId]->user->id])) {
                        $token['workers'][$accountId] = $tests[$accountId]->user;
                    }
                } else {
                    $tests[$accountId]['successRate'] = 0;
                    $tests[$accountId]['successRate'] = number_format(($qa['correct'] / $qa['question']) * 100, 0);
                    if($tests[$accountId]['successRate']<85) {
                        $sendEmail = true;
                        if(!isset($token['workers'][$tests[$accountId]->user->id])) {
                            $token['workers'][$accountId] = $tests[$accountId]->user;
                        }

                        /*if(isset($tests[$accountId]['questionIds'])) {
                            Answer::query()
                                ->whereIn('testId', $tests[$accountId]['questionIds'])
                                ->delete();
                        }*/
                    }
                }
            }

            /*
            $successList = [];
            foreach($tests as $key => $test) {
                $successList[$key] = [
                    'successRate' => $test['successRate'],
                    'notFilledAtAll' => $test['notFilledAtAll'],
                    'notFilledCompletely' => $test['notFilledCompletely']
                ];
            }
            */
            $token['company'] = $company;
            $token['questionIdsAmount'] = count($questionIds);


            /**
             * @comment "Cégvezetői email címek összegyűjtése"
             * @modDate "2018.11.02"
             */
            $users = conAccountCompany::query()
                ->select('account.*')
                ->leftJoin('account', 'account.id', '=', 'conaccountcompany.accountId')
                ->where('conaccountcompany.companyId', '=', DB::raw($company->id))
                ->where('conaccountcompany.permission', '=', ModuleConst::PERMISSION_COMPANY_OWNER)
                ->get()->all();

            $emailArray = [];
            foreach ($users as $key => $user) {
                /*
                 * Csekkolás hogy az email biztosan e-mail.
                */
                if (strpos($user->email, '@') !== FALSE && strpos($user->email, '.') !== FALSE) {
                    $emailArray[$key] = $user->email;

                    /**
                     * @comment "E-mail értesítés adatbázisba bejegyzése."
                     * @modDate "2018.11.07"
                     */
                    $emailnotification = new EmailNotification;
                    $emailnotification->data = json_encode($token);
                    $emailnotification->creUserId = $user->id;
                    $emailnotification->save();
                }
            }

            $title = 'Tűz & Munkavédelem';

            $from = [];
            $from[] = 'Tűz és Munkavédelmi Oktatás';
            $from[] = $title;
            $from[] = 'Teszteredmények';

            if (!empty($users) && $sendEmail) {

                foreach ($emailArray as $key => $email) {
                    /**
                     * @comment "Csekkolás hogy az alkalmazott kikapcsolta-e az e-mail küldést."
                     * @modDate "2018.11.07"
                     */
                    $blockedemail = BlockedEmail::query()->where('email','=',$email)->first();
                    if($blockedemail==null) {
                        $blacklistEmail = md5($email);
                        Mail::send('email.teszt-lista', [
                            "company" => $company,
                            "tests" => $tests,
                            "token" => md5($emailnotification->id),
                            "account" => $users[$key],
                            "blacklistEmail" => $blacklistEmail
                        ], function ($message) use ($email, $from) {
                            $message->from('emailcimed@gmail.com', 'Tűz és Munkavédelmi Oktatás');
                            $message->to($email);
                            //if(\App\Providers\Helper::isTest()) {
                            //}
                            $message->subject('Teszteredmények');
                        });
                    }
                }
            }
        }
    }
}
