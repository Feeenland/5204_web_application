<?php

namespace Controllers;

use Helpers\disinfect;
use Helpers\Validation;
use Models\UserModel;
use Views\HomeView;
use Views\LoginView;
use Views\UserView;

class LoginController
{
    protected $login_tries = 3; // 3 tries get the user to log in
    protected $ban_time = 60 * 60; // in seconds = 1h will the user be banner by 3 fails
    protected $view;
    protected $infos;
    protected $errorMessages;

    protected $fields = [
        'name',
        'nickname',
        'favourite_card',
        'password',
    ];

    public $rules = [
        'nickname' => ['required'],
        'password' => ['required']
    ];

    public function __construct()
    {
        if(isset($_POST['login_try']) )
        {
            $nick = $_REQUEST['nickname'];
            $pwd = $_REQUEST['password'];

            $d = new Disinfect();
            $nick = $d->disinfect($nick);
            $pwd = $d->disinfect($pwd);

            $this->doLoginAttempt($nick, $pwd);
        }else{
            $view = $this->view = new LoginView();
            $view->showTemplate();
        }
    }

    public function showLoginForm()
    {
        if (isset($_SESSION['logged_in'])) { // this case should not happen
            //$_SESSION['logged_in'] == 1;
            $this->infos = 'Sie sind bereits eingeloggt';
            $view = $this->view = new UserView();
            $this->view->addInfos($this->infos);
            $this->view->addErrorMessages($this->errorMessages);
            $view->showTemplate();
        } else {
            $view = $this->view = new LoginView();
            // $this->view->addInfos($this->infos);
            $this->view->addToKey('infos', $this->infos);
            $this->view->addErrorMessages($this->errorMessages);
            $view->showTemplate();
        }
    }

    public function doLoginAttempt($nick, $pwd)
    {
        session_destroy();
        // valid user?

        $valid = new Validation();
        $errors = $valid->validateFields($this->rules);

        $usr = new UserModel();
        $userfound =$usr->getUsersByNickname($nick);
        //var_dump($usr);
        //die();
        print $nick;
        if ($userfound == false) {
            // Return fail message
            print 'false usr';
            $this->infos = 'fehlerhafter login versuch, etwas wurde falsch eingegeben'; // nickname is wrong
            $this->errorMessages = 'Etwas wurde falsch eingegeben!';

            $this->showLoginForm();
        } else {
            if($usr->getFieldValue('banned_at') == null || $usr->getFieldValue('login_try') <= 1) {
                // check password
                //if (password_verify($pwd, $usr['password'])) { //match the pw with pw in DB //TODO add the pw hash
                if ($pwd == $usr->getFieldValue('password')) { //match the pw with pw in DB
                    // reset login_try, if there are any
                    if ($usr->getFieldValue('login_try') != 0) {
                        print 'set login try to 0' . '<br>';
                        $values['login_try'] = $usr->setFieldValue('login_try', 0);
                        $values['id'] = $usr->getFieldValue('id');
                        $usr->updateSave($values);
                    }
                    //print 'pw down';
                    // start session = user is logged in
                    $_SESSION['logged_in'] = 1;
                    print 'session 1, alles richtig' . '<br>';
                    $this->view = new UserView();
                    $this->infos = 'Hallo ' . $usr->getFieldValue('name') . ' ' . $usr->getFieldValue('nickname') . '<br> sie haben sich korrekt eingeloggt';

                    //die('correct password'); //logged in
                    $this->view->addInfos($this->infos);
                    $this->view->showTemplate();
                    //$this->showLoginForm();
                    $p = 'user';

                } else {
                    $this->checkIfUserIsBanned($usr);
                    print 'check user banned?' . '<br>';
                    // increase failure counter
                    $fails = $usr->getFieldValue('login_try')+ 1; //every try +1, and banned at 3
                    // check failure counter, ban if needed
                    if ($fails >= $this->login_tries) { // user get banned, add timestamp in the field banned_at in the DB.
                        print 'user wird gebannt ?' . '<br>';
                        $values['banned_at'] =  date('Y-m-d H:i:s');
                        $usr->updateSave($values); //TODO user update login_try
                        //updateUserField($usr['id'], 'banned_at', date('Y-m-d H:i:s'), 's');
                        $this->infos = 'Aufgrund falscher login versuche wurden sie gebannt <br>
                                 versuchen sie es später erneut! <br> zeit der Bannung: ' . date('Y-m-d H:i:s');
                        $this->errorMessages = 'Gebannt! um: ' . date('Y-m-d H:i:s');
                        //die('ban user: ' . date('Y-m-d H:i:s'));

                        $view = $this->view = new LoginView();
                        $this->view->adderrorMessages($this->errorMessages);
                        $this->view->addInfos($this->infos);
                        $view->showTemplate();
                    } else {
                        print 'update login try' . '<br>';
                        // update login_try ++ in DB.
                        $values['login_try'] = $fails;
                        $values['id'] = $usr->getFieldValue('id');
                        print_r($values);
                        //$usr->setFieldValue('login_try', $fails);
                        print 'set field value login try' . '<br>';
                        $usr->updateSave($values);
                        //$usr->save(); //TODO user update login_try
                        //updateUserField($usr['id'], 'login_try', $fails, 'i');
                        $this->infos = 'fehlerhafter login versuch, etwas wurde falsch eingegeben';
                        $this->errorMessages = 'Etwas wurde falsch eingegeben!';
                        //die('mistake counter incremented');

                        $this->showLoginForm();
                    }
                }
            }
        }
    }

    private function checkIfUserIsBanned(UserModel $usr){
        // is user banned?
        if ($usr->getFieldValue('banned_at') != null) {
            $banned_at = date_create_from_format($this->db_datetime_format, $usr['banned_at']);
            $now = new DateTime();
            $interval = $now->getTimestamp() - $banned_at->getTimestamp(); // now - banned_at = how log is he banned jet.
            //print $interval.'and'.$ban_time;
            if ($interval <= $this->ban_time) { //still banned
                $this->infos = 'Aufgrund zu vieler falscher login versuche wurden sie gebannt <br>
                                    versuchen sie es später erneut! ';
                $this->errorMessages ='Immernoch Gebannt ! ';

                $this->showLoginForm();
                return true;
            } else { // waited long enough = reset the field banned_at an login_try in the DB.
                $usr->setFieldValue('banned_at',null);
                $usr->setFieldValue('login_try', 0);
                $usr->save();
                return false;
            }

        }
    }

    public function doLogout()
    {
        session_destroy();
        $infos = 'Sie wurden ausgeloggt';
        $page = 'templates/forms/login.php';
    }
}

/** [}]{{}
 * Response fr Browser
 *
 * div
 *  h2 Login erfolgreich /h2
 *  p Herzlich willkommen Laura /p
 * /div
 *
 * Response fuer javascript / ajax
 * {
 *  success : true,
 *  message : {
 *      title : "Login erfolgreich",
 *      text : "Herzlich willkommen Laura"
 *      },
 *  user : {
 *      nickname : "Laura"
 *      }
 *  }
 *
 * EerrorMessages per AJAx
 * {
 *      success : false,
 *      errors : [
 *        { 'username' : 'Der Benutzername muss eingegeben werden' } ,
 *        { 'password' : 'Das eingegebene passwort ist ungueltig' }
 *      ]
 * }
 */

/**
 * This file controls the login.
 * the user has 3 tries to log in correctly if he writes the password wrong 3time he get banned for a hour.
 */

