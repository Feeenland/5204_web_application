<?php

namespace Controllers;

use Models\CardsModel;

class CardController
{

    public function getCardDetailByName() {

        $card = new CardsModel();
        $card->getCardByName('clariomultimatum'); //TODO $name!
        print $card->toString();




    }
}