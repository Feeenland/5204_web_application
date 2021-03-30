<?php


abstract class AbstractModel
{
    protected $table;

    protected function getBySingleField($field, $value, $type = 's')
    {
        $conn = DBConnection::getConnection();
        $sql = "SELECT * FROM " . $this->table . " WHERE " . $field . " = ?";
        $stmt = $conn->prepare($sql);
        if ($type === 's') {
            $value = disinfect($value);
        }
        $stmt->bind_param($type, $value);

        $stmt->execute();
        // $stmt->bind_result($test);

        return $stmt->get_result();
    }

    protected function saveValues($fields, $values, $id = 0)
    {
        // id == 0 -> Insert


        // id != 0 -> Update

    }
}