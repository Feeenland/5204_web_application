<?php


class MUser extends Config{

    protected function getUsersByNickname($nickname) {

        try{
            $sql = "SELECT * FROM users WHERE nickname = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bind_param('s', $_nickname);
            $_nickname = disinfect($nickname);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows == 0){
                print "false! MUser ";
                return false; //found nothing
            }else{
                print "fetch! ";
                return $result->fetch_assoc();
            }

        }catch(Exception $exception){
            die('Problem with database');
            //return false;
        }
    }

}






