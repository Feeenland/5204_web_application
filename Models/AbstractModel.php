<?php

namespace Models;
use Database\DBConnection;
use mysqli;
use Helpers\disinfect;

abstract class AbstractModel
{
    protected $table;
    protected $fields = [];
    protected $values = [];



    protected function getBySingleField($field, $value, $type = 's')
    {
        try{
            $conn = DBConnection::getConnection();
            $sql = "SELECT * FROM " . $this->table . " WHERE " . $field . " = ?";
            $stmt = $conn->prepare($sql);
            if ($type === 's') {
                $d = new Disinfect();
                $d->disinfect($value);
            }
            $stmt->bind_param($type, $value);
            $stmt->execute();
            return $stmt->get_result();
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function save()
    {
        $this->saveValues(
            $this->fields,
            $this->values,
            $this->getFieldValue('id') ?
                $this->getFieldValue('id') : 0
        );
    }

    public function GetAllEntries($groupBy) {
        try{
            $conn = DBConnection::getConnection();
            $sql = "SELECT * FROM " . $this->table . " GROUP BY " . $groupBy;
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }catch(Exception $e){
            die($e->getMessage());
        }
    }
    public function CountAllEntries() {
        try{
            $conn = DBConnection::getConnection();
            $sql = "SELECT COUNT(*) FROM " . $this->table ;
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function GetAllByFKId($id, $field) {
        try{
            $conn = DBConnection::getConnection();
            $sql = "SELECT * FROM " . $this->table . " WHERE " .$field ."=" . $id; //SELECT * FROM `decks` WHERE user_id = 1
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function saveValues($fields, $values, $id = 0)
    {
        if (isset($id) && $id !=0){// update
            try{
                $fieldsArray = array();
                foreach ($fields as $field){
                    if ($field == 'id'){
                        //print 'is id!';

                    } else{
                        $fieldsArray[] = $field;
                        $str[] = '?';
                    }

                }
                $fieldsArray =implode("= ?, ", $fieldsArray) . '= ?'; //separate by comma
                //print $fieldsArray . '<br>';
                $conn = DBConnection::getConnection();
                $sql = "UPDATE " . $this->table . " SET " . $fieldsArray . " WHERE id = ?";
                //print "SQL statement:  " .$sql ."  ";
                $stmt = $conn->prepare($sql);

                // call method, override it in every specific model
                $this->bindMyParams($stmt, true);

                $stmt->execute();
                //print "update ";
                return true;
            }catch(Exception $e){
                die($e->getMessage());
            }
        }else{// Create
            try{
                $str = array();
                $fieldsArray = array();
                foreach ($fields as $field){
                    if ($field != 'id'){
                        $str[] = '?';
                        $fieldsArray[] = $field;
                    }
                }
                $str = implode(",", $str); //separate by comma
                $fieldsArray =implode(", ", $fieldsArray); //separate by comma
                //print $fieldsArray . '<br>';
                //print $str . '<br>';
                $conn = DBConnection::getConnection();
                //$sql = "INSERT INTO  " . $this->table . "(" . $fieldsArray . ")" . " VALUES " . "(" . $str . ")" ;
                $sql = "INSERT INTO  " . $this->table . "(" . $fieldsArray . ")" . " VALUES " . "(" . $str . ")" ;
                //print $sql;
                $stmt = $conn->prepare($sql);
                //INSERT INTO users(name,nickname,favourite_card,password) VALUES ('test3','test4','island','test');
                $stmt = $this->bindMyParams($stmt, false);
                $stmt->execute();
                print 'saved? ';
                return $stmt->insert_id;
            }catch(Exception $e){
                die($e->getMessage());
            }
        }
    }

    protected abstract function bindMyParams($stmt, $update = false);

    protected function loadManyToManyRelations($id_source, $fk_source, $fk_dest, $pivot_table)
    {
        $conn = DBConnection::getConnection();
        $sql = "SELECT " . $fk_dest . " From " . $pivot_table . " WHERE " . $fk_source . "=?" ;
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id_source);
        $stmt->execute();
        return $stmt->get_result()->fetch_all();
        // TODO to secure it by empty result or something

        // SELECT $fk_dest FROM $pivot_table WHERE $fk_source = $source_id
        // SELECT colors_id FROM cards_has_colors WHERE cards_id
    }
    protected function loadManyToManyRelationsSpecific($id_source, $id_dest, $fk_source, $fk_dest, $pivot_table)
    {
        $conn = DBConnection::getConnection();
        $sql = "SELECT " . $fk_dest . " From " . $pivot_table . " WHERE " . $fk_source . "=? AND " . $fk_dest . "=? " ;
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $id_source,$id_dest);
        $stmt->execute();
        return $stmt->get_result()->fetch_all();
        // TODO to secure it by empty result or something

        // SELECT $fk_dest FROM $pivot_table WHERE $fk_source = $source_id  AND $fk_dest = $id_dest
        // SELECT id FROM users_has_cards WHERE cards_id =4251 AND users_id = 17
    }

    protected function addManyToManyRelations( $pivot_table, $fk_dest, $fk_source, $fk_dest_value, $fk_source_value)
    {
        $conn = DBConnection::getConnection();
        $sql = "INSERT INTO " . $pivot_table . " (" . $fk_dest . "," . $fk_source . ") VALUES (?,?)" ;
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $fk_dest_value,$fk_source_value);
        $stmt->execute();
        return $stmt->get_result();

        // INSERT INTO $pivot_table ($fk_dest, $fk_source) VALUES (?, ?)
        // INSERT INTO decks_has_colors (color_id, deck_id) VALUES (1, 1)
    }

    protected function deleteByID($id)
    {
        try{
            $conn = DBConnection::getConnection();
            $sql = "DELETE FROM " . $this->table . " WHERE ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result();
        }catch(Exception $e){
            return false;
        }
    }

    public function toString(){
        $infos = '';
        foreach($this->values as $key => $value){
            $infos .= $key . ' - ' . $value . '<br>';

        }
        return $infos;
    }

    public function getFieldValue($fieldName){ // get value from one specific field
        if ( !in_array($fieldName, $this->fields)){
            return 'invalid field';
        }
        if (! array_key_exists($fieldName, $this->values)){
            return null;
        }
        return $this->values[$fieldName];
    }

    public function setFieldValue($fieldName, $value){
        if ( !in_array($fieldName, $this->fields)){
            return 'invalid field';
        }
        $this->values[$fieldName] = $value;
    }

}
