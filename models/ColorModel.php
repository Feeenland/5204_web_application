<?php


class ColorModel extends AbstractModel {

    protected $table = 'colors';

    private $fields = [
        'id',
        'color',
        'abbr',
        'basic_land'
    ];

    private $values = [];

    public function getColorById($id) {
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

    public function setFieldValue($fieldName, $value){
        if ( !in_array($fieldName, $this->fields)){
            return 'invalid field';
        }
        $this->values[$fieldName] = $value;
    }
}






