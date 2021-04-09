<?php

namespace Controllers;

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

    public function __construct()
    {
        if(isset($_POST['login_try']) )
        {
            $nick = $_REQUEST['nickname'];
            $pwd = $_REQUEST['password'];

            $this->doLoginAttempt($nick, $pwd);
        }else{
            $view = $this->view = new LoginView();
            $view->showTemplate();
        }
    }

    public function showLoginForm()
    {
        if (isset($_SESSION['logged_in'])) { // this case should not happen
            $_SESSION['logged_in'] == 1;
            $this->infos = 'Sie sind bereits eingeloggt';
            $view = $this->view = new HomeView();
            $this->view->addInfos($this->infos);
            $this->view->addErrorMessages($this->errorMessages);
            $view->showTemplate();
        } else {
            $view = $this->view = new LoginView();
            $this->view->addInfos($this->infos);
            $this->view->addErrorMessages($this->errorMessages);
            $view->showTemplate();
        }
    }

    public function doLoginAttempt($nick, $pwd)
    {

        // valid user?
        $usr = new UserModel();
        $usr->getUsersByNickname($nick);
        print $nick;
        print_r ($usr);
        if ($usr == false) { // TODO it returns an object not true or false why!!!!!!
            // Return fail message
            print 'false usr';
            $this->infos = 'fehlerhafter login versuch, etwas wurde falsch eingegeben'; // mail is wrong
            $this->errorMessages = 'Etwas wurde falsch eingegeben!';

            $this->showLoginForm();
        } else {
            if($usr->getFieldValue('banned_at') == null || $usr->getFieldValue('login_try') <= 1) {
                // check password
                //if (password_verify($pwd, $usr['password'])) { //match the pw with pw in DB //TODO add the pw hash
                if ($pwd == $usr['password']) { //match the pw with pw in DB
                    // reset login_try, if there are any
                    if ($usr['login_try'] != 0) {
                        $usr->setFieldValue('login_try', 0);
                    }
                    //print 'pw down';
                    // start session = user is logged in
                    $_SESSION['logged_in'] = 1;
                    $this->infos = 'Hallo ' . $usr['name'] . ' ' . $usr['nickname'] . '<br> sie haben sich korrekt eingeloggt';

                    //die('correct password'); //logged in
                    $view = $this->view = new UserView();
                    $this->view->addInfos($this->infos);
                    $view->showTemplate();

                } else {
                    $this->checkIfUserIsBanned($usr);
                    // increase failure counter
                    $fails = $usr['login_try'] + 1; //every try +1, and banned at 3
                    // check failure counter, ban if needed
                    if ($fails >= $this->login_tries) { // user get banned, add timestamp in the field banned_at in the DB.
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
                        // update login_try ++ in DB.
                        $values['login_try'] = +1;
                        $usr->updateSave($values); //TODO user update login_try
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

    private function checkIfUserIsBanned($usr){
        // is user banned?
        if ($usr['banned_at'] != null) {
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
                updateUserField($usr['id'], 'banned_at', null, 's');
                updateUserField($usr['id'], 'login_try', 0, 'i');
                $usr['login_try'] = 0;
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

