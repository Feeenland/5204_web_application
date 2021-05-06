<?php
/**
 * The DecksController.php includes functions related to the user decks.
 */

namespace Controllers;

use Models\CardsModel;
use Models\ColorModel;
use Models\DecksModel;
use Models\DecksSearchModel;
use Models\FormatsModel;
use Models\UserModel;
use Views\DeckSingleView;
use Views\DecksSearchedView;
use Views\DecksSingleView;
use Views\DecksView;

class DecksController extends UserController
{
    public $view;

    public function __construct(string $method = null)
    {
        $user = $this->getUserByNicknameSession();

        switch($method) {
            case 'search_own':
                $this->searchOwnDecks();
                break;
            case 'search_own_count':
                $this->countOwnDecks();
                break;
            case 'show_deck':
                $this->showDeckSingle($_GET['showDeck']);
                break;
            case 'delete_card':
                $this->deleteCardInDeck($_GET['data-card'],$_GET['data-deck']);
                break;
            default:
                $this->getUserDecks();
        }
    }

    /** search user Decks by filter */
    public function searchOwnDecks()
    {
        $d = new DecksSearchModel();
        $d->search_name = $_POST['search_text'];
        if(isset($_POST['color'])) {
            $d->search_color = $_POST['color'];
        }
        if(isset($_POST['format'])) {
            $d->search_format = $_POST['format'];
        }
        $get_decks = $d->getSearchOwnResult($this->userId);
        $this->showDecks($get_decks);
    }

    /** count user Decks by filter */
    public function countOwnDecks()
    {
        $d = new DecksSearchModel();
        $d->search_name = $_POST['search_text'];
        if(isset($_POST['color'])) {
            $d->search_color = $_POST['color'];
        }
        if(isset($_POST['format'])) {
            $d->search_format = $_POST['format'];
        }
        print_r($d->getSearchOwnCount($this->userId));
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

    /** delete a card in deck */
    public function deleteCardInDeck($cardId, $deckId)
    {
        $d = new DecksModel();
        $delete =$d->deleteCardFromDeck($cardId, $deckId);
        print $delete;
    }

    /** get all user decks */
    public function getUserDecks()
    {
        $userDecks = new DecksSearchModel();
        $decks = $userDecks->getDecks($this->userId);

        $c = new ColorModel();
        $colors = $c->getAllColors();
        $f = new FormatsModel();
        $formats = $f->getAllFormats();

        $this->view = new DecksView();
        $this->view->addToKey('nickname', $this->userNick);
        $this->view->addToKey('name', $this->userName);
        $this->view->addToKey('colors', $colors);
        $this->view->addToKey('formats', $formats);
        $this->view->addToKey('decks_count', count($decks));

        foreach ($decks as $deck){
            $this->view->addDecks($deck[1], 'id', $deck[1]);
            $this->view->addDecks($deck[1], 'name',  html_entity_decode($deck[0]));
            $this->view->addDecks($deck[1], 'format', $deck[2]);
            $this->view->addDecks($deck[1], 'nickname', $deck[3]);
            $colors = explode(',', $deck[4]);
            foreach ($colors as $color){
                $this->view->addDecks($deck[1], 'colors', $color);
            }
            $this->view->addDecks($deck[1], 'image_uris', $deck[5]);
        }
        if (isset($_GET['info']) || $_GET['info'] == 'Welcome'){
            $this->view->addInfos('Welcome ' .$this->userName .' ' . $this->userNick);
        }
        $this->view->showTemplate();
    }

    /** show single deck */
    public function showDeckSingle($deckId)
    {
        $d = new DecksSearchModel();
        $singleDeck = $d->getSingleDeck($deckId);
        $c = new CardsModel();
        $cards = $c->getAllCardsByDeckId($deckId);

        $this->view = new DeckSingleView();
        $count = 1; /* the count is used because the same card can be in a deck several times*/
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
        $this->view->addToKey('deckId', $deckId);
        $this->view->addToKey('deckCountCards', (count($cards)));
        $this->view->showTemplate();
    }
}
