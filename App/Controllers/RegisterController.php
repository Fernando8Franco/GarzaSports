<?php

require_once(__DIR__ . '/../models/team.php');
require_once(__DIR__ . '/../models/player.php');
require_once(__DIR__ . '/../models/event.php');

class RegisterController extends Controller
{
  private $con;
  private $teamModel;
  private $eventModel;
  private $playerModel;

  public function __construct(PDO $con)
  {
    parent::__construct($con);
    $this->con = $con;
    $this->teamModel = new Team($con);
    $this->playerModel = new Player($con);
    $this->eventModel = new Event($con);
  }

  public function index()
  {
    if ($_SESSION['role_emp'] == 'Administrador') {
      $this->renderView('register');
    }
  }

  public function registerByDependency()
  {
    if ($_SESSION['role_emp'] == 'Empleado') {
      $this->renderView('registerByDependency');
    }
  }

  public function registerCRUD()
  {
    $option = $_POST['option'] ?? '';
    $id_dependency = $_POST['id_dependency'] ?? '';
    if (empty($id_dependency)) {
      $id_dependency = $_SESSION['id_dependency'];
    }
    date_default_timezone_set("America/Mexico_City");
    $actualDate = date("Y-m-d");
    $event = $this->eventModel->getByBetween($actualDate, 'start_date', 'end_date');

    $columns = [
      'Player.id' => 'Player_ID',
      'Player.acc_number' => 'Player_Account_Number',
      'Player.name_s' => 'Player_Name',
      'Player.father_last_name' => 'Player_Father_Last_Name',
      'Player.mother_last_name' => 'Player_Mother_Last_Name',
      'Player.birthday' => 'Player_Birthday',
      'Player.gender' => 'Player_Gender',
      'Player.phone_number' => 'Player_Phone_Number',
      'Player.email' => 'Player_Email',
      'Player.semester' => 'Player_Semester',
      'Player.group_num' => 'Player_Group_Number',
      'Player.photo' => 'Player_Photo',
      'Player.is_captain' => 'Player_Is_Captain',
      'Dependency.name' => 'Dependency_Name',
      'Dependency.category' => 'Dependency_Category',
      'Sport.name' => 'Sport_Name',
      'Sport.gender' => 'Sport_Gender',
      'Team.name' => 'Team_Name',
      'Team.record_date' => 'Record_Date',
    ];
    $joinTables = [
      'Team' => 'Player.id_team = Team.id',
      'Dependency_Sport' => 'Team.id_dependency_sport = Dependency_Sport.id',
      'Dependency' => 'Dependency_Sport.id_dependency = Dependency.id',
      'Sport' => 'Dependency_Sport.id_sport = Sport.id',
      'Event' => 'Team.id_event = Event.id',
    ];
    $conditionals = [
      'Event.id' => ($event !== false) ? $event['id'] : -1,
    ];
    $conditionalsDependency = [
      'Event.id' => ($event !== false) ? $event['id'] : -1,
      'Dependency.id' => $id_dependency,
    ];

    switch ($option) {
      case 4:
        if ($id_dependency == "-1") {
          $registers = $this->playerModel->getByJOINS(false, $columns, $joinTables, $conditionals, 'Team.id');
        } else {
          $registers = $this->playerModel->getByJOINS(false, $columns, $joinTables, $conditionalsDependency, 'Team.id');
        }
        break;
    }

    foreach ($registers as &$register) {
      $register['Player_Birthday'] = $this->calculateAge($register['Player_Birthday']);
      $register['Record_Date'] = $this->formatDate($register['Record_Date']);
      $register['Player_Is_Captain'] = ($register['Player_Is_Captain']) ? 'CAPITAN' : 'JUGADOR';
    }

    echo json_encode($registers, JSON_UNESCAPED_UNICODE);
  }

  public function registersData()
  {
    $id_dependency = $_SESSION['id_dependency'];
    date_default_timezone_set("America/Mexico_City");
    $actualDate = date("Y-m-d");
    $event = $this->eventModel->getByBetween($actualDate, 'start_date', 'end_date');
    $columns = [
      '(SELECT COUNT(*) FROM team)' => 'rows_team',
      '(SELECT COUNT(*) FROM player)' => 'rows_player',
    ];
    $conditionals = [
      'Event.id' => ($event !== false) ? $event['id'] : -1,
    ];
    $columnsDependency = [
      "(SELECT COUNT(*) FROM Team T JOIN Dependency_Sport DS ON T.id_dependency_sport = DS.id WHERE DS.id_dependency = $id_dependency)" => 'rows_team',
      "(SELECT COUNT(*) FROM Player P WHERE P.id_dependency = $id_dependency)" => 'rows_player',
    ];
    $conditionalsDependency = [
      'Event.id' => ($event !== false) ? $event['id'] : -1,
    ];

    if ($_SESSION['role_emp'] == 'Administrador') {
      $registerData = $this->eventModel->getByJOINS(false, $columns, [], $conditionals, 'Event.id');
    } else if ($_SESSION['role_emp'] == 'Empleado') {
      $registerData = $this->eventModel->getByJOINS(false, $columnsDependency, [], $conditionalsDependency, 'Event.id');
    }
    
    if ($registerData) {
      $registerData = $registerData[0];
      echo json_encode($registerData, JSON_UNESCAPED_UNICODE);
    } else {
      $defaultRecord = [
        'rows_team' => '0',
        'rows_player' => '0',
      ];
      echo json_encode($defaultRecord, JSON_UNESCAPED_UNICODE);
    }
  }

  public function getRegisterByTeam()
  {
    $acc_number = $_POST['acc_number'] ?? '';
    $email = $_POST['email'] ?? '';
    $dependency = $_POST['dependency'] ?? '';
    $sport = $_POST['sport'] ?? '';

    date_default_timezone_set("America/Mexico_City");
    $actualDate = date("Y-m-d");
    $event = $this->eventModel->getByBetween($actualDate, 'start_date', 'end_date');

    $columns = [
      'Team.id' => 'id',
    ];
    $joinTables = [
      'Player' => 'Team.id = Player.id_team',
      'Event' => 'Team.id_event = Event.id',
      'Dependency_Sport' => 'Team.id_dependency_sport = Dependency_Sport.id',
      'Sport' => 'Dependency_Sport.id_sport = Sport.id',
    ];
    $conditionals = [
      'Player.acc_number' => $acc_number,
      'Player.email' => $email,
      'Dependency_Sport.id_dependency' => $dependency,
      'Sport.id' => $sport,
      'Event.id' => ($event !== false) ? $event['id'] : -1,
    ];

    $id_team = $this->teamModel->getByJOINS(false, $columns, $joinTables, $conditionals, 'Team.id');

    if (!empty($id_team[0])) {
      $columnsPlayer = [
        'Player.id' => 'Player_ID',
        'Player.acc_number' => 'Player_Account_Number',
        'Player.name_s' => 'Player_Name',
        'Player.father_last_name' => 'Player_Father_Last_Name',
        'Player.mother_last_name' => 'Player_Mother_Last_Name',
        'Player.birthday' => 'Player_Birthday',
        'Player.gender' => 'Player_Gender',
        'Player.phone_number' => 'Player_Phone_Number',
        'Player.email' => 'Player_Email',
        'Player.semester' => 'Player_Semester',
        'Player.group_num' => 'Player_Group_Number',
        'Player.photo' => 'Player_Photo',
        'Player.is_captain' => 'Player_Is_Captain',
        'Dependency.name' => 'Dependency_Name',
        'Dependency.category' => 'Dependency_Category',
        'Sport.name' => 'Sport_Name',
        'Sport.gender' => 'Sport_Gender',
        'Team.name' => 'Team_Name',
        'Team.record_date' => 'Record_Date',
      ];
      $joinTablesPlayer = [
        'Team' => 'Player.id_team = Team.id',
        'Dependency_Sport' => 'Team.id_dependency_sport = Dependency_Sport.id',
        'Dependency' => 'Dependency_Sport.id_dependency = Dependency.id',
        'Sport' => 'Dependency_Sport.id_sport = Sport.id',
        'Event' => 'Team.id_event = Event.id',
      ];
      $conditionalsPlayer = [
        'Event.id' => ($event !== false) ? $event['id'] : -1,
        'Team.id' => $id_team[0]['id'],
      ];

      $registers = $this->playerModel->getByJOINS(false, $columnsPlayer, $joinTablesPlayer, $conditionalsPlayer, 'Player.id');
      foreach ($registers as &$register) {
        $register['Player_Birthday'] = $this->calculateAge($register['Player_Birthday']);
        $register['Record_Date'] = $this->formatDate($register['Record_Date']);
        $register['Player_Is_Captain'] = ($register['Player_Is_Captain']) ? 'CAPITAN' : 'JUGADOR';
      }
      echo json_encode($registers, JSON_UNESCAPED_UNICODE);
    } else {
      $defaultRecord = [
        'Player_ID' => 'NO REGISTRADO',
      ];
      $register = array($defaultRecord);
      echo json_encode($register, JSON_UNESCAPED_UNICODE);
    }
  }

  public function internRegister()
  {
    $teamName = $_POST["team_name"] ?? '';
    $dependencyId = $_POST["id_dependency"][0] ?? '';
    $sportId = $_POST["id_sport"][0] ?? '';

    $sqlDependecySport = "SELECT id FROM Dependency_Sport WHERE id_dependency = :id_dependency AND id_sport = :id_sport AND is_active = 1";
    $stmDependencySport = $this->con->prepare($sqlDependecySport);
    $stmDependencySport->bindValue(":id_dependency", $dependencyId);
    $stmDependencySport->bindValue(":id_sport", $sportId);
    $stmDependencySport->execute();
    $idDependecySport = $stmDependencySport->fetchColumn();

    date_default_timezone_set("America/Mexico_City");
    $actualDate = date("Y-m-d");
    $queryEvent = "SELECT id FROM Event WHERE :targetDate BETWEEN ins_start_date AND ins_end_date";
    $stm = $this->con->prepare($queryEvent);
    $stm->bindValue(":targetDate", $actualDate);
    $stm->execute();
    $idEvent = $stm->fetchColumn();

    try {
      $this->con->beginTransaction();

      $sqlInsertTeam = "INSERT INTO Team (name, record_date, id_dependency_sport, id_event) VALUES (:teamName, CONVERT(DATE, :record_date), :id_dependency_sport, :id_event)";
      $stmInsertTeam = $this->con->prepare($sqlInsertTeam);
      $stmInsertTeam->bindValue(':teamName', $teamName);
      $stmInsertTeam->bindValue(':record_date', $actualDate);
      $stmInsertTeam->bindValue(':id_dependency_sport', $idDependecySport);
      $stmInsertTeam->bindValue(':id_event', $idEvent);
      $stmInsertTeam->execute();

      $id_team = $this->con->lastInsertId();

      for ($i = 0; $i < count($_POST["acc_number"]); $i++) {
        $acc_number = $_POST["acc_number"][$i] ?? '';
        $name = $_POST["name"][$i] ?? '';
        $father_last_name = $_POST["father_last_name"][$i] ?? '';
        $mother_last_name = $_POST["mother_last_name"][$i] ?? '';
        $birthday = $_POST["birthday"][$i] ?? '';
        $gender = $_POST["gender"][$i] ?? '';
        $phone_number = $_POST["phone_number"][$i] ?? '';
        $email = $_POST["email"][$i] ?? '';
        $semester = $_POST["semester"][$i] ?? '';
        $group_num = $_POST["group_num"][$i] ?? '';
        $photo = $_POST["photo"][$i] ?? '';
        $is_captain = $_POST["is_captain"][$i] ?? '';

        if (empty($acc_number) || empty($name) || empty($father_last_name) || empty($mother_last_name) || empty($birthday) || empty($gender) || empty($phone_number) || empty($email) || empty($semester) || empty($group_num) || empty($photo)) {
          throw new Exception("Falta uno o más datos obligatorios del jugador.");
        }

        $sqlInsertPlayer = "INSERT INTO Player (acc_number, name_s, father_last_name, mother_last_name, birthday, gender, phone_number, email, semester, group_num, photo, is_captain, id_dependency, id_team) 
                                          VALUES (:acc_number, :name_s, :father_last_name, :mother_last_name, :birthday, :gender, :phone_number, :email, :semester, :group_num, :photo, :is_captain, :id_dependency, :id_team)";
        $stmtInsertPlayer = $this->con->prepare($sqlInsertPlayer);
        $stmtInsertPlayer->bindValue(':acc_number', $acc_number);
        $stmtInsertPlayer->bindValue(':name_s', $name);
        $stmtInsertPlayer->bindValue(':father_last_name', $father_last_name);
        $stmtInsertPlayer->bindValue(':mother_last_name', $mother_last_name);
        $stmtInsertPlayer->bindValue(':birthday', $birthday);
        $stmtInsertPlayer->bindValue(':gender', $gender);
        $stmtInsertPlayer->bindValue(':phone_number', $phone_number);
        $stmtInsertPlayer->bindValue(':email', $email);
        $stmtInsertPlayer->bindValue(':semester', $semester);
        $stmtInsertPlayer->bindValue(':group_num', $group_num);
        $stmtInsertPlayer->bindValue(':photo', $photo);
        $stmtInsertPlayer->bindValue(':is_captain', $is_captain);
        $stmtInsertPlayer->bindValue(':id_dependency', $dependencyId);
        $stmtInsertPlayer->bindValue(':id_team', $id_team);
        $stmtInsertPlayer->execute();
      }

      $this->con->commit();

      $register = $this->teamModel->getRegisterByTeam($id_team);
      echo json_encode($register, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
      // En caso de error, revertir la transacción
      $this->con->rollback();
      //echo "Error: " . $e->getMessage();
      $defaultRecord = [
        'Player_ID' => 'NO REGISTRADO',
      ];
      $register = array($defaultRecord);
      echo json_encode($register, JSON_UNESCAPED_UNICODE);
    }
  }

  // public function registerByDependencyCRUD()
  // {
  //   $option = $_POST['option'] ?? '';
  //   $id_dependency = $_SESSION['id_dependency'];

  //   date_default_timezone_set("America/Mexico_City");
  //   $actualDate = date("Y-m-d");
  //   $event = $this->eventModel->getByBetween($actualDate, 'start_date', 'end_date');

  //   $columns = [
  //     'Player.id' => 'Player_ID',
  //     'Player.acc_number' => 'Player_Account_Number',
  //     'Player.name_s' => 'Player_Name',
  //     'Player.father_last_name' => 'Player_Father_Last_Name',
  //     'Player.mother_last_name' => 'Player_Mother_Last_Name',
  //     'Player.birthday' => 'Player_Birthday',
  //     'Player.gender' => 'Player_Gender',
  //     'Player.phone_number' => 'Player_Phone_Number',
  //     'Player.email' => 'Player_Email',
  //     'Player.semester' => 'Player_Semester',
  //     'Player.group_num' => 'Player_Group_Number',
  //     'Player.photo' => 'Player_Photo',
  //     'Player.is_captain' => 'Player_Is_Captain',
  //     'Dependency.name' => 'Dependency_Name',
  //     'Dependency.category' => 'Dependency_Category',
  //     'Sport.name' => 'Sport_Name',
  //     'Sport.gender' => 'Sport_Gender',
  //     'Team.name' => 'Team_Name',
  //     'Team.record_date' => 'Record_Date',
  //   ];
  //   $joinTables = [
  //     'Team' => 'Player.id_team = Team.id',
  //     'Dependency_Sport' => 'Team.id_dependency_sport = Dependency_Sport.id',
  //     'Dependency' => 'Dependency_Sport.id_dependency = Dependency.id',
  //     'Sport' => 'Dependency_Sport.id_sport = Sport.id',
  //     'Event' => 'Team.id_event = Event.id',
  //   ];
  //   $conditionalsDependency = [
  //     'Event.id' => $event['id'],
  //     'Dependency.id' => $id_dependency,
  //   ];

  //   switch ($option) {
  //     case 4:
  //       $registers = $this->playerModel->getByJOINS(false, $columns, $joinTables, $conditionalsDependency, 'Team.id');;
  //       break;
  //   }

  //   foreach ($registers as &$register) {
  //     $register['Player_Birthday'] = $this->calculateAge($register['Player_Birthday']);
  //     $register['Record_Date'] = strtr($register['Record_Date'], $this->spanishMonthNames);
  //   }

  //   echo json_encode($registers, JSON_UNESCAPED_UNICODE);
  // }
}