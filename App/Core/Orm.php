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

  public function getDistinct($column, $order)
  {
    $stm = $this->db->prepare("SELECT DISTINCT $column FROM {$this->table} ORDER BY $order");
    $stm->execute();
    return $stm->fetchAll();
  }

  public function getByBetween($target, $between1, $between2)
  {
    $stm = $this->db->prepare("SELECT * FROM {$this->table} WHERE :target BETWEEN $between1 AND $between2");
    $stm->bindValue(':target', $target);
    $stm->execute();
    return $stm->fetch();
  }

  public function getByJOINS($distinct, $columns, $joinTables, $conditionals, $order)
  {
    if (!$distinct) {
      $sql = "SELECT";
    } else {
      $sql = "SELECT DISTINCT";
    }
    foreach ($columns as $key => $value) {
      $sql .= " {$key} AS {$value},";
    }
    $sql = trim($sql, ',');
    $sql .= " FROM {$this->table}";
    foreach ($joinTables as $key => $value) {
      $sql .= " JOIN {$key} ON {$value}";
    }
    $sql .= " WHERE";
    foreach ($conditionals as $key => $value) {
      $suffix = str_replace('.', '_', $key);
      $sql .= " {$key} = :{$suffix}";
      $sql .= " AND";
    }
    $sql = trim($sql, 'WHERE');
    $sql = trim($sql, 'AND');
    $sql .= " ORDER BY $order";
    $stm = $this->db->prepare($sql);
    foreach ($conditionals as $key => $value) {
      $key = str_replace('.', '_', $key);
      $stm->bindValue(":{$key}", $value);
    }
    $stm->execute();
    return $stm->fetchAll();
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
    $stm = $this->db->prepare("DELETE FROM Player
    WHERE id_team IN (
        SELECT T.id
        FROM Team T
        INNER JOIN Dependency_Sport DS ON T.id_dependency_sport = DS.id
        INNER JOIN Sport S ON DS.id_sport = S.id
        WHERE S.id = :id
    );
    ");
    $stm->bindValue(':id', $id);
    $stm->execute();
    $stm = $this->db->prepare("DELETE FROM Team
    WHERE id_dependency_sport IN (
        SELECT DS.id
        FROM Dependency_Sport DS
        WHERE DS.id_sport = :id
    );
    ");
    $stm->bindValue(':id', $id);
    $stm->execute();
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
}