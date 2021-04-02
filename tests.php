<?php


// database connection
include('database/DBConnection.php');
include('helpers/validations.php');
include('helpers/disinfect.php');
include('models/AbstractModel.php');
include('models/CardsModel.php');
include('models/ColorModel.php');


$card = new CardsModel();
$card->getCardByName('clariomultimatum');
print $card->toString();