<?php

namespace Controllers;

class LoginController
{
    private $login_tries = 3; // 3 tries get the user to log in
    private $ban_time = 60 * 60; // in seconds = 1h will the user be banner by 3 fails
    private $view;

    public function __construct()
    {
        $this->view = new LoginView();
    }

    public function showLoginForm()
    {
        if (isset($_SESSION['logged_in'])) { // this case should not happen
            $_SESSION['logged_in'] == 1;
            $content = 'Sie sind bereits eingeloggt';
        } else {
            $page = 'templates/forms/login.php';
        }
        // Show login page
    }
/*
    public function doLoginAttempt($nick, $pwd)
    {
        // valid user?
        $usr = new MUser();
        $usr->getUsersByNickname($nick);

        if ($usr == false) {
            // Return fail message
            // $login_output = 'fehlerhafter login versuch, etwas wurde falsch eingegeben'; // mail is wrong
            $this->view->assignValue('login_output', 'fehlerhafter login versuch, etwas wurde falsch eingegeben');

            $page = 'templates/forms/login.php';
            //die('wrong user');
            $email = desinfect($_POST['email']);
            // $errorMessage = 'Etwas wurde falsch eingegeben!';
            $this->view->assignValue('error_message', 'Etwas wurde falsch eingegeben!');
        } else {
            if($usr->getFieldValue('banned_at') == null || $usr->getFieldValue('login_try') <= 1) {
                // check password
                if (password_verify($_REQUEST['password'], $usr['password'])) { //match the pw with pw in DB
                    // reset login_try, if there are any
                    if ($usr['login_try'] != 0) {
                        $usr->setFieldValue('login_try', 0);
                    }
                    //print 'pw down';
                    // start session = user is logged in
                    $_SESSION['logged_in'] = 1;
                    if ($usr['role'] == 'admin') {
                        $_SESSION['role'] = 1;
                    }
                    $login_output = 'Hallo ' . $usr['first_name'] . ' ' . $usr['last_name'] . '<br> sie haben sich korrekt eingeloggt';
                    $page = 'templates/admin/admin.php';
                    //die('correct password'); //logged in
                } else {
                    // increase failure counter
                    $fails = $usr['login_try'] + 1; //every try +1, and banned at 3
                    // check failure counter, ban if needed
                    if ($fails >= $login_tries) { // user get banned, add timestamp in the field banned_at in the DB.
                        updateUserField($usr['id'], 'banned_at', date('Y-m-d H:i:s'), 's');
                        $login_output = 'Aufgrund zu vieler falscher login versuche wurden sie gebannt <br>
                                    versuchen sie es später erneut! <br>
                                    zeit der Bannung: ' . date('Y-m-d H:i:s');
                        $page = 'templates/forms/login.php';
                        $email = desinfect($_POST['email']);
                        $errorMessage = 'Gebannt! um: ' . date('Y-m-d H:i:s');
                        //die('ban user: ' . date('Y-m-d H:i:s'));
                    } else {
                        // update login_try ++ in DB.
                        updateUserField($usr['id'], 'login_try', $fails, 'i');
                        $login_output = 'fehlerhafter login versuch, etwas wurde falsch eingegeben';
                        $page = 'templates/forms/login.php';
                        //die('mistake counter incremented');
                        $email = desinfect($_POST['email']);
                        $errorMessage = 'Etwas wurde falsch eingegeben!';
                    }
                }
            }
        }
    }*/

    private function checkIfUserIsBanned($usr){
        // is user banned?
        if ($usr['banned_at'] != null) {
            $banned_at = date_create_from_format($this->db_datetime_format, $usr['banned_at']);
            $now = new DateTime();
            $interval = $now->getTimestamp() - $banned_at->getTimestamp(); // now - banned_at = how log is he banned jet.
            //print $interval.'and'.$ban_time;
            if ($interval <= $this->ban_time) { //still banned
                $this->view->assignValue('login_output', 'Aufgrund zu vieler falscher login versuche wurden sie gebannt <br>
                                    versuchen sie es später erneut! ');
                $page = 'templates/forms/login.php';
                $this->view->assignValue('error_message', 'Immernoch Gebannt ! ');
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
        $login_output = 'Sie wurden ausgeloggt';
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
 * Eerrormessage per AJAx
 * {
 *      success : false,
 *      errors : [
 *        { 'username' : 'Der Benutzername muss eingegeben werden' } ,
 *        { 'password' : 'Das eingegebene passwort ist ungueltig' }
 *      ]
 * }
 *
 *
 *
 *
 */



/**
 * This file controls the login.
 * the user has 3 tries to log in correctly if he writes the password wrong 3time he get banned for a hour.
 */

