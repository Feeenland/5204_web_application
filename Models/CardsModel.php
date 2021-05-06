<?php
/**
 * DecksModel.php Do the queries for the decks in the DB
 */
namespace Models;

class CardsModel extends AbstractModel {

    protected $table = 'cards';
    protected $fields = [
        'id',
        'lang',
        'scryfall_uri',
        'cmc',
        'oracle_text',
        'mana_cost',
        'name',
        'power',
        'toughness',
        'image_uris',
        'rarity',
        'set_name', //FK
        'collector_number',
        'type_line',
    ];
    protected $colors = [];
    protected $cards = [];
    protected $set = [];
    protected $formats = [];
    protected $legalities = [];
    protected $values = [];

    /** count all cards */
    public function CountCards()
    {
        try {
           $count = $this->CountAllEntries();
            return $count;
        }catch(Exception $exception){
            die('Problem with database');
            //return false;
        }
    }

    /** get all cards by user */
    public function getCardsByUserId($id)
    {
        $ids = $this->loadManyToManyRelations(
            $id,
            'users_id',
            'cards_id',
            'users_has_cards'
        );
        $this->cards = [];
        foreach($ids as $id)
        {
            $this->cards[] = $id[0];
        }
        // TODO optimize the DB query so that hundreds of queries do not have to be made (with JOIN / lazy loading)
            return $this->cards;
    }

    /** get all cards by deck */
    public function getAllCardsByDeckId($deckId)
    {
        $ids = $this->loadManyToManyRelations(
            $deckId,
            'deck_id',
            'cards_id',
            'decks_has_cards'
        );
        $this->cards = [];
        foreach($ids as $id)
        {
            $this->cards[] = $id[0];
        }
        return $this->cards;
    }

    /** get specific card by deck */
    public function countSpecificCardByDeckId($deckId, $cardId)
    {
        $ids = $this->loadManyToManyRelationsSpecific(
            $deckId,
            $cardId,
            'deck_id',
            'cards_id',
            'decks_has_cards');
        $this->cards = [];
        foreach($ids as $id)
        {
            $this->cards[] = $id[0];
        }
        return $this->cards;
    }

    /** get card by id */
    public function getCardById($id)
    {
        try{
            $result = $this->getBySingleField('id', $id, 'i');
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

    /** check if user has this card */
    public function checkUserHasCard($cardId, $userId)
    {
        try{
            $result = $this->loadManyToManyRelationsSpecific(
                $cardId,
                $userId,
                'cards_id',
                'users_id',
                'users_has_cards');
            //print_r($result);
            if($result == [] ){
                //print "card not here";
                return false;
            }else{
                //print "true";
                //print_r($result[0][0]) ;
                return true; //found
            }
        }catch(Exception $exception){
            die('Problem with database');
            //return false;
        }
    }

    /** add card to user cards */
    public function addCardToUser($cardId, $userId)
    {
        try{
            $result = $this->addManyToManyRelations(
                'users_has_cards',
                'users_id',
                'cards_id',
                $userId,
                $cardId);
            return true;
        }catch(Exception $exception){
            die('Problem with database');
            //return false;
        }
    }

    /** delete card from user */
    public function DeleteCardByUserId($cardId, $userId)
    {
        try{
            $result = $this->deleteManyToManyRelations(
                'users_has_cards',
                'users_id',
                'cards_id',
                $userId,
                $cardId);
            return true;
        }catch(Exception $exception){
            die('Problem with database');
            //return false;
        }
    }

    /** add card to deck */
    public function addCardToDeck($cardId, $deckId)
    {
        try{
            $result = $this->addManyToManyRelations(
                'decks_has_cards',
                'deck_id',
                'cards_id',
                $deckId,
                $cardId);
            return true;
        }catch(Exception $exception){
            die('Problem with database');
            //return false;
        }
    }

    /** get single card by id */
    public function getSingleCardById($cardId)
    {
        try{
            $result = $this->getBySingleField('id', $cardId, 'i');
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

    /** bind params, not in use because i dont want to change cards and the add is only by the ApiModel.php */
    protected function bindMyParams($stmt, $update = false)
    {
        // TODO: Implement bindMyParams() method.
    }
}






