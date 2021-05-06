<?php
/**
 * LegalitiesModel.php Does the queries for the legalities in the DB
 */
namespace Models;

class LegalitiesModel extends AbstractModel {

    protected $table = 'legalities';
    public $fields = [
        'id',
        'legality'
    ];
    public $values = [];

    /** get all legalities */
    public function getLegalityById($id)
    {
        try{
            $result = $this->getBySingleField('id', $id, 's');
            if($result->num_rows == 0){
                //print "false";
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

    /** bind params not in use because i dont want to change or add  legalities */
    protected function bindMyParams($stmt, $update = false)
    {
        // TODO: Implement bindMyParams() method.
    }
}






