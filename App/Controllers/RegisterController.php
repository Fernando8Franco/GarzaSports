<?php

require_once(__DIR__ . '/../models/Team.php');

class RegisterController extends Controller
{
  private $con;
  private $teamModel;
  private $spanishMonthNames = [
    'Jan' => 'Ene',
    'Feb' => 'Feb',
    'Mar' => 'Mar',
    'Apr' => 'Abr',
    'May' => 'May',
    'Jun' => 'Jun',
    'Jul' => 'Jul',
    'Aug' => 'Ago',
    'Sep' => 'Sep',
    'Oct' => 'Oct',
    'Nov' => 'Nov',
    'Dec' => 'Dic'
  ];

  public function __construct(PDO $con)
  {
    parent::__construct($con);
    $this->con = $con;
    $this->teamModel = new Team($con);
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

    switch ($option) {
      case 4:
        if($id_dependency == "-1") {
          $registers = $this->teamModel->getRegister();
        } else {
          $registers = $this->teamModel->getRegisterByDependency($id_dependency);
        }
        break;
    }

    foreach ($registers as &$register) {
      $register['Player_Birthday'] = $this->calculateAge($register['Player_Birthday']);
      $register['Record_Date'] = strtr($register['Record_Date'], $this->spanishMonthNames);
    }

    echo json_encode($registers, JSON_UNESCAPED_UNICODE);
  }

  public function registerByDependencyCRUD()
  {
    $option = $_POST['option'] ?? '';
    $id_dependency = $_POST['id_dependency'] ?? '';

    switch ($option) {
      case 4:
        $registers = $this->teamModel->getRegisterByDependency($id_dependency);
        break;
    }

    foreach ($registers as &$register) {
      $register['Player_Birthday'] = $this->calculateAge($register['Player_Birthday']);
      $register['Record_Date'] = strtr($register['Record_Date'], $this->spanishMonthNames);
    }

    echo json_encode($registers, JSON_UNESCAPED_UNICODE);
  }

  public function registersData() {
    $registerData = $this->teamModel->getCount('team', 'player');
    if($registerData){
      echo json_encode($registerData, JSON_UNESCAPED_UNICODE);
    } else {
      $defaultRecord = [
        'rows_team' => '0',
        'rows_player' => '0',
      ];
      echo json_encode($defaultRecord, JSON_UNESCAPED_UNICODE);
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

        if (!empty($acc_number) && !empty($name) && !empty($father_last_name) && !empty($mother_last_name) && !empty($birthday) && !empty($gender) && !empty($phone_number) && !empty($email) && !empty($semester) && !empty($group_num) && !empty($photo)) {
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
        'Player_Account_Number' => 'NO REGISTRADO',
        'Player_Name' => 'NO REGISTRADO',
        'Player_Father_Last_Name' => 'NO REGISTRADO',
        'Player_Mother_Last_Name' => 'NO REGISTRADO',
        'Player_Birthday' => 'NO REGISTRADO',
        'Player_Gender' => 'NO REGISTRADO',
        'Player_Phone_Number' => 'NO REGISTRADO',
        'Player_Email' => 'NO REGISTRADO',
        'Player_Semester' => 'NO REGISTRADO',
        'Player_Group_Number' => 'NO REGISTRADO',
        'Player_Photo' => 'NO REGISTRADO',
        'Player_Is_Captain' => 'NO REGISTRADO',
        'Dependency_Name' => 'NO REGISTRADO',
        'Sport_Name' => 'NO REGISTRADO',
        'Sport_Gender' => 'NO REGISTRADO',
        'Team_Name' => 'NO REGISTRADO',
        'Record_Date' => 'NO REGISTRADO',
      ];
      $register = array($defaultRecord);
      echo json_encode($register, JSON_UNESCAPED_UNICODE);
    }
  }
  private function calculateAge($birthday)
  {
    $currentDate = new DateTime('now', new DateTimeZone('America/Mexico_City'));
    $birthdate = new DateTime($birthday, new DateTimeZone('America/Mexico_City'));
    $ageInterval = $birthdate->diff($currentDate);
    return $ageInterval->y;
  }
}