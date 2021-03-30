<?php


class MUser extends AbstractModel {

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
                print "fetch! ";
                $dbusr = $result->fetch_assoc();

                foreach ($this->fields as $field){
                    if (array_key_exists($field, $dbusr))
                        $this->setFieldValue($field, $dbusr[$field]);
                }
                return true;
            }

        }catch(Exception $exception){
            die('Problem with database');
            //return false;
        }
    }

    public function setFieldValue($fieldname, $value){
        if ( !in_array($fieldname, $this->fields)){
            return 'invalid field';
        }
        $this->values[$fieldname] = $value;
    }

    public function getFieldValue($fieldname){
        if ( !in_array($fieldname, $this->fields)){
            return 'invalid field';
        }

        if (! array_key_exists($fieldname, $this->values)){
            return null;
        }

        return $this->values[$fieldname];

    }

    protected function save(){

        // $fields = ['name', 'password', 'email'];
        // $values = [$_request[], 'password', 'email'];

        // parent::saveValues($fields, $values);
    }

    public function toString(){
        $infos = '';
        foreach($this->values as $key => $value){
            $infos .= $key . ' - ' . $value . '<br>';

        }
        return $infos;
    }

}






