<?php


class DecksHasColorsModel extends AbstractModel {

    protected $table = 'deck_has_color';

    private $fields = [
        'id',
        'deck_id', //FK
        'color_id' //FK
    ];

    private $values = [];
 // TODO what happens if there are more then one = deck_id ?
    public function getColorByDeckId($deck_id) { //get color by deck
        try{
            $result = $this->getBySingleField('id', $deck_id, 's');
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






