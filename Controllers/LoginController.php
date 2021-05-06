<?php
/**
 * LoginController.php checks the login.
 */
namespace Controllers;

use DateTime;
use Helpers\disinfect;
use Helpers\Validation;
use Models\UserModel;
use Views\DecksView;
use Views\HomeView;
use Views\LoginView;
use Views\UserView;

class LoginController
{
    protected $login_tries = 3; // 3 tries get the user to log in
    protected $ban_time = 60 * 60; // in seconds = 1h will the user be banner by 3 fails
    protected $db_datetime_format = 'Y-m-d H:i:s';
    protected $view;
    protected $infos;
    protected $errorMessages;
    protected $fields = [
        'nickname',
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
        }else if (isset($_GET['action'])){
            switch($_GET['action']){    // if already logged in then log out.
                case 'logout':
                    $_SESSION = [];
                    session_destroy();
                    session_unset();
                    //print 'destroyed';
                    $info= 'You have been logged out';
                    $view = $this->view = new LoginView();
                    $this->view->addInfos($info);
                    $view->showTemplate();
                    break;
                default:
                    if(isset($_SESSION)){ // this case should not happen
                        $info= 'You are logged in !';
                        $view = $this->view = new DecksView();
                        $this->view->addInfos($info);
                        $view->showTemplate();
                    }else{
                        $c = new HomeController();
                    }
            }
        }else{
            $view = $this->view = new LoginView();
            if (isset($_GET['info'])){
                if ($_GET['info'] == 'forgotPw'){
                    $view->addInfos('Your password has changed, log in !');
                }
                if ($_GET['info'] == 'Register'){
                    $view->addInfos('Your are Registered, log in !');
                }
            }
            $view->showTemplate();
        }
    }

    /** check login attempt */
    public function doLoginAttempt($nick, $pwd)
    {
        $valid = new Validation();
        $errors = $valid->validateFields($this->rules);
        $usr = new UserModel();
        $userfound =$usr->getUsersByNickname($nick);
        if ($userfound == false ||  count($errors) != 0) { // errors
            if ($userfound == false){
                $generalerr ='this user does not exist!';
            }else{
                $generalerr ='something was entered incorrectly!';
            }
            $info ='Please enter Your correct login data!';

            return $this->showErrorsValues(
                $info, $generalerr, $errors,
                [
                    'nickname' => $_REQUEST['nickname'],
                    'password' => $_REQUEST['password'],
                ]);
        } else { //no errors from feedback

            if ($usr->getFieldValue('banned_at') != null){ // if user is banned

                $banned_at = date_create_from_format($this->db_datetime_format, $usr->getFieldValue('banned_at'));
                $now = new DateTime();
                $interval = $now->getTimestamp() - $banned_at->getTimestamp(); // now - banned_at = how log is he banned jet.

                if ($interval <= $this->ban_time) { //still banned
                    $info = 'You were banned due to incorrect login attempts,
                                 try again later!';
                    $generalerr = 'still banned !';

                    return $this->showErrorsValues(
                        $info, $generalerr, $errors,
                        [
                            'nickname' => $_REQUEST['nickname'],
                            'password' => $_REQUEST['password'],
                        ]);

                } else { // waited long enough = reset the field banned_at an login_try in the DB.

                    $values['login_try'] = null;
                    $values['banned_at'] = null;
                    $values['id'] = $usr->getFieldValue('id');
                    $usr->updateSave($values);


                    if (password_verify($pwd, $usr->getFieldValue('password'))) { //pw check!

                        $userId =$usr->getFieldValue('id');
                        $userNick =$usr->getFieldValue('nickname');
                        $_SESSION['userId'] =$userId;
                        $_SESSION['userNick'] =$userNick;

                        echo json_encode(array(
                            'status' => true
                        ));
                        return true;
                    } else{
                        $generalerr ='Something was entered incorrectly!';
                        $info = 'Something was entered incorrectly!';

                        return $this->showErrorsValues(
                            $info, $generalerr, $errors,
                            [
                                'nickname' => $_REQUEST['nickname'],
                                'password' => $_REQUEST['password'],
                            ]);
                    }
                }
            }else if($usr->getFieldValue('banned_at') == null || $usr->getFieldValue('login_try') <= 1) { // user is not banned
                // check password
                if (password_verify($pwd, $usr->getFieldValue('password'))) { //match the pw with pw in DB
                    if ($usr->getFieldValue('login_try') != 0) { // reset login_try, if there are any
                        //print 'set login try to 0' . '<br>';
                        $values['login_try'] = null;
                        $values['id'] = $usr->getFieldValue('id');
                        $usr->updateSave($values);
                    }
                    $userId =$usr->getFieldValue('id');
                    $userNick =$usr->getFieldValue('nickname');
                    $_SESSION['userId'] =$userId;
                    $_SESSION['userNick'] =$userNick;

                    echo json_encode(array(
                        'status' => true
                    ));
                    return true;

                } else { //pw not correct
                    // increase failure counter
                    $fails = $usr->getFieldValue('login_try')+ 1; //every try +1, and banned at 3
                    // check failure counter, ban if needed
                    if ($fails >= $this->login_tries) { // user get banned, add timestamp in the field banned_at in the DB.
                        $values['banned_at'] =  date('Y-m-d H:i:s');
                        $values['login_try'] = null;
                        $usr->updateSave($values);

                        $generalerr ='You are Banned try later again! ';
                        $info = 'You were banned due to incorrect login attempts,
                             try again later!  time of banishment:' . date('Y-m-d H:i:s');

                        return $this->showErrorsValues(
                            $info, $generalerr, $errors,
                            [
                                'nickname' => $_REQUEST['nickname'],
                                'password' => $_REQUEST['password'],
                            ]);
                    } else {//user can try again, login_try +1
                        $fails = $usr->getFieldValue('login_try')+ 1;
                        $values['login_try'] = $fails;
                        $values['id'] = $usr->getFieldValue('id');
                        $usr->updateSave($values);

                        $generalerr ='Something was entered incorrectly!';
                        $info = 'Something was entered incorrectly!';

                        return $this->showErrorsValues(
                            $info, $generalerr, $errors,
                            [
                                'nickname' => $_REQUEST['nickname'],
                                'password' => $_REQUEST['password'],
                            ]);
                    }
                }
            }
        }
    }

    /** show errors */
    public function showErrorsValues($info, $generalerr, $errors = [], $values = [])
    {
        echo json_encode(array(
            'status' => 'error',
            'errors' => $errors,
            'generalerr' => $generalerr,
            'info' => $info,
            'values' => $values
        ));
        return [
            'errors' => $errors,
            'values' => $values
        ];
    }
}

