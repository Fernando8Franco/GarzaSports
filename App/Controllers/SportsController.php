<?php

require_once(__DIR__ . '/../models/sport.php');

class SportsController extends Controller {
    
    private $sportModel;

    public function __construct(PDO $con)
    {
        parent::__construct($con);
        $this->sportModel = new Sport($con);
    }

    public function index() {
        if ($_SESSION['role_emp'] == 'Administrador') {
            $this->renderView('sports');
        }
    }

    public function sportsCRUD()
    {
        $data = [
            'id' => $_POST['id'] ?? '',
            'name' => $_POST['name'] ?? '',
            'type' => $_POST['type'] ?? '',
            'gender' => $_POST['gender'] ?? '',
            'num_players' => $_POST['num_players'] ?? '',
            'num_extraplayers' => $_POST['num_extraplayers'] ?? '',
            'has_captain' => $_POST['has_captain'] ?? ''
        ];

        $option = $_POST['option'] ?? '';

        switch ($option) {
            case 1:
                $this->sportModel->insert($data);
                $sports = $this->sportModel->getAll();
                break;
            case 2:
                $this->sportModel->updateById($data['id'], $data);
                $sports = $this->sportModel->getAll();
                break;
            case 3:
                $this->sportModel->deleteByIdDependency_Sport($data['id']);
                $sports = $this->sportModel->getAll();
                break;
            case 4:
                $sports = $this->sportModel->getAll();
                break;
        }
        
        echo json_encode($sports, JSON_UNESCAPED_UNICODE);
    }
}