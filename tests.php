<?php

use Models\CardsModel;
use Views\LoginView;

include('bootstrap.php');



$card = new CardsModel();
$card->getCardByName('clariomultimatum');
print $card->toString();


$view = new LoginView();
$view->showTemplate();




