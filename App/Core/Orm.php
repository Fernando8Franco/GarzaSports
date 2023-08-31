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

    public function getById($id)
    {
        $stm = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stm->bindValue(':id', $id);
        $stm->execute();
        return $stm->fetch();
    }

    public function deleteById($id)
    {
        $stm = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stm->bindValue(':id', $id);
        $stm->execute();
    }

    public function deleteByIdDependency_Sport($id)
    {
        $stm = $this->db->prepare("DELETE FROM Dependency_Sport WHERE id_{$this->table} = :id");
        $stm->bindValue(':id', $id);
        $stm->execute();
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
        $stm = $this->db->prepare("SELECT id, name, 
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

    ////////////////////////////////////////////////////////////////////////////////////
    // CONSULT CATEGORIES
    ////////////////////////////////////////////////////////////////////////////////////
    public function getCategories()
    {
        $stm = $this->db->prepare("SELECT DISTINCT category FROM {$this->table}");
        $stm->execute();
        return $stm->fetchAll();
    }

    ////////////////////////////////////////////////////////////////////////////////////
    // CONSULT DEPENDENCIES BY CATEGORY
    ////////////////////////////////////////////////////////////////////////////////////
    public function getDependenciesByCategory($category)
    {
        $stm = $this->db->prepare("SELECT id, name FROM {$this->table} WHERE category = :category");
        $stm->bindParam(':category', $category);
        $stm->execute();
        return $stm->fetchAll();
    }

    ////////////////////////////////////////////////////////////////////////////////////
    // CONSULT BRANCHES
    ////////////////////////////////////////////////////////////////////////////////////
    public function getbranches($dependency)
    {
        $stm = $this->db->prepare("SELECT DISTINCT S.gender
        FROM Dependency_Sport DS
        INNER JOIN Dependency D ON DS.id_dependency = D.id
        INNER JOIN Sport S ON DS.id_sport = S.id
        WHERE D.id = :dependency AND DS.is_active = 1");
        $stm->bindParam(':dependency', $dependency);
        $stm->execute();
        return $stm->fetchAll();
    }

    ////////////////////////////////////////////////////////////////////////////////////
    // CONSULT SPORTS BY DEPENDENCY
    ////////////////////////////////////////////////////////////////////////////////////
    public function getSportsByDependency($dependency, $gender)
    {
        $stm = $this->db->prepare("SELECT S.id AS id, S.name AS name
        FROM Dependency_Sport DS
        INNER JOIN Dependency D ON DS.id_dependency = D.id
        INNER JOIN Sport S ON DS.id_sport = S.id
        WHERE D.id = :dependency AND DS.is_active = 1 AND S.gender = :gender");
        $stm->bindParam(':dependency', $dependency);
        $stm->bindParam(':gender', $gender);
        $stm->execute();
        return $stm->fetchAll();
    }

    ////////////////////////////////////////////////////////////////////////////////////
    // CONSULT TEAM BY DATE
    ////////////////////////////////////////////////////////////////////////////////////
    public function getAllTeamsByDate() {
        date_default_timezone_set("America/Mexico_City"); // Set your desired time zone
        $actualDate = date("Y-m-d");
        $stm = $this->db->prepare("SELECT T.id, T.name AS name, CONVERT(VARCHAR(11), T.record_date, 106) AS record_date,
        D.name AS dependency_name, S.name AS sport_name, E.name AS event_name
        FROM dbo.Team AS T
        JOIN dbo.Dependency_Sport AS DS ON T.id_dependency_sport = DS.id
        JOIN dbo.Dependency AS D ON DS.id_dependency = D.id
        JOIN dbo.Sport AS S ON DS.id_sport = S.id
        JOIN dbo.Event AS E ON T.id_event = E.id
        WHERE CONVERT(DATE, :target_date) BETWEEN E.start_date AND E.end_date AND DS.is_active = 1");
        $stm->bindParam(':target_date', $actualDate);
        $stm->execute();
        return $stm->fetchAll();
    }
}