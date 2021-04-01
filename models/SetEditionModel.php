<?php


class SetEditionModel extends AbstractModel {

    protected $table = 'set_edition';

    private $fields = [
        'id',
        'set_name',
        'set'
    ];

    private $values = [];

    public function getSetById($id) {
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
    // TODO add save => if there are new releases
}






