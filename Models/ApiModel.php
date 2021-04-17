<?php


namespace Models;


use Database\DBConnection;
use mysqli;

class ApiModel
{
    public $sql = "SELECT id, set_name FROM set_edition";



    public function getAllSet() {
        try{
            $conn = DBConnection::getConnection();
            $sql = ("SELECT id, set_name FROM set_edition");
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->get_result();
        }catch(Exception $exception){
            die('Problem with database');
            //return false;
        }
    }

    public function getCardsByScryfallId($scryfall_id) {
        try{
            $conn = DBConnection::getConnection();
            $sql = ("SELECT id FROM cards WHERE scryfall_id = ?");
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $scryfall_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows == 0){
                return false; //found nothing
            }else{
                return $result->fetch_assoc();
            }
            return $stmt->get_result();
        }catch(Exception $exception){
            die('Problem with database');
            //return false;
        }
    }

    public function createSet($set_name, $set) {
        try{
            $conn = DBConnection::getConnection();

            $sql =("INSERT INTO set_edition (set_name, `set`) VALUES (?,?)");
            $stmt = $conn->prepare($sql);

            //$test = $set_name;
            $stmt->bind_param('ss', $set_name, $set);
            //print_r($stmt);

            $stmt->execute();

            return $stmt->insert_id;
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function putCardsInDB($card, $setId){

        try{
            $conn = DBConnection::getConnection();

            $sql =("INSERT INTO cards (scryfall_id,
                    lang,
                    scryfall_uri,
                    cmc,
                    oracle_text,
                    mana_cost,
                    `name`,
                    power,
                    toughness,
                    image_uris,
                    rarity,
                    set_name,
                    collector_number,
                    type_line) Values (
                    ?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt = $conn->prepare($sql);

            $stmt->bind_param("sssdsssssssiis",$card->id, $card->lang, $card->scryfall_uri, $card->cmc, $card->oracle_text,
                $card->mana_cost, $card->name, $card->power, $card->toughness, $card->image_uris->normal, $card->rarity,
                $setId,$card->collector_number, $card->type_line );


            $stmt->execute();
            //print_r($stmt);
            return $stmt->insert_id;
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function putCardColorsInDB($card_colors, $card_id){
        try{
            $conn = DBConnection::getConnection();
            $sql =("INSERT INTO cards_has_colors (cards_id, colors_id) SELECT " . $card_id . ", id FROM colors WHERE abbr = ?");
            $stmt = $conn->prepare($sql);
            $color = "";
            $stmt->bind_param("s", $color);

            foreach($card_colors as $color) {
                $stmt->execute();
            }

            return true;
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function putLegalitiesInDB($formats, $card_id){
        try{
            $conn = DBConnection::getConnection();
            $sql =("INSERT INTO cards_has_formats_has_legalities (cards_id, legalities_id, formats_id) 
                SELECT " . $card_id . ", legalities.id, formats.id 
                FROM legalities 
                LEFT JOIN formats
                ON formats.format = ? 
                WHERE legalities.legality = ?");
            $stmt = $conn->prepare($sql);
            $format = "";
            $legality = "";
            $stmt->bind_param("ss", $format, $legality);

            $format = "standard";
            $legality = $formats->standard;
            $stmt->execute();

            $format = "future";
            $legality = $formats->future;
            $stmt->execute();

            $format = "historic";
            $legality = $formats->future;
            $stmt->execute();

            $format = "gladiator";
            $legality = $formats->future;
            $stmt->execute();

            $format = "pioneer";
            $legality = $formats->future;
            $stmt->execute();

            $format = "modern";
            $legality = $formats->future;
            $stmt->execute();

            $format = "legacy";
            $legality = $formats->future;
            $stmt->execute();

            $format = "pauper";
            $legality = $formats->future;
            $stmt->execute();

            $format = "vintage";
            $legality = $formats->future;
            $stmt->execute();

            $format = "penny";
            $legality = $formats->future;
            $stmt->execute();

            $format = "commander";
            $legality = $formats->future;
            $stmt->execute();

            $format = "brawl";
            $legality = $formats->future;
            $stmt->execute();

            $format = "duel";
            $legality = $formats->future;
            $stmt->execute();

            $format = "oldschool";
            $legality = $formats->future;
            $stmt->execute();

            $format = "premodern";
            $legality = $formats->future;
            $stmt->execute();

            return true;
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

}
