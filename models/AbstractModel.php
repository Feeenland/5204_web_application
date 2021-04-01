<?php


abstract class AbstractModel
{
    protected $table;

    protected function getBySingleField($field, $value, $type = 's')
    {
        try{
            $conn = DBConnection::getConnection();
            $sql = "SELECT * FROM " . $this->table . " WHERE " . $field . " = ?";
            $stmt = $conn->prepare($sql);
            if ($type === 's') {
                $value = disinfect($value);
            }
            $stmt->bind_param($type, $value);
            $stmt->execute();
            return $stmt->get_result();
        }catch(Exception $e){
            die($e->getMessage());
        }
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
                        disinfect($value); //save disinfected value in array
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
                $values = implode("', '", $values); //separate by comma
                $values = "'".$values."'";
                $conn = DBConnection::getConnection();
                $sql = "UPDATE " . $this->table . " SET " . $fieldsArray . " WHERE id = ?";
                //UPDATE users SET id= 1, name = 'testX' ,nickname = 'test' WHERE id = '1'
                print "SQL statement:  " .$sql ."  ";
                $stmt = $conn->prepare($sql);
                print "type: " .$type ."  values: " . $values;
                $stmt->bind_param($type, $values);
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
                        $value = disinfect($value);
                        $type = $type . "s"; //$type + s
                        disinfect($value); //save disinfected value in array
                    }
                }
                $values = implode("', '", $values); //separate by comma
                $values = "'".$values."'";
                $conn = DBConnection::getConnection();
                $sql = "INSERT INTO  " . $this->table . "(" . $fieldsArray . ")" . " VALUES " . "(" . $str . ")" ;
                $stmt = $conn->prepare($sql);
                print $sql;   print $type ." " . $values;
                //INSERT INTO users(name,nickname,favourite_card,password) VALUES ('test3','test4','island','test');
                $stmt->bind_param($type, $values);
                $stmt->execute();
                print "save ";
                return $stmt->insert_id;
            }catch(Exception $e){
                die($e->getMessage());
            }
        }
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
}