<?php

namespace Controllers;

use Models\CardsModel;
use Views\CardSingleView;
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

    public function __construct(/*$method, $id = null*/) {

        $user = $this->getUserByNicknameSession();

        $this->view = new CardsView();
        $this->view->addToKey('nickname', $this->userNick);
        $this->view->addToKey('name', $this->userName);
        $this->view->showTemplate();
      /*  switch($method) {
            case 'printCard':
                $this->getCardDetailByName($_POST['cardId']);
                break;

            case 'search':
                $this->SearchCard($_POST['search']);
                break;

            case 'searchcount':
                $this->SearchCount($_POST['search']);
                break;

            case 'searchhard':
                $this->SearchHard($_POST['search']);
                break;
        }*/
    }

    public function getUserCards(){

        $card = new CardsModel();
        
    }

    public function SearchCount($count){
        //count possible cards
        $card = new CardsModel();
    }

    public function SearchCard($cards){
        //if possible cards > 20, print cards
    }
    public function SearchHard($cards){
        // print all possible cards
    }

    // $this->errorMessages = 'Something was entered incorrectly!';
    //$this->view->addInfos($this->infos);
    public function addCardValues()
    {


    }
}
