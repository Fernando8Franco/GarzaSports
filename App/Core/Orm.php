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

  public function deleteByIdEvent($id)
  {
    try {
      $this->db->beginTransaction();
      $stm = $this->db->prepare("DELETE FROM dbo.Player WHERE id_team IN (SELECT id FROM dbo.Team WHERE id_event = :eventoID)");
      $stm->bindParam(':eventoID', $id, PDO::PARAM_INT);
      $stm->execute();

      $stm = $this->db->prepare("DELETE FROM dbo.Team WHERE id_event = :eventoID");
      $stm->bindParam(':eventoID', $id, PDO::PARAM_INT);
      $stm->execute();

      $stm = $this->db->prepare("DELETE FROM dbo.Event WHERE id = :eventoID");
      $stm->bindParam(':eventoID', $id, PDO::PARAM_INT);
      $stm->execute();

      $this->db->commit();
    } catch (Exception $e) {
      $this->db->rollBack();
      echo "Error: " . $e->getMessage();
    }
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

  public function deleteByIdTeam($id)
  {
    $stm = $this->db->prepare("DELETE FROM Player WHERE id_team = :id_team");
    $stm->bindParam(':id_team', $id, PDO::PARAM_INT);
    $stm->execute();
    $stm = $this->db->prepare("DELETE FROM Team WHERE id = :id");
    $stm->bindParam(':id', $id, PDO::PARAM_INT);
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

  public function getEventByIns()
  {
    date_default_timezone_set("America/Mexico_City"); // Set your desired time zone
    $actualDate = date("Y-m-d");
    $stm = $this->db->prepare("SELECT id, name, 
        CONVERT(VARCHAR(11), start_date, 106) as start_date, 
        CONVERT(VARCHAR(11), end_date, 106) as end_date, 
        CONVERT(VARCHAR(11), ins_start_date, 106) as ins_start_date, 
        CONVERT(VARCHAR(11), ins_end_date, 106) as ins_end_date 
        FROM {$this->table}
        WHERE CONVERT(DATE, :target_date) BETWEEN ins_start_date AND ins_end_date;");
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
  // CONSULT EMPLOYEES
  ////////////////////////////////////////////////////////////////////////////////////
  public function getAllSports()
  {
    $stm = $this->db->prepare("SELECT id, name, type, gender, num_players, num_extraplayers,
        CASE WHEN has_captain = 0 THEN '<i class=\"fa-solid fa-xmark\"></i>' WHEN has_captain = 1 THEN '<i class=\"fa-solid fa-check\"></i>'
        END AS has_captain FROM {$this->table}");
    $stm->execute();
    return $stm->fetchAll();
  }

  ////////////////////////////////////////////////////////////////////////////////////
  // CONSULT DEPENDENCY
  ////////////////////////////////////////////////////////////////////////////////////
  public function getDependencies()
  {
    $stm = $this->db->prepare("SELECT id, name FROM {$this->table} ORDER BY name");
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
        WHERE D.id = :dependency AND DS.is_active = 1
        ORDER BY S.gender");
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
        WHERE D.id = :dependency AND DS.is_active = 1 AND S.gender = :gender
        ORDER BY S.name");
    $stm->bindParam(':dependency', $dependency);
    $stm->bindParam(':gender', $gender);
    $stm->execute();
    return $stm->fetchAll();
  }

  ////////////////////////////////////////////////////////////////////////////////////
  // CONSULT TEAM BY DATE
  ////////////////////////////////////////////////////////////////////////////////////
  public function getAllTeamsByDate()
  {
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

  public function getRegister()
  {
    date_default_timezone_set("America/Mexico_City");
    $actualDate = date("Y-m-d");
    $stm = $this->db->prepare("SELECT P.id AS Player_ID, P.acc_number AS Player_Account_Number, P.name_s AS Player_Name,
        P.father_last_name AS Player_Father_Last_Name, P.mother_last_name AS Player_Mother_Last_Name, P.birthday AS Player_Birthday,
        CASE WHEN P.gender = 'Hombre' THEN 'H' WHEN P.gender = 'Mujer' THEN 'M' END AS Player_Gender, P.phone_number AS Player_Phone_Number,
        P.email AS Player_Email, P.semester AS Player_Semester, P.group_num AS Player_Group_Number, P.photo AS Player_Photo,
        CASE WHEN P.is_captain = 0 THEN 'JUGADOR' WHEN P.is_captain = 1 THEN 'CAPITAN' END AS Player_Is_Captain,
        D.name AS Dependency_Name, S.name AS Sport_Name, S.gender AS Sport_Gender, T.name AS Team_Name,
        CONVERT(VARCHAR(11), T.record_date, 106) AS Record_Date FROM dbo.Player AS P
        INNER JOIN dbo.Team AS T ON P.id_team = T.id
        INNER JOIN dbo.Dependency_Sport AS DS ON T.id_dependency_sport = DS.id
        INNER JOIN dbo.Dependency AS D ON DS.id_dependency = D.id
        INNER JOIN dbo.Sport AS S ON DS.id_sport = S.id
        JOIN dbo.Event AS E ON T.id_event = E.id
        WHERE CONVERT(DATE, :target_date) BETWEEN E.start_date AND E.end_date");
    $stm->bindParam(':target_date', $actualDate);
    $stm->execute();
    return $stm->fetchAll();
  }

  public function getRegisterByDependency($id_dependency)
  {
    date_default_timezone_set("America/Mexico_City");
    $actualDate = date("Y-m-d");
    $stm = $this->db->prepare("SELECT P.id AS Player_ID, P.acc_number AS Player_Account_Number, P.name_s AS Player_Name,
        P.father_last_name AS Player_Father_Last_Name, P.mother_last_name AS Player_Mother_Last_Name, P.birthday AS Player_Birthday,
        CASE WHEN P.gender = 'Hombre' THEN 'H' WHEN P.gender = 'Mujer' THEN 'M' END AS Player_Gender, P.phone_number AS Player_Phone_Number,
        P.email AS Player_Email, P.semester AS Player_Semester, P.group_num AS Player_Group_Number, P.photo AS Player_Photo,
        CASE WHEN P.is_captain = 0 THEN 'JUGADOR' WHEN P.is_captain = 1 THEN 'CAPITAN' END AS Player_Is_Captain,
        D.name AS Dependency_Name, S.name AS Sport_Name, S.gender AS Sport_Gender, T.name AS Team_Name,
        CONVERT(VARCHAR(11), T.record_date, 106) AS Record_Date FROM dbo.Player AS P
        INNER JOIN dbo.Team AS T ON P.id_team = T.id
        INNER JOIN dbo.Dependency_Sport AS DS ON T.id_dependency_sport = DS.id
        INNER JOIN dbo.Dependency AS D ON DS.id_dependency = D.id
        INNER JOIN dbo.Sport AS S ON DS.id_sport = S.id
        JOIN dbo.Event AS E ON T.id_event = E.id
        WHERE CONVERT(DATE, :target_date) BETWEEN E.start_date AND E.end_date
        AND D.id = :id_dependency");
    $stm->bindParam(':target_date', $actualDate);
    $stm->bindParam(':id_dependency', $id_dependency);
    $stm->execute();
    return $stm->fetchAll();
  }

  public function getCount($tableName1, $tableName2)
  {
    date_default_timezone_set("America/Mexico_City");
    $actualDate = date("Y-m-d");
    $stm = $this->db->prepare("SELECT
        (SELECT COUNT(*) FROM $tableName1) AS rows_$tableName1,
        (SELECT COUNT(*) FROM $tableName2) AS rows_$tableName2
        FROM dbo.Event AS E
        WHERE CONVERT(DATE, :target_date) BETWEEN E.start_date AND E.end_date");
    $stm->bindParam(':target_date', $actualDate);
    $stm->execute();
    return $stm->fetch();
  }

  public function getSportsByDependencyForStatus()
  {
    $stm = $this->db->prepare("SELECT DS.id, DS.is_active, D.name AS dependency_name, S.name AS sport_name, S.gender
    FROM Dependency_Sport AS DS
    JOIN Dependency AS D ON DS.id_dependency = D.id
    JOIN Sport AS S ON DS.id_sport = S.id");
    $stm->execute();
    return $stm->fetchAll();
  }

  public function getTeamByAccEmail($acc_number, $email)
  {
    date_default_timezone_set("America/Mexico_City");
    $actualDate = date("Y-m-d");
    $stm = $this->db->prepare("SELECT T.id
    FROM {$this->table} T
    INNER JOIN dbo.Player P ON T.id = P.id_team
    JOIN dbo.Event AS E ON CONVERT(DATE, :target_date) BETWEEN E.start_date AND E.end_date
    WHERE P.acc_number = :acc_number AND P.email = :email");
    $stm->bindParam(':acc_number', $acc_number, PDO::PARAM_STR);
    $stm->bindParam(':email', $email, PDO::PARAM_STR);
    $stm->bindParam(':target_date', $actualDate);
    $stm->execute();
    return $stm->fetch();
  }

  public function getTeamByAccEmailDependencyGenderSport($acc_number, $email, $dependency, $gender, $sport)
  {
    date_default_timezone_set("America/Mexico_City");
    $actualDate = date("Y-m-d");
    $stm = $this->db->prepare("SELECT T.id
    FROM {$this->table} T
    INNER JOIN dbo.Player P ON T.id = P.id_team
    JOIN dbo.Event AS E ON CONVERT(DATE, :target_date) BETWEEN E.start_date AND E.end_date
    JOIN dbo.Dependency_Sport DS ON P.id_dependency = DS.id_dependency
    JOIN dbo.Sport S ON DS.id_sport = S.id
    WHERE P.acc_number = :acc_number 
    AND P.email = :email 
    AND DS.id_dependency = :dependency 
    AND S.gender = :gender 
    AND S.id = :sport");
    $stm->bindParam(':acc_number', $acc_number, PDO::PARAM_STR);
    $stm->bindParam(':email', $email, PDO::PARAM_STR);
    $stm->bindParam(':dependency', $dependency, PDO::PARAM_INT);
    $stm->bindParam(':gender', $gender, PDO::PARAM_STR);
    $stm->bindParam(':sport', $sport, PDO::PARAM_STR);
    $stm->bindParam(':target_date', $actualDate);
    $stm->execute();
    return $stm->fetch();
  }



  public function getRegisterByTeam($team_id)
  {
    date_default_timezone_set("America/Mexico_City");
    $actualDate = date("Y-m-d");
    $stm = $this->db->prepare("SELECT P.id AS Player_ID, P.acc_number AS Player_Account_Number, P.name_s AS Player_Name,
        P.father_last_name AS Player_Father_Last_Name, P.mother_last_name AS Player_Mother_Last_Name, P.birthday AS Player_Birthday,
        CASE WHEN P.gender = 'Hombre' THEN 'H' WHEN P.gender = 'Mujer' THEN 'M' END AS Player_Gender, P.phone_number AS Player_Phone_Number,
        P.email AS Player_Email, P.semester AS Player_Semester, P.group_num AS Player_Group_Number, P.photo AS Player_Photo,
        CASE WHEN P.is_captain = 0 THEN 'JUGADOR' WHEN P.is_captain = 1 THEN 'CAPITAN' END AS Player_Is_Captain,
        D.name AS Dependency_Name, S.name AS Sport_Name, S.gender AS Sport_Gender, T.name AS Team_Name,
        CONVERT(VARCHAR(11), T.record_date, 106) AS Record_Date FROM dbo.Player AS P
        INNER JOIN dbo.Team AS T ON P.id_team = T.id
        INNER JOIN dbo.Dependency_Sport AS DS ON T.id_dependency_sport = DS.id
        INNER JOIN dbo.Dependency AS D ON DS.id_dependency = D.id
        INNER JOIN dbo.Sport AS S ON DS.id_sport = S.id
        JOIN dbo.Event AS E ON T.id_event = E.id
        WHERE CONVERT(DATE, :target_date) BETWEEN E.start_date AND E.end_date
        AND T.id = :team_id");
    $stm->bindParam(':target_date', $actualDate);
    $stm->bindParam(':team_id', $team_id);
    $stm->execute();
    return $stm->fetchAll();
  }
}