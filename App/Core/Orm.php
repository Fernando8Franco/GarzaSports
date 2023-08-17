<?php

class Orm {
    protected $table;
    protected $db;

    public function __construct($table, PDO $connection) {
        $this->table = $table;
        $this->db = $connection;
    }

    public function getAll() {
        $stm = $this->db->prepare("SELECT * FROM {$this->table}");
        $stm->execute();
        return $stm->fetchAll();
    }

    public function getIdByName($name) {
        $stm = $this->db->prepare("SELECT id FROM {$this->table} WHERE name = :name");
        $stm->bindParam(':name', $name, PDO::PARAM_STR);
        $stm->execute();
        return $stm->fetch();
    }

    public function getByName($name) {
        $stm = $this->db->prepare("SELECT * FROM {$this->table} WHERE name = :name");
        $stm->bindParam(':name', $name, PDO::PARAM_STR);
        $stm->execute();
        return $stm->fetch();
    }

    public function deleteByName($name) {
        $stm = $this->db->prepare("DELETE FROM {$this->table} WHERE name = :name");
        $stm->bindParam(':name', $name, PDO::PARAM_STR);
        $stm->execute();
    }

    public function updateById($id, $data) {
        $sql = "UPDATE {$this->table} SET ";
        foreach ($data as $key => $value) {
            $sql .= "{$key} = :{$key},";
        }
        $sql = trim($sql, ',');
        $sql .= " WHERE id = :id ";
        $stm = $this->db->prepare($sql);

        foreach($data as $key => $value) {
            $stm->bindValue(":{$key}", $value);
        }

        $stm->bindParam(":id", $id,  PDO::PARAM_INT);
        $stm->execute();
    }
    
    ////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////
    // CONSULTAS EVENTOS
    public function getAllDates() {
        $stm = $this->db->prepare("SELECT id, name, 
        CONVERT(VARCHAR(11), start_date, 106) as start_date, 
        CONVERT(VARCHAR(11), end_date, 106) as end_date, 
        CONVERT(VARCHAR(11), ins_start_date, 106) as ins_start_date, 
        CONVERT(VARCHAR(11), ins_end_date, 106) as ins_end_date FROM event ORDER BY id DESC");
        $stm->execute();
        return $stm->fetchAll();
    }

    public function insert($name, $start_event, $end_event, $ins_start_event, $ins_end_event) {
        $stm = $this->db->prepare("INSERT INTO {$this->table} (name, start_date, end_date, ins_start_date, ins_end_date)
        VALUES (:name, :start_event, :end_event, :ins_start_event, :ins_end_event)");
        $stm->bindParam(":name", $name);
        $stm->bindParam(":start_event", $start_event);
        $stm->bindParam(":end_event", $end_event);
        $stm->bindParam(":ins_start_event", $ins_start_event);
        $stm->bindParam(":ins_end_event", $ins_end_event);
        $stm->execute();
    }
}