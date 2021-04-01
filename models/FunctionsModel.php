<?php


class FunctionsModel extends AbstractModel {

    public function updateSave($values){
        try{
            foreach ($this->fields as $field){ //put the incoming values in to $this->values
                if (array_key_exists($field, $values))
                    $this->setFieldValue($field, $values[$field]);
            }
            print $this->toString();
            $result = $this->saveValues($this->fields, $this->values, $this->values['id']);
            if ($result == false) {
                print 'Speichern fehlgeschlagen ';
                //die('Speichern fehlgeschlagen');
            } else {
                print " worked! ";
                return true;
            }
        }catch(Exception $exception){
            die('Problem with database');
            //return false;
        }
    }

    public function delete($id){
        try{
            $this->deleteByID($id);
            print "deleted";
        }catch(Exception $exception){
            die('Problem with database');
            //return false;
        }
    }

    public function setFieldValue($fieldName, $value){
        if ( !in_array($fieldName, $this->fields)){
            return 'invalid field';
        }
        $this->values[$fieldName] = $value;
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

    public function toString(){
        $infos = '';
        foreach($this->values as $key => $value){
            $infos .= $key . ' - ' . $value . '<br>';

        }
        return $infos;
    }
}






