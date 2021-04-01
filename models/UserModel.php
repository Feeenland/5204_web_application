<?php


class UserModel extends AbstractModel {

    protected $table = 'users';

    private $fields = [
        'id',
        'name',
        'nickname',
        'favourite_card',
        'password',
        'banned_at',
        'login_try'
    ];

    private $values = [];

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

    public function setFieldValue($fieldName, $value){
        if ( !in_array($fieldName, $this->fields)){
            return 'invalid field';
        }
        $this->values[$fieldName] = $value;
    }

    public function getFieldValue($fieldName){
        if ( !in_array($fieldName, $this->fields)){
            return 'invalid field';
        }
        if (! array_key_exists($fieldName, $this->values)){
            return null;
        }
        return $this->values[$fieldName];
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
                print 'Speichern fehlgeschlagen ';
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

    public function toString(){
        $infos = '';
        foreach($this->values as $key => $value){
            $infos .= $key . ' - ' . $value . '<br>';

        }
        return $infos;
    }


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






