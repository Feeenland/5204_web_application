<?php

//include('models/MUser.php');

class CLoginGetUserByNickname  extends MUser{

    public function getUserByNickname() {

        // valid user?
        $usr = $this->getUsersByNickname($_REQUEST['nickname']); //if nickname = false = not necessary check password.
        //print $_REQUEST['nickname'];

        //print_r ($usr);

        if ($usr === false) {
            print "nick doesn't exist ";
            return false;
        }else{
            print "exist2 ";
            print_r($usr);
            return $usr;
        }

    }

}