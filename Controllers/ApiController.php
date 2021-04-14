<?php


namespace Controllers;


use Models\ApiModel;

class ApiController
{
    protected $cardsfile;
    protected $cardsjson;
    protected $cards;

    public function addNewCards()
    {
        $cardsfile = fopen("scryfallApi/default-cards-20210413210324.json", "r");
        $cardsjson = fread($cardsfile, filesize("scryfallApi/default-cards-20210413210324.json"));
        $cards = json_decode($cardsjson);
        fclose($cardsfile);

        $a = new ApiModel();

        $storedSets = $a->getAllSet();

        while($row = $storedSets->fetch_assoc()) {
            $availableSets[$row['set_name']] = $row['id'];
        }

        foreach($cards as $card) {
            if (array_key_exists($card->set_name, $availableSets)) {
                $setId = $availableSets[$card->set_name];
            } else {
                $setId = $a->createSet($card->set_name, $card->set);
                $availableSets[$card->set_name] = $setId;
            }

            print_r($card->id);
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
            print_r($card->legalities);

            $card_id = $a->putCardsInDB($card, $setId);
            $a->putCardColorsInDB($card->colors, $card_id);
            $a->putLegalitiesInDB($card->legalities, $card_id);
            die();
        }

    }

    public function AddColors()
    {
        print_r($card->colors);

    }


}