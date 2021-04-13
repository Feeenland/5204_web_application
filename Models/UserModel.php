<?php

namespace Models;

class UserModel extends AbstractModel {

    protected $table = 'users';

    protected $fields = [
        'id',
        'name',
        'nickname',
        'favourite_card',
        'password',
        'banned_at',
        'login_try'
    ];

    protected $values = [];

    //protected $id = $values['id'];

    public function getUsersByNickname($nickname) {

        try{
            $result = $this->getBySingleField('nickname', $nickname, 's');

            if($result->num_rows == 0){
                print "false! MUser ";
                return false; //found nothing
            }else{
                print "fetch!? ";
                $dbUsr = $result->fetch_assoc();
                foreach ($this->fields as $field){
                    if (array_key_exists($field, $dbUsr))
                        $this->setFieldValue($field, $dbUsr[$field]);
                }
                return true;
            }
        }catch(Exception $exception){
            die('Problem with database');
            //return false;
        }
    }

    protected function bindMyParams($stmt, $update = false)
    {
        // SQL statement: UPDATE users SET
        // name = ? ,
        //nickname = ? ,
        //favourite_card = ? ,
        //password = ? ,
        //login_try = ? WHERE id = ?
        // with or without ID
        $types = $update ? 'sssssii' : 'sssssi';
        $name = $this->getFieldValue('name');
        $nickname = $this->getFieldValue('nickname');
        $favourite_card = $this->getFieldValue('favourite_card');
        $password = $this->getFieldValue('password');
        $banned_at = $this->getFieldValue('banned_at');
        $login_try = $this->getFieldValue('login_try');
        $id = $this->getFieldValue('id');
        if($update){
            $stmt->bind_param($types, $name, $nickname, $favourite_card, $password,  $banned_at, $login_try, $id);
            print $types.'<br>'. $name.'<br>'. $nickname.'<br>'. $favourite_card.'<br>'. $password.'<br>'.  $banned_at. '<br>'.$login_try.'<br>'. $id;
        }else{
            $stmt->bind_param($types, $name, $nickname, $favourite_card, $password,  $banned_at, $login_try);
            print $types.'<br> name: '. $name.'<br> nick: '. $nickname.'<br> card: '.
                $favourite_card.'<br> pw: '. $password.'<br> banned: '.  $banned_at. '<br> login: '.$login_try. '<br>';
        }
    }

    public function updateSave($values){
        try{
            foreach ($this->fields as $field){ //put the incoming values in to $this->values
                if (array_key_exists($field, $values))
                    $this->setFieldValue($field, $values[$field]);
            }
            print $this->toString();
            $result = $this->saveValues($this->fields, $this->values, $this->values['id']);
            if ($result == false) {
                print ' Speichern fehlgeschlagen ';
                //die('Speichern fehlgeschlagen');
            } else {
                print " worked! ";
                return true;
            }
        }catch(Exception $exception){
            die('Problem with database');
            //return false;
        }
    }

    // only in use if i add a admin Sometime
    public function delete($id){
        try{
            $this->deleteByID($id);
            print "deleted";
        }catch(Exception $exception){
            die('Problem with database');
            //return false;
        }
    }

}






