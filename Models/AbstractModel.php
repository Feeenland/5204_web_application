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

    protected function searchByLetters($field, $value, $type = 's')
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

 /*   public function updateUserField(){ //to update a specific user field
        try{
            //UPDATE users SET login_try = 1 WHERE id= 4;
            $_value = 1;
            $_id = 4;
            $db_connection = $conn = DBConnection::getConnection();
            $stmt = $db_connection->prepare('UPDATE users SET login_try = ? WHERE id = ?');
            $stmt->bind_param('ii',$_value,  $_id);
            $stmt->execute();
            return true;
        }catch(Exception $exception){
        die('update failed');
        }
    }*/


    public function saveValues($fields, $values, $id = 0)
    {
        if (isset($id) && $id !=0){// update
            try{
                $fieldsArray = array();
                foreach ($fields as $field){
                    if ($field == 'id'){
                        print 'is id!';

                    } else{
                        $fieldsArray[] = $field;
                        $str[] = '?';
                    }

                }
                $fieldsArray =implode("= ?, ", $fieldsArray) . '= ?'; //separate by comma
                print $fieldsArray . '<br>';
                $conn = DBConnection::getConnection();
                $sql = "UPDATE " . $this->table . " SET " . $fieldsArray . " WHERE id = ?";
                print "SQL statement:  " .$sql ."  ";
                $stmt = $conn->prepare($sql);

                // call method, override it in every specific model
                $this->bindMyParams($stmt, true);

                $stmt->execute();
                print "update ";
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
                print $fieldsArray . '<br>';
                print $str . '<br>';
                $conn = DBConnection::getConnection();
                //$sql = "INSERT INTO  " . $this->table . "(" . $fieldsArray . ")" . " VALUES " . "(" . $str . ")" ;
                $sql = "INSERT INTO  " . $this->table . "(" . $fieldsArray . ")" . " VALUES " . "(" . $str . ")" ;
                $stmt = $conn->prepare($sql);
                //INSERT INTO users(name,nickname,favourite_card,password) VALUES ('test3','test4','island','test');
                print_r($stmt);
                $stmt = $this->bindMyParams($stmt, false);
                print_r($stmt);
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
