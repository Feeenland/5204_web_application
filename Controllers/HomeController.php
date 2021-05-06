<?php
/**
 * HomeController.php  includes functions related all decks.
 */

namespace Controllers;

use Models\CardsModel;
use Models\ColorModel;
use Models\DecksModel;
use Models\DecksSearchModel;
use Models\FormatsModel;
use Views\DeckSingleView;
use Views\DecksSearchedView;
use Views\DecksView;
use Views\HomeView;

class HomeController extends UserController
{
    public $view;

    public function __construct(string $method = null)
    {
        $user = $this->getUserByNicknameSession();

        switch($method) {
            case 'search':
                $this->searchDecks();
                break;
            case 'search_count':
                $this->countDecks();
                break;

            case 'show_deck':
                $this->showDeckSingle($_GET['showDeck']);
                break;
            default:
                $this->getAllDecks();
        }
    }

    /** search all decks by filter */
    public function searchDecks()
    {
        $d = new DecksSearchModel();
        $d->search_name = $_POST['search_text'];
        if(isset($_POST['color'])) {
            $d->search_color = $_POST['color'];
        }
        if(isset($_POST['format'])) {
            $d->search_format = $_POST['format'];
        }
        $get_decks = $d->getSearchResult();
        $this->showDecks($get_decks);
    }

    /** count all decks by filter */
    public function countDecks()
    {
        $d = new DecksSearchModel();
        $d->search_name = $_POST['search_text'];
        if(isset($_POST['color'])) {
            $d->search_color = $_POST['color'];
        }
        if(isset($_POST['format'])) {
            $d->search_format = $_POST['format'];
        }
        print_r($d->getSearchCount());
    }

    /** show single deck */
    public function showDeckSingle($deckId)
    {
        $d = new DecksSearchModel();
        $singleDeck = $d->getSingleDeck($deckId);
        $c = new CardsModel();
        $cards = $c->getAllCardsByDeckId($deckId);

        $this->view = new DeckSingleView();
        $count = 1;
        foreach ($cards as $card){
            $cardValue = $c->getCardById($card);
            $cardId = $c->getFieldValue('id');
            $cardImg = $c->getFieldValue('image_uris');
            $cardName = $c->getFieldValue('name');

            $this->view->addCards($count, 'id', $cardId);
            $this->view->addCards($count, 'image_uris', $cardImg);
            $this->view->addCards($count, 'name', $cardName);
            $count++;
        }
        foreach ($singleDeck as $deck){
            $this->view->addDecks($deck[2], 'id', $deck[2]);
            $this->view->addDecks($deck[2], 'name', html_entity_decode($deck[0]));
            $this->view->addDecks($deck[2], 'description', html_entity_decode($deck[1]));
            $this->view->addDecks($deck[2], 'format', $deck[3]);
            $this->view->addDecks($deck[2], 'nickname', $deck[4]);
            $colors = explode(',', $deck[5]);
            foreach ($colors as $color){
                $this->view->addDecks($deck[2], 'colors', $color);
            }
        }
        $this->view->addToKey('deckCountCards', (count($cards)));
        $this->view->showTemplate();
    }

    /** show searched decks */
    public function showDecks($decks)
    {
        $this->view = new DecksSearchedView();
        foreach ($decks as $deck){
            $this->view->addDecks($deck['id'], 'id', $deck['id']);
            $this->view->addDecks($deck['id'], 'name', html_entity_decode($deck['name']));
            $this->view->addDecks($deck['id'], 'format', $deck['format']);
            $this->view->addDecks($deck['id'], 'nickname', $deck['nickname']);
            $colors = explode(',', $deck['colors']);
            foreach ($colors as $color){
                $this->view->addDecks($deck['id'], 'colors', $color);
            }
            $this->view->addDecks($deck['id'], 'image_uris', $deck['image_uris']);
        }
        $this->view->showTemplate();
    }

    /** show all decks */
    public function getAllDecks()
    {
        $c = new ColorModel();
        $colors = $c->getAllColors();
        $f = new FormatsModel();
        $formats = $f->getAllFormats();
        $d = new DecksModel();
        $decks = $d->getAllDecks();
        $ds = new DecksSearchModel();
        $get_decks = $ds->getDecks();

        $view = $this->view = new HomeView();
        $this->view->addToKey('nickname', $this->userNick);
        $this->view->addToKey('name', $this->userName);
        $this->view->addToKey('colors', $colors);
        $this->view->addToKey('formats', $formats);
        $this->view->addToKey('decks_count', count($decks));

        foreach ($get_decks as $deck){
            $this->view->addDecks($deck[1], 'id', $deck[1]);
            $this->view->addDecks($deck[1], 'name', html_entity_decode($deck[0]));
            $this->view->addDecks($deck[1], 'format', $deck[2]);
            $this->view->addDecks($deck[1], 'nickname', $deck[3]);
            $colors = explode(',', $deck[4]);
            foreach ($colors as $color){
                $this->view->addDecks($deck[1], 'colors', $color);
            }
            $this->view->addDecks($deck[1], 'image_uris', $deck[5]);
        }
        $view->showTemplate();
    }
}
