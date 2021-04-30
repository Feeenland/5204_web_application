<?php

namespace Models;

class FormatsModel extends AbstractModel {

    protected $table = 'formats';

    public $fields = [
        'id',
        'format',
        'cards',
        'sideboard'
    ];

    public $values = [];

    public function getAllFormats(){

        return $this->GetAllEntries('id');
    }

    public function getFormatsById($id) {
        try{
            $result = $this->getBySingleField('id', $id, 's');
            if($result->num_rows == 0){
                //print "false!";
                return false; //found nothing
            }else{
                //print "fetch!? ";
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






