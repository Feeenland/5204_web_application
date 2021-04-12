<?php

namespace Models;
use Database\DBConnection;
use Helpers;
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

    protected function saveValues($fields, $values, $id = 0)
    {
        if (isset($id) && $id !=0){// update
            try{
                $fieldsArray = array();
                foreach ($fields as $field){ //safe existing fields in a array with = ?,
                    if (isset($values[$field]) && $field != 'id'){
                        $fieldsArray[] = $field . " = ? ";
                    }
                }
                $fieldsArray =implode(",", $fieldsArray); //separate by comma
                $type = "";
                foreach ($values as $value){
                    $d = new Disinfect();
                    $d->disinfect($value);
                }
                $v = $values['id']; //save the id
                unset($values['id']); //delete the id
                $values['id'] = $v;// put the id at the end
                foreach ($values as $value){
                    if (is_numeric($value)) {
                        $type = $type . "i"; //$type + i
                    } else{
                        $type = $type . "s"; //$type + s
                    }
                }
                //$values = implode("', '", $values); //separate by comma
                //$values = "'".$values."'";
                print_r($values);
                $conn = DBConnection::getConnection();
                $sql = "UPDATE " . $this->table . " SET " . $fieldsArray . " WHERE id = ?";
                //UPDATE users name = 'testX' ,nickname = 'test' WHERE id = '1'
                print "SQL statement:  " .$sql ."  ";
                $stmt = $conn->prepare($sql);

                //call_user_func_array(array($stmt, "bind_param"), array_merge(array($type), $values));

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
                    if (isset($values[$field])){
                        $str[] = '?';
                        $fieldsArray[] = $field;
                    }
                }
                $str = implode(",", $str); //separate by comma
                $fieldsArray =implode(",", $fieldsArray); //separate by comma
                $type = "";
                print_r($values) ;
                foreach ($values as $value){
                    if (is_numeric($value)) {
                        $type = $type . "i"; //$type + i
                    } else{
                        $d = new Disinfect();
                        $d->disinfect($value);
                        $type = $type . "s"; //$type + s
                    }
                }
                $values = implode("', '", $values); //separate by comma
                $values = "'".$values."'";
                $conn = DBConnection::getConnection();
                $sql = "INSERT INTO  " . $this->table . "(" . $fieldsArray . ")" . " VALUES " . "(" . $str . ")" ;
                $stmt = $conn->prepare($sql);
                print $sql;   print $type ." " . $values;
                //INSERT INTO users(name,nickname,favourite_card,password) VALUES ('test3','test4','island','test');
                $this->bindMyParams($stmt, false);;
                $stmt->execute();
                print "save ";
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