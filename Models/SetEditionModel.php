<?php
/**
 * SetEditionModel.php Does the queries for the set in the DB
 */
namespace Models;

class SetEditionModel extends AbstractModel {

    protected $table = 'set_edition';
    protected $fields = [
        'id',
        'set_name',
        'set'
    ];
    protected $values = [];

    /** get all sets */
    public function getAllSets()
    {
        return $this->GetAllEntries('set_name');
    }

    /** get set by id */
    public function getSetById($id)
    {
        try{
            $result = $this->getBySingleField('id', $id, 's');
            if($result->num_rows == 0){
                //print "false! ";
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

    /** set field values */
    public function setFieldValue($fieldName, $value)
    {
        if ( !in_array($fieldName, $this->fields)){
            return 'invalid field';
        }
        $this->values[$fieldName] = $value;
    }

    /** bind params not in use because i dont want to change or add  sets */

    protected function bindMyParams($stmt, $update = false)
    {
        // TODO: Implement bindMyParams() method.
    }
}






