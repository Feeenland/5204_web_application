<?php


class UsersHasCardsModel extends AbstractModel {

    protected $table = 'set_edition';

    private $fields = [
        'id',
        'users_id',
        'cards_id'
    ];

    private $values = [];

    public function getCardsByUser($user_id) {
        try{
            $result = $this->getBySingleField('id', $user_id, 's');
            if($result->num_rows == 0){
                print "false! MUser ";
                return false; //found nothing
            }else{
                print "fetch!? ";
                $dbValue = $result->fetch_assoc();
                foreach ($this->fields as $field){
                    if (array_key_exists($field, $dbValue))
                        $this->setFieldValue($field, $dbValue[$field]);
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
    // TODO add delete an save/update
}






