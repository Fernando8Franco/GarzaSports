<?php

require_once(__DIR__ . '/../models/team.php');
require_once(__DIR__ . '/../models/event.php');

class TeamsController extends Controller
{
  private $teamModel;
  private $eventModel;

  public function __construct(PDO $con)
  {
    parent::__construct($con);
    $this->teamModel = new Team($con);
    $this->eventModel = new Event($con);
  }

  public function index()
  {
    !empty($_SESSION['role_emp']) && $_SESSION['role_emp'] == 'Administrador' ? $this->renderView('teams') : $this->render('404', 'empty');
  }

  public function teamsCRUD()
  {
    $data = [
      'id' => $_POST['id'] ?? '',
      'name' => $_POST['name'] ?? '',
    ];
    $option = $_POST['option'] ?? '';

    date_default_timezone_set("America/Mexico_City");
    $actualDate = date("Y-m-d");
    $event = $this->eventModel->getByBetween($actualDate, 'start_date', 'end_date');

    $columns = [
      'Team.id' => 'id',
      'Team.name' => 'name',
      'Team.record_date' => 'record_date',
      'Dependency.name' => 'dependency_name',
      'Sport.name' => 'sport_name',
      'Event.name' => 'event_name',
    ];
    $joinTables = [
      'Dependency_Sport' => 'Team.id_dependency_sport = Dependency_Sport.id',
      'Dependency' => 'Dependency_Sport.id_dependency = Dependency.id',
      'Sport' => 'Dependency_Sport.id_sport = Sport.id',
      'Event' => 'Team.id_event = Event.id',
    ];
    $conditionals = [
      'Event.id' => ($event !== false) ? $event['id'] : -1,
      'Dependency_Sport.is_active' => 1,
    ];

    if (!empty($_SESSION['role_emp']) && $_SESSION['role_emp'] == 'Administrador') {
      switch ($option) {
        case 2:
          $this->teamModel->updateById($data['id'], $data);
          $teams = $this->teamModel->getByJOINS(false, $columns, $joinTables, $conditionals, 'id');
          break;
        case 3:
          $this->teamModel->deleteByIdTeam($data['id']);
          $teams = $this->teamModel->getByJOINS(false, $columns, $joinTables, $conditionals, 'id');
          break;
        case 4:
          $teams = $this->teamModel->getByJOINS(false, $columns, $joinTables, $conditionals, 'id');
          break;
      }
    }

    foreach ($teams as &$team) {
      $team['record_date'] = $this->formatDate($team['record_date']);
    }

    echo json_encode($teams, JSON_UNESCAPED_UNICODE);
  }
}