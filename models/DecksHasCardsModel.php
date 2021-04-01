<?php


class DecksHasCardsModel extends AbstractModel {

    protected $table = 'decks_has_cards';

    private $fields = [
        'id',
        'deck_id', //FK
        'cards_id' //FK
    ];

    private $values = [];

    public function getCardsByDeckId($card_id) { //get cards by deck
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

    public function setFieldValue($fieldName, $value){
        if ( !in_array($fieldName, $this->fields)){
            return 'invalid field';
        }
        $this->values[$fieldName] = $value;
    }

    // TODO add delete an save/update
}






