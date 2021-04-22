<?php

namespace Models;

class DecksModel extends AbstractModel {

    protected $table = 'decks';
    protected $decks = [];
    protected $colors = [];

    protected $fields = [
        'id',
        'user_id', //FK
        'format_id', //FK
        'name'
    ];

    protected $values = [];


    public function getAllDecks(){

        return $this->GetAllEntries();
    }

    public function getDecksByUserId($id) {

        return $this->GetAllByFKId($id, 'user_id');
    }

    public function getDeckById($id) {

        try{
            $result = $this->getBySingleField('id', $id, 'i');
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

    public function getDecksBySearch($name = '', $colors = '', $format='') {

        $decks = $this->searchDecks($name, $colors, $format);
        return $decks;
    }

    public function countDecksBySearch($name = '', $colors = '', $format='') {

        $decks = $this->searchDecks($name, $colors, $format);

        return $decks;
    }

    public function getDeckByName($name) {

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

    protected function bindMyParams($stmt, $update = false)
    {
        // TODO: Implement bindMyParams() method.
    }
}






