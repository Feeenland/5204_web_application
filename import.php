<?php

include('bootstrap.php');

use Controllers\ApiController;


print 'import.php';
$a = new ApiController();
$a->addNewCards();



/*
$cardsfile = fopen("default-cards-20210413210324.json", "r");
$cardsjson = fread($cardsfile, filesize("default-cards-20210413210324.json"));
$cards = json_decode($cardsjson);
fclose($cardsfile);

$sql = "SELECT id, set_name FROM set_edition";*/

/*
foreach($cards as $card) {
    if(array_key_exists($card->set_name, $sets)) {
        $setFK = $sets[$card->set_name];
    } else {
        // Set erstellen und an Array anhängen
    }*/
 /*
    ->prepare("INSERT INTO cards (scryfall_id,
    lang,
    scryfall_uri,
    cmc,
    oracle_text,
    mana_cost,
    name,
    power,
    toughness,
    image_uris, // normal
    rarity,
    collector_number,
    type_line) Values (
        ?,?,?,?,?,?,?,?,?,?,?,?,?
    )");
    $stmt->bind_param("sssdsssssssis",$card->id, $card->lang, $card->scryfall_uri, $card->cmc, $card->oracle_text,
        $card->mana_cost, $card->name, $card->power, $card->toughness, $card->image_uris->normal, $card->rarity,
        $card->collector_number, $card->type_line );
    $stmt->execute();
    */

    /*$sql = "INSERT INTO cards (scryfall_id,
    lang,
    scryfall_uri,
    cmc,
    oracle_text,
    mana_cost,
    name,
    power,
    toughness,
    image_uris, // normal
    rarity,
    /*set_name, //FK*/
    /*collector_number,
    type_line) Values (
        ?,?,?,?,?,?,?,?,?,?,?,?,?
    )";

    ->bind_param("sssdsssssssis",$card->id, $card->lang, $card->scryfall_uri, $card->cmc, $card->oracle_text,
        $card->mana_cost, $card->name, $card->power, $card->toughness, $card->image_uris->normal, $card->rarity,
        $card->collector_number, $card->type_line )*/

    // Karte erstellen

/*    print_r($card->id);
    print_r($card->lang);
    print_r($card->scryfall_uri);
    print_r($card->cmc);
    print_r($card->oracle_text);
    print_r($card->mana_cost);
    print_r($card->name);
    print_r($card->power);
    print_r($card->toughness);
    print_r($card->image_uris->normal);
    print_r($card->rarity);
    print_r($card->collector_number);
    print_r($card->type_line);
    print '<br>';
    print_r($card->set_name);
    print_r($card->set);
    print '<br>';
    print_r($card->colors);
    die();

    // Farbe und Legalitäten erstellen
}
*/

/*protected $fields = [
    'id',
    'lang',
    'scryfall_uri',
    'cmc',
    'oracle_text',
    'mana_cost',
    'name',
    'power',
    'toughness',
    'image_uris', // normal
    'rarity',
    'set_name', //FK
    'collector_number',
    'type_line',
];*/
