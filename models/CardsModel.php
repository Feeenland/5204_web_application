<?php


class CardsModel extends AbstractModel {

    protected $table = 'cards';

    private $fields = [
        'id',
        'lang',
        'scryfall_uri',
        'cmc',
        'mana_cost',
        'name',
        'power',
        'toughness',
        'image_uris',
        'rarity',
        'set_name', //FK
        'type_line',
    ];
    // TODO  set_name is FK, try to handle ?!
    // TODO  lists, cards_has_color & cads_has_formats_has_legalities ??

    // TODO delete,save,setFieldValue,getFieldValue,toString = is the same =  separate file ?

    private $values = [];

    public function getCardByName($name) {

        try{
            $result = $this->getBySingleField('name', $name, 's');
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

    // only to use if there are new cards released
    public function updateSave($values){
        try{
            foreach ($this->fields as $field){ //put the incoming values in to $this->values
                if (array_key_exists($field, $values))
                    $this->setFieldValue($field, $values[$field]);
            }
            $result = $this->saveValues($this->fields, $this->values, $this->values['id']);
            if ($result == false) {
                print 'Speichern fehlgeschlagen ';
                //die('Speichern fehlgeschlagen');
            } else {
                print " worked! ";
                return true;
            }
        }catch(Exception $exception){
            die('Problem with database');
            //return false;
        }
    }
}






