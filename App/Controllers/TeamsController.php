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
}