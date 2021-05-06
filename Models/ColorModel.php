<?php
/**
 * ColorModel.php Does the queries for the colors in the DB
 */
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

    /** get all colors */
    public function getAllColors()
    {
        return $this->GetAllEntries('id');
    }

    /** get color by id */
    public function getColorById($id)
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

    /** bind params not in use because i dont want to change or add colors */
    protected function bindMyParams($stmt, $update = false)
    {
        // TODO: Implement bindMyParams() method.
    }
}






