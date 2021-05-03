<?php

namespace Controllers;

use Models\CardsModel;
use Models\CardsSearchModel;
use Models\ColorModel;
use Models\DecksModel;
use Models\DecksSearchModel;
use Models\FormatsModel;
use Models\SetEditionModel;
use Views\CardAddedView;
use Views\CardSingleView;
use Views\CardsSearchedView;
use Views\CardsView;
use Views\DeckAddCardView;

class CardsController extends UserController
{
    protected $cardInfos;
    public $view;
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
        'type_line',
        'color  ',
    ];

    public function __construct(string $method = null) {

        $user = $this->getUserByNicknameSession();


        switch($method) {
            case 'search':
                $this->searchCards();
                break;
            case 'search_count':
                $this->countCards();
                break;
            case 'search_own':
                $this->searchOwnCards();
                break;
            case 'search_own_count':
                $this->countOwnCards();
                break;
            case 'add_card':
                $this->addCard($_GET['addCard']);
                break;
            case 'select_deck':
                $this->addCardToDeck($_GET['data-card'],$_GET['data-deck']);
                break;
            case 'show_card':
                $this->showSingleCard($_GET['showCard']);
                break;
            default:
                if (isset($_GET['p']) && $_GET['p'] == 'cards'){
                    $this->getUserCards();
                }else{
                    $this->getAllCards();
                }
        }
    }

    public function searchCards() {
        $c = new CardsSearchModel();
        $c->search_name = $_POST['search_text_card'];
        $c->search_type = $_POST['search_text_creature'];
        if(isset($_POST['color'])) {
            $c->search_color = $_POST['color'];
        }
        if(isset($_POST['format'])) {
            $c->search_format = $_POST['format'];
        }
        if(isset($_POST['set'])) {
            $c->search_set = $_POST['set'];
        }
        //print_r($c->getSearchResult());
        $get_cards = $c->getSearchResult();

        $this->showCards($get_cards);
    }

    public function countCards() {
        $c = new CardsSearchModel();
        $c->search_name = $_POST['search_text_card'];
        $c->search_type = $_POST['search_text_creature'];
        if(isset($_POST['color'])) {
            $c->search_color = $_POST['color'];
        }
        if(isset($_POST['format'])) {
            $c->search_format = $_POST['format'];
        }
        if(isset($_POST['set'])) {
            $c->search_set = $_POST['set'];
        }
        //print $d;
        print_r($c->getSearchCount());
    }
    public function searchOwnCards() {
        $c = new CardsSearchModel();
        $c->search_name = $_POST['search_text_card'];
        $c->search_type = $_POST['search_text_creature'];
        if(isset($_POST['color'])) {
            $c->search_color = $_POST['color'];
        }
        if(isset($_POST['format'])) {
            $c->search_format = $_POST['format'];
        }
        if(isset($_POST['set'])) {
            $c->search_set = $_POST['set'];
        }
        //print_r($c->getSearchOwnResult($this->userId));
        $get_cards = $c->getSearchOwnResult($this->userId);

        $this->showCards($get_cards);
    }

    public function countOwnCards() {
        $c = new CardsSearchModel();
        $c->search_name = $_POST['search_text_card'];
        $c->search_type = $_POST['search_text_creature'];
        if(isset($_POST['color'])) {
            $c->search_color = $_POST['color'];
        }
        if(isset($_POST['format'])) {
            $c->search_format = $_POST['format'];
        }
        if(isset($_POST['set'])) {
            $c->search_set = $_POST['set'];
        }
        //print $d;
        print_r($c->getSearchOwnCount($this->userId));
    }

    public function showCards($cards){
        $this->view = new CardsSearchedView();
        foreach ($cards as $card){
            //print_r($card);
            $this->view->addCards($card['id'], 'id', $card['id']);
            $this->view->addCards($card['id'], 'image_uris', $card['image_uris']);
            $this->view->addCards($card['id'], 'name', $card['name']);
        }

        $this->view->showTemplate();
    }

    public function addCard($cardId){

        print $cardId;
        $userId =$this->userId;

        //check if card is saved by user?
        $c = new CardsModel();
        $cardExist =$c->checkUserHasCard($cardId, $userId);
        //print_r($cardExist);
        if ($cardExist == false){
            // save card by user
            $cardExist =$c->addCardToUser($cardId, $userId);
            print 'add card to user';
        }

        // get user decks
        $d = new DecksSearchModel();
        $d->searchDecks($userId);
        $userDecks = $d->search_result;

        // ask to witch deck to add? return deck ID
        // TODO open view over the searched cards view!
        $this->view = new DeckAddCardView();
        foreach ($userDecks as $deck){
            $this->view->addDecks($deck['id'], 'id', $deck['id']);
            $this->view->addDecks($deck['id'], 'name', $deck['name']);
            $this->view->addDecks($deck['id'], 'format', $deck['format']);
            $this->view->addDecks($deck['id'], 'nickname', $deck['nickname']);
            $colors = explode(',', $deck['colors']);
            foreach ($colors as $color){
                $this->view->addDecks($deck['id'], 'colors', $color);
            }
            $this->view->addDecks($deck['id'], 'image_uris', $deck['image_uris']);
        }
        $this->view->addToKey('cardId', $cardId);
        $this->view->showTemplate();


    }

    public function addCardToDeck($cardId, $deckId){
        // ad card to deck
        $c = new CardsModel();
            $c->addCardToDeck($cardId, $deckId);

        $countCards = $c->countSpecificCardByDeckId($deckId, $cardId);
            //TODO check if this card is allowed in this deck format = print
        $cardSearch = new CardsSearchModel();
        $formats = $cardSearch->getCardLegalityByDeckFormat($cardId, $deckId);
       // print_r($formats);

        $this->view = new CardAddedView();
        $this->view->addToKey('cardId', $cardId);
        $this->view->addToKey('cardName', $formats[0]['card']);
        $this->view->addToKey('deckName', $formats[0]['deck']);
        $this->view->addInfos('Notice: The Deck '.$formats[0]['deck'] .' has the Format '
            .$formats[0]['format'] .' and the card '.$formats[0]['card'] .' is  '.$formats[0]['legality'] .' in this Deck!');
        $this->view->addInfos('Notice: this card is now ' .(count($countCards)).' times in this deck,
                    if the same card from another edition is in this deck, it was not counted.');
        if ((count($countCards)) >= 5){
            $this->view->addInfos('Notice: a non-land card should not be more than 4x in one deck !');
        }

        $this->view->addInfos('Notice: this card is now saved in your cards and in your deck of choice :)');
        $this->view->showTemplate();

    }

    public function showSingleCard($cardId){



        $card = new CardsModel();
        $card->getSingleCardById($cardId);
        $this->view = new CardSingleView();

        foreach ($this->fields as $field){ //cards specific values
            $fieldValue = $card->getFieldValue($field);
            $this->view->assignData($field, $fieldValue);
        }

        $card = new CardsSearchModel();
        $cardDetail = $card->getSingleCardDetail($cardId);
        //print_r($cardDetail[0]['name']);
        //print_r($cardDetail);

        foreach ($cardDetail as $card){ // relationships
            //print_r($card);
            $this->view->addCards($card['id'], 'set_name', $card['set_name']);
            $colors = explode(',', $card['colors']);
            foreach ($colors as $color){
                $this->view->addCards($card['id'], 'colors', $color);
            }

            $formats = explode(',', $card['formats']);
            $legalities = explode(',', $card['legalities']);
            $formatsLegalities = array_combine($formats, $legalities);
            foreach (array_keys($formatsLegalities) as $fKey){
                $this->view->addCards($card['id'], $fKey, $formatsLegalities[$fKey]);
            }
        }

        $this->view->showTemplate();

    }


    public function getCardDetailById(/*$cardId*/) {

        $card = new CardsModel();
        $card->getSingleCardDetailById(37615);
        //print $card->toString();
        $view = $this->view = new CardSingleView();

        foreach ($this->fields as $field){
            $fieldValue = $card->getFieldValue($field);
            $this->view->assignData($field, $fieldValue);
        } // TODO find a way to print the relationships

        $view->showTemplate();
    }

    public function getUserCards(){

        $userCards = new CardsModel();
        $cards = $userCards->getCardsByUserId($this->userId);

        $c = new ColorModel();
        $colors = $c->getAllColors();
        //print_r($colors);
        $f = new FormatsModel();
        $formats = $f->getAllFormats();
        //print_r($formats);
        $s = new SetEditionModel();
        $sets = $s->getAllSets();

        $this->view = new CardsView();
        $this->view->addToKey('nickname', $this->userNick);
        $this->view->addToKey('name', $this->userName);
        $this->view->addToKey('colors', $colors);
        $this->view->addToKey('formats', $formats);
        $this->view->addToKey('sets', $sets);
        $this->view->addToKey('cards_count', count($cards));
        $this->view->addToKey('cards_user_all', 'search_own');

        foreach ($cards as $card){
            $cardValue = $userCards->getCardById($card);

            $cardId = $userCards->getFieldValue('id');
            $cardImg = $userCards->getFieldValue('image_uris');
            $cardName = $userCards->getFieldValue('name');

            $this->view->addCards($cardId, 'id', $cardId);
            $this->view->addCards($cardId, 'image_uris', $cardImg);
            $this->view->addCards($cardId, 'name', $cardName);
        }
        $this->view->showTemplate();
    }

    public function getAllCards(){

        $c = new CardsModel();
        $count = $c->CountCards();
        //print_r($count[0]['COUNT(*)']);

        $c = new ColorModel();
        $colors = $c->getAllColors();
        //print_r($colors);
        $f = new FormatsModel();
        $formats = $f->getAllFormats();
        //print_r($formats);
        $s = new SetEditionModel();
        $sets = $s->getAllSets();

        $infos = 'Search for cards.';
        $this->view = new CardsView();
        $this->view->addToKey('nickname', $this->userNick);
        $this->view->addToKey('name', $this->userName);
        $this->view->addInfos($infos);
        $this->view->addToKey('colors', $colors);
        $this->view->addToKey('formats', $formats);
        $this->view->addToKey('sets', $sets);
        $this->view->addToKey('cards_count', $count[0]['COUNT(*)']);
        $this->view->addToKey('cards_user_all', 'search');

        $this->view->showTemplate();
    }

}
