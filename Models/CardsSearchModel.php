<?php


namespace Models;

use Database\DBConnection;
use Helpers\disinfect;
use mysqli;


class CardsSearchModel
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

    protected function searchCards($id =0)
    {
        try{
            $conn = DBConnection::getConnection();
            $sql = "SELECT c.id, c.image_uris, c.name FROM cards c
                INNER JOIN cards_has_colors chc ON c.id = chc.cards_id
                INNER JOIN cards_has_formats_has_legalities chfhl ON c.id = chfhl.cards_id
                INNER JOIN users_has_cards uhc ON c.id = uhc.cards_id
                INNER JOIN set_edition se ON c.set_name = se.id
                WHERE `name` LIKE ? 
                AND type_line LIKE ?";

            /*
             * SELECT c.id, c.image_uris, c.name FROM cards c
                INNER JOIN cards_has_colors chc ON c.id = chc.cards_id
                INNER JOIN cards_has_formats_has_legalities chfhl ON c.id = chfhl.cards_id
                INNER JOIN users_has_cards uhc ON c.id = uhc.cards_id
                INNER JOIN set_edition se ON c.set_name = se.id
                WHERE `name` LIKE 'sel%' AND
              	 type_line LIKE '%cre%'
                GROUP BY c.name, c.id
             *
             * */
            if(count($this->search_color)) {
                $sql .= " AND chc.colors_id IN(" . implode(',', $this->search_color) . ")";
            }
            if($this->search_format > 0) {
                $sql .= " AND c.format_id = " . $this->search_format; // = legal ?
            }
            if($this->search_format > 0) {
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
            $sql = "SELECT COUNT(*) as cards_count FROM cards 
                INNER JOIN users_has_cards uhc ON c.id = uhc.cards_id
                WHERE `name` LIKE ?";

            if($id > 0) {
                $sql .= " and uhc.users_id = " . $id;
            }
            $stmt = $conn->prepare($sql);
            $d = new Disinfect();
            $name = $this->search_name . "%";
            $d->disinfect($name);
            $stmt->bind_param('s', $name);
            $stmt->execute();
            $result = $stmt->get_result();

            $row = $result->fetch_assoc();
            $this->search_count = $row['cards_count'];
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

}
