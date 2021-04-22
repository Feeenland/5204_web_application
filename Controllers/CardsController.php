<?php

namespace Controllers;

use Models\CardsModel;
use Models\CardsSearchModel;
use Models\ColorModel;
use Models\DecksModel;
use Models\FormatsModel;
use Models\SetEditionModel;
use Views\CardSingleView;
use Views\CardsSearchedView;
use Views\CardsView;

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
            $c->search_color = $_POST['format'];
        }
        print_r($c->getSearchResult());
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
            $c->search_color = $_POST['format'];
        }
        //print $d;
        print_r($c->getSearchOwnCount($this->userId));
    }
    public function searchOwnCards() {
        $c = new CardsSearchModel();
        $c->search_name = $_POST['search_text_card'];
        $c->search_type = $_POST['search_text_creature'];
        if(isset($_POST['color'])) {
            $c->search_color = $_POST['color'];
        }
        if(isset($_POST['format'])) {
            $c->search_color = $_POST['format'];
        }
        print_r($c->getSearchOwnResult($this->userId));
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
            $c->search_color = $_POST['format'];
        }
        //print $d;
        print_r($c->getSearchCount($this->userId));
    }

    public function showCards($cards){
        $this->view = new CardsSearchedView();
        foreach ($cards as $card){
            print_r($card);
            $this->view->addCards($card['id'], 'id', $card['id']);
            $this->view->addCards($card['id'], 'image_uris', $card['image_uris']);
            $this->view->addCards($card['id'], 'name', $card['name']);
        }
        $this->view->showTemplate();
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
