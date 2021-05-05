<?php


namespace Models;

use Database\DBConnection;
use Helpers\disinfect;
use mysqli;


class CardsSearchModel extends AbstractModel
{
    public $search_name;
    public $search_color = [];
    public $search_format;
    public $search_set;
    public $search_type;

    protected $search_count;
    protected $search_result = [];

    public function getSearchResult() {
        $this->searchCards();
        return $this->search_result;
    }

    public function getSearchCount() {
        $this->countCards();
        return $this->search_count;
    }
    public function getSearchOwnResult($id) {
        $this->searchCards($id);
        return $this->search_result;
    }

    public function getSearchOwnCount($id) {
        $this->countCards($id);
        return $this->search_count;
    }

    protected function searchCards($id = 0)
    {
        try{
            $conn = DBConnection::getConnection();
            $sql = "SELECT c.id, c.image_uris, c.name FROM cards c
                LEFT JOIN cards_has_colors chc ON c.id = chc.cards_id
                LEFT JOIN cards_has_formats_has_legalities chfhl ON c.id = chfhl.cards_id AND chfhl.legalities_id = 1
                LEFT JOIN users_has_cards uhc ON c.id = uhc.cards_id
                INNER JOIN set_edition se ON c.set_name = se.id
                WHERE `name` LIKE ? 
                AND type_line LIKE ?";

            if(count($this->search_color)) {
                $sql .= " AND chc.colors_id IN(" . implode(',', $this->search_color) . ")";
            }
            if($this->search_format > 0) {
                $sql .= " AND chfhl.formats_id = " . $this->search_format; // = legal ?
            }
            if($this->search_set > 0) {
                $sql .= " AND c.set_name = " . $this->search_set; // set_name = set_edition.id
            }
            if($id > 0) {
                $sql .= " and uhc.users_id = " . $id;
            }
            $sql .= " GROUP BY c.name, c.id";
            $stmt = $conn->prepare($sql);
            $d = new Disinfect();
            $name = $this->search_name . "%";
            $type = "%" . $this->search_type . "%"; // = %?%
            $d->disinfect($name);
            $d->disinfect($type);
            $stmt->bind_param('ss', $name, $type);
            $stmt->execute();
            $result = $stmt->get_result();

            //print_r($sql);

            $this->search_result = [];
            while($row = $result->fetch_assoc()) {
                $this->search_result[] = $row;
            }
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    protected function countCards($id =0) {
        try{
            $conn = DBConnection::getConnection();
            $sql = "SELECT COUNT(DISTINCT c.id) as cards_count FROM cards c
                LEFT JOIN cards_has_colors chc ON c.id = chc.cards_id
                LEFT JOIN cards_has_formats_has_legalities chfhl ON c.id = chfhl.cards_id AND chfhl.legalities_id = 1
                LEFT JOIN users_has_cards uhc ON c.id = uhc.cards_id
                INNER JOIN set_edition se ON c.set_name = se.id
                /*WHERE `name` LIKE ? 
                AND type_line LIKE ?*/";

           /* if($this->search_color != []) {
                $sql .= "INNER JOIN cards_has_colors chc ON c.id = chc.cards_id";
            }*/

            $sql .= "WHERE `name` LIKE ?
                    AND type_line LIKE ?";

            if(count($this->search_color)) {
               /* $sql .= "INNER JOIN cards_has_colors chc ON c.id = chc.cards_id";*/
                $sql .= " AND chc.colors_id IN(" . implode(',', $this->search_color) . ")";
            }
            if($this->search_format > 0) {
                $sql .= " AND chfhl.formats_id = " . $this->search_format; // = legal ?
            }
            if($this->search_set > 0) {
                $sql .= " AND c.set_name = " . $this->search_set; // set_name = set_edition.id
            }
            if($id > 0) {
                $sql .= " and uhc.users_id = " . $id;
            }

            $stmt = $conn->prepare($sql);
            $d = new Disinfect();
            $name = $this->search_name . "%";
            $type = "%" . $this->search_type . "%"; // = %?%
            $d->disinfect($name);
            $d->disinfect($type);
            $stmt->bind_param('ss', $name, $type);
            $stmt->execute();
            $result = $stmt->get_result();

            $row = $result->fetch_assoc();
            $this->search_count = $row['cards_count'];
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function getSingleCardDetail($id){

        try{
            $conn = DBConnection::getConnection();
            $sql = "SELECT c.*, se.set_name, group_concat(f.format ORDER BY f.id) as formats, group_concat(l.legality ORDER BY f.id) as legalities,

            (SELECT group_concat(col.color) FROM cards_has_colors chcol
                                LEFT JOIN colors col ON chcol.colors_id = col.id
                                Where chcol.cards_id = c.id
                                LIMIT 1) as  colors
             FROM cards c

                LEFT JOIN cards_has_formats_has_legalities chfhl ON c.id = chfhl.cards_id
                LEFT JOIN formats f ON chfhl.formats_id = f.id
                LEFT JOIN legalities l ON chfhl.legalities_id = l.id

                INNER JOIN set_edition se ON c.set_name = se.id
                WHERE c.id LIKE ?
                GROUP BY  c.id";
            /*
            // all data but without the legalities!
            SELECT c.*, se.set_name, group_concat(col.color) as colors FROM cards c
                LEFT JOIN cards_has_colors chc ON c.id = chc.cards_id
                LEFT JOIN colors col ON chc.colors_id = col.id
                INNER JOIN set_edition se ON c.set_name = se.id
                WHERE c.id LIKE ?
                GROUP BY  c.id
             * */

            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();

            $this->search_result = [];
            while($row = $result->fetch_assoc()) {
                $this->search_result[] = $row;
            }
            return $this->search_result;
        }catch(Exception $e){
            die($e->getMessage());
        }
    }
    public function getCardLegalityByDeckFormat($cardId, $deckId){

        try{
            $conn = DBConnection::getConnection();
            $sql = "SELECT  c.name as card, d.name as deck, f.format, l.legality
            FROM cards c
            	INNER JOIN decks d ON d.id = ?
                INNER JOIN cards_has_formats_has_legalities chfhl ON chfhl.cards_id = c.id
                LEFT JOIN legalities l ON chfhl.legalities_id = l.id
                
                LEFT JOIN formats f ON f.id = d.format_id

                WHERE c.id = ?
                AND chfhl.formats_id = d.format_id";
/*
 * SELECT  c.name, d.name, f.format, l.legality
            FROM cards c
            	INNER JOIN decks d ON d.id = 15
                INNER JOIN cards_has_formats_has_legalities chfhl ON chfhl.cards_id = c.id
                LEFT JOIN legalities l ON chfhl.legalities_id = l.id

                LEFT JOIN formats f ON f.id = d.format_id

                WHERE c.id = 9056
                AND chfhl.formats_id = d.format_id
*/
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii', $deckId, $cardId);
            $stmt->execute();
            $result = $stmt->get_result();

            $this->search_result = [];
            while($row = $result->fetch_assoc()) {
                $this->search_result[] = $row;
            }
            return $this->search_result;
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    protected function bindMyParams($stmt, $update = false)
    {
        // TODO: Implement bindMyParams() method.
    }
}
