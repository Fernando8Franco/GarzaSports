<?php

require_once(__DIR__ . '/../models/Employee.php');

class EmployeesController extends Controller {
    
    private $employeeModel;

    public function __construct(PDO $con)
    {
        parent::__construct($con);
        $this->employeeModel = new Employee($con);
    }

    public function index() {
        if ($_SESSION['role_emp'] == 'Administrador') {
            $this->renderView('employees');
        }
    }

    public function employeesCRUD()
    {
        $data = [
            'id' => $_POST['id'] ?? '',
            'no_employee' => $_POST['no_employee'] ?? '',
            'id_dependency' => $_POST['id_dependency'] ?? '',
            'name_s' => $_POST['name_s'] ?? '',
            'father_last_name' => $_POST['father_last_name'] ?? '',
            'mother_last_name' => $_POST['mother_last_name'] ?? '',
            'role_emp' => $_POST['role_emp'] ?? '',
            'is_active' => $_POST['is_active'] ?? ''
        ];

        $option = $_POST['option'] ?? '';

        switch ($option) {
            case 1:
                $this->employeeModel->insert($data);
                $employees = $this->employeeModel->getAllEmployees();
                break;
            case 2:
                $this->employeeModel->updateById($data['id'], $data);
                $employees = $this->employeeModel->getAll();
                break;
            case 3:
                $this->employeeModel->deleteById($data['id']);
                $employees = $this->employeeModel->getAll();
                break;
            case 4:
                $employees = $this->employeeModel->getAllEmployees();
                break;
        }
        
        echo json_encode($employees, JSON_UNESCAPED_UNICODE);
    }
}