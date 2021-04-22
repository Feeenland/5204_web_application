<?php


namespace Controllers;


use Models\ApiModel;

class ApiController
{
    protected $cardsfile;
    protected $cardsjson;
    protected $cards;

//Fatal error: Maximum execution time of 120 seconds exceeded in C:\xampp\htdocs\5204_php\Models\ApiModel.php on line 167
// 1666 cards !

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
            /* add de set if it is not there yet*/
            if (array_key_exists($card->set_name, $availableSets)) {
                $setId = $availableSets[$card->set_name];
            } else {
                $setId = $a->createSet($card->set_name, $card->set);
                $availableSets[$card->set_name] = $setId;
            }

            /*check id the card already exist, if not add the card*/
            $scry_id = $a->getCardsByScryfallId($card->id);
            if ($scry_id == false){
                //print_r($scry_id);
                print ' new ';
                $card_id = $a->putCardsInDB($card, $setId);
                $a->putCardColorsInDB($card->colors, $card_id);
                $a->putLegalitiesInDB($card->legalities, $card_id);
            }else{
                print' Here! ';
                //print_r($scry_id);
            }

           // die();

           /* print_r($card->id);
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
            print_r($card->legalities);*/

        }

    }
}