<?php


namespace Controllers;


use Models\ColorModel;
use Models\DecksModel;
use Models\DecksSearchModel;
use Models\FormatsModel;
use Views\DecksView;
use Views\HomeView;

class HomeController extends UserController
{
    public $view;

    public function __construct()
    {
        $user = $this->getUserByNicknameSession();

        $c = new ColorModel();
        $colors = $c->getAllColors();
            //print_r($colors);
        $f = new FormatsModel();
        $formats = $f->getAllFormats();
            //print_r($formats);
        $d = new DecksModel();
        $decks = $d->getAllDecks();
         //count($decks);
            //print_r($decks);
        $ds = new DecksSearchModel();
        $get_decks = $ds->getDecks();
        //print_r($get_decks);
         //count($decks);
            //print_r($decks);

        $view = $this->view = new HomeView();
        $this->view->addToKey('nickname', $this->userNick);
        $this->view->addToKey('name', $this->userName);
        $this->view->addToKey('colors', $colors);
        $this->view->addToKey('formats', $formats);
        $this->view->addToKey('decks_count', count($decks));

        foreach ($get_decks as $deck){
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
        $view->showTemplate();
    }


}
