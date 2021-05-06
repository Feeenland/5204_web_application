<?php
/**
 * CardsModel.php Do the queries for the card in the DB
 */
namespace Models;

class DecksModel extends AbstractModel {

    protected $table = 'decks';
    protected $decks = [];
    protected $colors = [];
    protected $fields = [
        'id',
        'user_id', //FK
        'format_id', //FK
        'name',
        'description'
    ];
    protected $values = [];


    public function getAllDecks()
    {
        return $this->GetAllEntries('name');
    }

    /** get all deck from user */
    public function getDecksByUserId($id)
    {
        return $this->GetAllByFKId($id, 'user_id');
    }

    /** get deck by id */
    public function getDeckById($id)
    {
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

    /** get deck by name */
    public function getDeckByName($name)
    {
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

    /** save/update deck */
    public function updateSave($values, $colors)
    {
        try{
            foreach ($this->fields as $field){ //put the incoming values in to $this->values
                if (array_key_exists($field, $values))
                    $this->setFieldValue($field, $values[$field]);
            }
            $result = $this->saveValues($this->fields, $this->values, $this->values['id']);
            //colors?
            foreach ( $colors as $color){
                $this->addManyToManyRelations( 'decks_has_colors', 'color_id', 'deck_id', $color, $result);
            }
            if ($result == false) {
                print 'Speichern fehlgeschlagen ';
                //die('Speichern fehlgeschlagen');
            } else {
                //print " worked! ";
                return true;
            }
        }catch(Exception $exception){
            die('Problem with database');
            //return false;
        }
    }

    /** delete card from deck */
    public function deleteCardFromDeck($cardId, $deckId)
    {
        try{
            $result = $this->deleteManyToManyRelations(
                'decks_has_cards',
                'cards_id',
                'deck_id',
                $cardId,
                $deckId);
            return true;
        }catch(Exception $exception){
            die('Problem with database');
            //return false;
        }
    }

    /** bind params */
    protected function bindMyParams($stmt, $update = false)
    {
        $types = $update ? 'iissi' : 'iiss';
        $user_id = $this->getFieldValue('user_id');
        $format_id = $this->getFieldValue('format_id');
        $name = $this->getFieldValue('name');
        $description = $this->getFieldValue('name');
        $id = $this->getFieldValue('id');
        if($update){
            $stmt->bind_param($types,$user_id, $format_id, $name, $description, $id);
        }else{
            $stmt->bind_param($types, $user_id, $format_id, $name, $description);
        }
        return $stmt;
    }
}






