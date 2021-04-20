<?php

namespace Controllers;

use Models\CardsModel;
use Views\CardSingleView;

class CardSingleController
{
    protected $cardInfos;
    protected $view;
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

        $this->getCardDetailByName();
    }

    public function getCardDetailByName(/*$cardId*/) {

        $card = new CardsModel();
        $card->getCardByName('Fury Sliver');
        //print $card->toString();
        $view = $this->view = new CardSingleView();

        foreach ($this->fields as $field){
            $fieldValue = $card->getFieldValue($field);
            $this->view->assignData($field, $fieldValue);
        } // TODO find a way to print the relationships
        $view->showTemplate();
    }
    // $this->errorMessages = 'Something was entered incorrectly!';
    //$this->view->addInfos($this->infos);
    public function addCardValues()
    {


    }
}
