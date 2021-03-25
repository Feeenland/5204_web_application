<?php

include('models/MUser.php');
include('CLoginGetUserByNickname.php');

class CLogin extends CLoginGetUserByNickname{

    public $login_tries = 3; // 3 tries get the user to log in
    public $ban_time = 60 * 60; // in seconds = 1h will the user be banner by 3 fails
    public $db_datetime_format = 'Y-m-d H:i:s'; // date format = 2021-01-13 11:06:37

    public $login_output = ''; // gives out what happened (fail ty, banned)
    public $nickname = '';
    public $errorMessage = '';


    public function userLogin() {

        if(isset($_POST['login_try']) ) {

            $UserByNick = $this->getUserByNickname();

            print_r ($UserByNick);

            if ($UserByNick === false) { //nickname doesn't exist
                $this->login_output = 'fehlerhafter login versuch, etwas wurde falsch eingegeben';
                $this->page = 'views/login.php';
                $this->nickname = disinfect($_POST['nickname']);
                $this->errorMessage = 'Etwas wurde falsch eingegeben!';
            } else{ //nickname exist
                print "nick exist";

            }
        }else{
            print "no login_try";
        }




    }


}


/*class ViewUser extends MUser {


    public function showUser()
    {
        $datas = $this->getUsersByNickname('test');
        foreach ($datas as $data) {
            echo $data['nickname'] . "<br>";
            echo $data['name'] . "<br>";
        }
    }

}*/



$page = 'views/login.php';