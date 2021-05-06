<?php
/**
 * The ApiController reads the JSON file with all the cards and prepares the data to save them in the DB.
 */

namespace Controllers;

use Models\ApiModel;

class ApiController
{
    protected $cards;

    /** add new magic cards  */
    public function addNewCards()
    {
        $cardsfile = fopen("scryfallApi/default-cards-20210427210307.json", "r");
        $cardsjson = fread($cardsfile, filesize("scryfallApi/default-cards-20210427210307.json"));
        $cards = json_decode($cardsjson);
        fclose($cardsfile);

        $a = new ApiModel();
        $storedSets = $a->getAllSet();

        while($row = $storedSets->fetch_assoc())
        {
            $availableSets[$row['set_name']] = $row['id'];
        }

        $i = 0;
        foreach($cards as $card){
            $i++;
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
                $card_id = $a->putCardsInDB($card, $setId);
                //print '<br> new ' . $card->id . ' : '. $card_id . '<br>';
                if(isset($card->colors)){
                    $a->putCardColorsInDB($card->colors, $card_id);
                }
                $a->putLegalitiesInDB($card->legalities, $card_id);
            }else{
                //print'card is Here! ';
                /* there was the problem that a lot of cards didnt had a legality, so i deleted them and added it new*/
                $a->deleteLegalities($scry_id['id']);
                $a->putLegalitiesInDB($card->legalities, $scry_id['id']);
            }
        }
    }
}
