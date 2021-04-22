<?php


namespace Models;

use Database\DBConnection;
use Helpers\disinfect;
use mysqli;


class DecksSearchModel
{
    public $search_name;
    public $search_color = [];
    public $search_format;

    protected $search_count;
    protected $search_result = [];

    public function getSearchResult() {
        $this->searchDecks();
        return $this->search_result;
    }
    public function getSearchCount() {
        $this->countDecks();
        return $this->search_count;
    }
    public function getSearchOwnResult($id) {
        $this->searchDecks($id);
        return $this->search_result;
    }

    public function getSearchOwnCount($id) {
        $this->countDecks($id);
        return $this->search_count;
    }

    protected function searchDecks($id =0)
    {
        try{
            $conn = DBConnection::getConnection();
            $sql = "SELECT d.name, d.id, f.format, u.nickname, group_concat(col.color) as colors, card.image_uris FROM decks d
                INNER JOIN decks_has_colors dhcol ON d.id = dhcol.deck_id
                INNER JOIN colors col ON dhcol.color_id = col.id
                INNER JOIN decks_has_cards dhcard ON d.id = dhcard.deck_id
                INNER JOIN cards card ON dhcard.cards_id = card.id
                INNER JOIN formats f ON d.format_id = f.id
                INNER JOIN users u ON d.user_id = u.id
                WHERE d.`name` LIKE ?";
            if(count($this->search_color)) {
                $sql .= " AND dhcol.color_id IN(" . implode(',', $this->search_color) . ")";
            }
            if($id > 0) {
                $sql .= " and d.user_id = " . $id;
            }
            if($this->search_format > 0) {
                $sql .= " AND d.format_id = " . $this->search_format;
            }
            $sql .= " GROUP BY d.name, d.id, f.format, u.nickname";
            $stmt = $conn->prepare($sql);
            $d = new Disinfect();
            $name = $this->search_name . "%";
            $d->disinfect($name);
            $stmt->bind_param('s', $name);
            $stmt->execute();
            $result = $stmt->get_result();


            $this->search_result = [];
            while($row = $result->fetch_assoc()) {
                $this->search_result[] = $row;
            }
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    protected function countDecks($id =0) {
        try{
            $conn = DBConnection::getConnection();
            $sql = "SELECT COUNT(DISTINCT d.id) as decks_count FROM decks d 
                INNER JOIN decks_has_colors dhcol ON d.id = dhcol.deck_id
                WHERE d.`name` LIKE ?";
            if(count($this->search_color)) {
                $sql .= " AND dhcol.color_id IN(" . implode(',', $this->search_color) . ")";
            }
            if($this->search_format > 0) {
                $sql .= " AND d.format_id = " . $this->search_format;
            }
            if($id > 0) {
                $sql .= " and d.user_id = " . $id;
            }
            $stmt = $conn->prepare($sql);
            $d = new Disinfect();
            $name = $this->search_name . "%";
            $d->disinfect($name);
            $stmt->bind_param('s', $name);
            $stmt->execute();
            $result = $stmt->get_result();

            $row = $result->fetch_assoc();
            $this->search_count = $row['decks_count'];
        }catch(Exception $e){
            die($e->getMessage());
        }
    }



    public function getDecks($id =0){
        try{
            //get Deck name, deck format, deck user, deck color, deck first card
            // d.name, f.format, u.nickname, col.color, card.image_uris
            $conn = DBConnection::getConnection();
            $sql = "SELECT d.name, d.id, f.format, u.nickname, group_concat(col.color) as colors, card.image_uris FROM decks d
               INNER JOIN decks_has_colors dhcol ON d.id = dhcol.deck_id
                INNER JOIN colors col ON dhcol.color_id = col.id
                INNER JOIN decks_has_cards dhcard ON d.id = dhcard.deck_id
                INNER JOIN cards card ON dhcard.cards_id = card.id
                INNER JOIN formats f ON d.format_id = f.id
                INNER JOIN users u ON d.user_id = u.id";
            if($id > 0) {
                $sql .=  " WHERE user_id =" . $id;
            }
            $sql .=  " GROUP BY d.name, d.ID, f.format, u.nickname";
            $stmt = $conn->prepare($sql);
            /*if($id > 0) {
                $stmt->bind_param('i', $id);
            }*/
            $stmt->execute();
            $result = $stmt->get_result();


            return $result->fetch_all();
        }catch(Exception $e){
            die($e->getMessage());
        }
    }
}
