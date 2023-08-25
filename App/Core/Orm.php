<?php

class Orm
{
    protected $table;
    protected $db;

    public function __construct($table, PDO $connection)
    {
        $this->table = $table;
        $this->db = $connection;
    }

    public function getAll()
    {
        $stm = $this->db->prepare("SELECT * FROM {$this->table}");
        $stm->execute();
        return $stm->fetchAll();
    }

    public function deleteById($id)
    {
        $stm = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stm->bindValue(':id', $id);
        $stm->execute();
    }

    public function deleteByName($name)
    {
        $stm = $this->db->prepare("DELETE FROM {$this->table} WHERE name = :name");
        $stm->bindParam(':name', $name, PDO::PARAM_STR);
        $stm->execute();
    }

    public function updateById($id, $data)
    {
        $sql = "UPDATE {$this->table} SET ";
        foreach ($data as $key => $value) {
            if ($key != "id") {
                $sql .= "{$key} = :{$key},";
            }
        }
        $sql = trim($sql, ',');
        $sql .= " WHERE id = :id ";
        $stm = $this->db->prepare($sql);

        foreach ($data as $key => $value) {
            if ($key != "id") {
                $stm->bindValue(":{$key}", $value);
            }
        }

        $stm->bindValue(":id", $id);
        $stm->execute();
    }

    public function insert($data)
    {
        $sql = "INSERT INTO {$this->table} (";
        foreach ($data as $key => $value) {
            if ($key != "id") {
                $sql .= "{$key},";
            }
        }
        $sql = trim($sql, ',');
        $sql .= ") VALUES (";
        foreach ($data as $key => $value) {
            if ($key != "id") {
                $sql .= ":{$key},";
            }
        }
        $sql = trim($sql, ',');
        $sql .= ")";

        $stm = $this->db->prepare($sql);
        foreach ($data as $key => $value) {
            if ($key != "id") {
                $stm->bindValue(":{$key}", $value);
            }
        }
        $stm->execute();
    }

    ////////////////////////////////////////////////////////////////////////////////////
    // CONSULT ALL EVENTS
    ////////////////////////////////////////////////////////////////////////////////////

    public function getAllDates()
    {
        $stm = $this->db->prepare("SELECT id, name, 
        CONVERT(VARCHAR(11), start_date, 106) as start_date, 
        CONVERT(VARCHAR(11), end_date, 106) as end_date, 
        CONVERT(VARCHAR(11), ins_start_date, 106) as ins_start_date, 
        CONVERT(VARCHAR(11), ins_end_date, 106) as ins_end_date FROM {$this->table}");
        $stm->execute();
        return $stm->fetchAll();
    }

    ////////////////////////////////////////////////////////////////////////////////////
    // CONSULT SINGLE EVENT
    ////////////////////////////////////////////////////////////////////////////////////

    public function getEvent()
    {
        date_default_timezone_set("America/Mexico_City"); // Set your desired time zone
        $actualDate = date("Y-m-d");
        $stm = $this->db->prepare("SELECT name, 
        CONVERT(VARCHAR(11), start_date, 106) as start_date, 
        CONVERT(VARCHAR(11), end_date, 106) as end_date, 
        CONVERT(VARCHAR(11), ins_start_date, 106) as ins_start_date, 
        CONVERT(VARCHAR(11), ins_end_date, 106) as ins_end_date 
        FROM {$this->table}
        WHERE CONVERT(DATE, :target_date) BETWEEN start_date AND end_date;");
        $stm->bindParam(':target_date', $actualDate);
        $stm->execute();
        return $stm->fetch();
    }

    ////////////////////////////////////////////////////////////////////////////////////
    // CONSULT EMPLOYEES
    ////////////////////////////////////////////////////////////////////////////////////

    public function getAllEmployees()
    {
        $stm = $this->db->prepare("SELECT E.id, E.no_employee, E.name_s, E.father_last_name, E.mother_last_name, E.role_emp, E.id_dependency,
        CASE WHEN E.is_active = 0 THEN '<i class=\"fa-solid fa-xmark\"></i>' WHEN E.is_active = 1 THEN '<i class=\"fa-solid fa-check\"></i>'
        END AS is_active, D.name AS dependency_name FROM dbo.Employee E JOIN dbo.Dependency D ON E.id_dependency = D.id;");
        $stm->execute();
        return $stm->fetchAll();
    }

    ////////////////////////////////////////////////////////////////////////////////////
    // CONSULT DEPENDENCY
    ////////////////////////////////////////////////////////////////////////////////////

    public function getDependencies()
    {
        $stm = $this->db->prepare("SELECT id, name FROM {$this->table}");
        $stm->execute();
        return $stm->fetchAll();
    }


}