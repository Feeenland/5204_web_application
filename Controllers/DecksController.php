<?php


namespace Controllers;


use Models\ColorModel;
use Models\DecksModel;
use Models\DecksSearchModel;
use Models\FormatsModel;
use Models\UserModel;
use Views\DecksSearchedView;
use Views\DecksView;

class DecksController extends UserController
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
            case 'search_own':
                $this->searchOwnDecks();
                break;
            case 'search_own_count':
                $this->countOwnDecks();
                break;
            default:
                $this->getUserDecks();
        }
    }

    public function searchDecks() {
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

    public function countDecks() {
        $d = new DecksSearchModel();
        $d->search_name = $_POST['search_text'];
        if(isset($_POST['color'])) {
            $d->search_color = $_POST['color'];
        }
        if(isset($_POST['format'])) {
            $d->search_format = $_POST['format'];
        }
        //print $d;
        print_r($d->getSearchCount());
    }

    public function searchOwnDecks() {
        $d = new DecksSearchModel();
        $d->search_name = $_POST['search_text'];
        if(isset($_POST['color'])) {
            $d->search_color = $_POST['color'];
        }
        if(isset($_POST['format'])) {
            $d->search_format = $_POST['format'];
        }
        //print $usrId;
        $get_decks = $d->getSearchOwnResult($this->userId);

        $this->showDecks($get_decks);
    }

    public function countOwnDecks() {
        $d = new DecksSearchModel();
        $d->search_name = $_POST['search_text'];
        if(isset($_POST['color'])) {
            $d->search_color = $_POST['color'];
        }
        if(isset($_POST['format'])) {
            $d->search_format = $_POST['format'];
        }
        //print $d;
        print_r($d->getSearchOwnCount($this->userId));
    }

    public function showDecks($decks){
        $this->view = new DecksSearchedView();
        //print_r($get_decks);
        foreach ($decks as $deck){
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
        $this->view->showTemplate();
    }

    public function getUserDecks(){

        $userDecks = new DecksSearchModel();
        $decks = $userDecks->getDecks($this->userId);

        $c = new ColorModel();
        $colors = $c->getAllColors();
        //print_r($colors);
        $f = new FormatsModel();
        $formats = $f->getAllFormats();
        //print_r($formats);
        //count($decks);
        //print_r($decks);
        $this->view = new DecksView();
        //print_r($get_decks);
        $this->view->addToKey('nickname', $this->userNick);
        $this->view->addToKey('name', $this->userName);
        $this->view->addToKey('colors', $colors);
        $this->view->addToKey('formats', $formats);
        $this->view->addToKey('decks_count', count($decks));

        foreach ($decks as $deck){
            $this->view->addDecks($deck[1], 'id', $deck[1]);
            $this->view->addDecks($deck[1], 'name', $deck[0]);
            $this->view->addDecks($deck[1], 'format', $deck[2]);
            $this->view->addDecks($deck[1], 'nickname', $deck[3]);
            $colors = explode(',', $deck[4]);
            foreach ($colors as $color){
                $this->view->addDecks($deck[1], 'colors', $color);
            }
            $this->view->addDecks($deck[1], 'image_uris', $deck[5]);
        }
        $this->view->showTemplate();
    }


}
