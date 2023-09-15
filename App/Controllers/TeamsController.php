<?php

require_once(__DIR__ . '/../models/team.php');

class TeamsController extends Controller
{
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
  private $teamModel;

  public function __construct(PDO $con)
  {
    parent::__construct($con);
    $this->teamModel = new Team($con);
  }

  public function index()
  {
    if ($_SESSION['role_emp'] == 'Administrador') {
      $this->renderView('teams');
    }
  }

  public function teamsCRUD()
  {
    $data = [
      'id' => $_POST['id'] ?? '',
      'name' => $_POST['name'] ?? '',
    ];

    $option = $_POST['option'] ?? '';

    switch ($option) {
      case 2:
        $this->teamModel->updateById($data['id'], $data);
        $teams = $this->teamModel->getAllTeamsByDate();
        break;
      case 3:
        $this->teamModel->deleteByIdTeam($data['id']);
        $teams = $this->teamModel->getAll();
        break;
      case 4:
        $teams = $this->teamModel->getAllTeamsByDate();
        break;
    }

    foreach ($teams as &$team) {
      $team['record_date'] = strtr($team['record_date'], $this->spanishMonthNames);
    }

    echo json_encode($teams, JSON_UNESCAPED_UNICODE);
  }

  public function getTeam()
  {
    $acc_number = $_POST['acc_number'] ?? '';
    $email = $_POST['email'] ?? '';
    $dependency = $_POST['dependency'] ?? '';
    $sport = $_POST['sport'] ?? '';

    $id_team = $this->teamModel->getTeamByAccEmailDependencySport($acc_number, $email, $dependency, $sport);

    if (!empty($id_team)) {
      $register = $this->teamModel->getRegisterByTeam($id_team['id']);
      echo json_encode($register, JSON_UNESCAPED_UNICODE);
    } else {
      $defaultRecord = array(
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
      );
      $register = array($defaultRecord);
      echo json_encode($register, JSON_UNESCAPED_UNICODE);
    }
  }
}