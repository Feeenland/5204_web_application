<?php


class CardsHasFormatsHasLegalitiesModel extends AbstractModel {

    protected $table = 'cards_has_formats_has_legalities';

    private $fields = [
        'id',
        'cards_id', //FK
        'formats_id', //FK
        'legalities_id' //FK
    ];
// TODO legality needs to be defined by card!
    private $values = [];

    public function getFormatByCardId($card_id) { //get format by card
        try{
            $result = $this->getBySingleField('id', $card_id, 's');
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

    public function getLegalitiesByColorId($color_id) { //get legality by card
        try{
            $result = $this->getBySingleField('id', $color_id, 's');
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






