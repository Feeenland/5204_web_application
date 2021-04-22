<?php

namespace Models;

class ColorModel extends AbstractModel {

    protected $table = 'colors';

    protected $fields = [
        'id',
        'color',
        'abbr',
        'basic_land'
    ];

    protected $values = [];

    public function getAllColors(){

        return $this->GetAllEntries();
    }

    public function getColorById($id) {
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






