<?php

namespace Models;

class CardsModel extends AbstractModel {

    protected $table = 'cards';

    protected $fields = [
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

    protected $colors = [];

    // TODO  set_name is FK, try to handle ?!
    // TODO  lists, cards_has_color & cads_has_formats_has_legalities ??


    protected $values = [];

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
                $this->loadColors();
                return true;
            }
        }catch(Exception $exception){
            die('Problem with database');
            //return false;
        }
    }

    public function loadColors()
    {
        $ids = $this->loadManyToManyRelations(
            $this->getFieldValue('id'),
            'cards_id',
            'colors_id',
            'cards_has_colors'
        );

        $this->colors = [];
        foreach($ids as $id)
        {
            $c = new ColorModel();
            $c->getColorById($id[0]);
            $this->colors[] = $c;
        }
        // TODO optimize the DB query so that hundreds of queries do not have to be made (with JOIN / lazy loading)
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
            } else {
                print " worked! ";
                return true;
            }
        }catch(Exception $exception){
            die('Problem with database');
            //return false;
        }
    }

    protected function bindMyParams($stmt, $update = false)
    {
        // TODO: Implement bindMyParams() method.
    }

    public function toString()
    {
        $standard = parent::toString();
        if ($this->colors != []){
            $standard .= 'COLORS:';
            foreach($this->colors as $c){
                $standard .= '<p>' . $c->toString() .'</p>';
            }
        }
        return $standard;
    }
}






