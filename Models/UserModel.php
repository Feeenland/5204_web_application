<?php
/**
 * UserModel.php Does the queries for the colors in the DB
 */
namespace Models;
use mysqli;

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

    /** get user by nickname */
    public function getUsersByNickname($nickname)
    {
        try{
            $result = $this->getBySingleField('nickname', $nickname, 's');
            if($result->num_rows == 0){
                //print "false! MUser ";
                return false; //found nothing
            }else{
                //print "fetch!? ";
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

    /** bind params */
    protected function bindMyParams($stmt, $update = false)
    {
        // SQL statement: UPDATE users SET
        // name = ? , nickname = ? , favourite_card = ? , password = ? , login_try = ? WHERE id = ?
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
        }else{
            $stmt->bind_param($types, $name, $nickname, $favourite_card, $password,  $banned_at, $login_try);
        }
        return $stmt;
    }

    /** save / update user */
    public function updateSave($values)
    {
        try{
            foreach ($this->fields as $field){ //put the incoming values in to $this->values
                if (array_key_exists($field, $values))
                    $this->setFieldValue($field, $values[$field]);
            }
            //print $this->toString();
            $result = $this->saveValues($this->fields, $this->values, isset($this->values['id']));
            if ($result == false) {
                print ' Speichern fehlgeschlagen ';
                //die('Speichern fehlgeschlagen');
            } else {
                //print " worked! ";
                return true;
            }
        }catch(Exception $exception){
            die('Problem with database');
            //return false;
        }
    }

    /** delete user, only in use if i add a admin Sometime */
    public function delete($id)
    {
        try{
            $this->deleteByID($id);
            //print "deleted";
        }catch(Exception $exception){
            die('Problem with database');
            //return false;
        }
    }
}






