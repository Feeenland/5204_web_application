<?php
/**
 * FormatsModel.php Does the queries for the colors in the DB
 */
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

    /** get all Formats */
    public function getAllFormats()
    {
        return $this->GetAllEntries('id');
    }

    /** get Format by id */
    public function getFormatsById($id)
    {
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

    /** bind params not in use because i dont want to change or add  Formats */
    protected function bindMyParams($stmt, $update = false)
    {
        // TODO: Implement bindMyParams() method.
    }
}






