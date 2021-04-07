<?php

namespace Models;

class FormatsModel extends AbstractModel {

    protected $table = 'formats';

    private $fields = [
        'id',
        'format',
        'cards',
        'sideboard'
    ];

    private $values = [];

    public function getFormatsById($id) {
        try{
            $result = $this->getBySingleField('id', $id, 's');
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

    protected function bindMyParams($stmt, $update = false)
    {
        // TODO: Implement bindMyParams() method.
    }
}






