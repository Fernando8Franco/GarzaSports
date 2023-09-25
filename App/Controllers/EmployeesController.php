<?php

require_once(__DIR__ . '/../models/Employee.php');

class EmployeesController extends Controller
{

  private $employeeModel;

  public function __construct(PDO $con)
  {
    parent::__construct($con);
    $this->employeeModel = new Employee($con);
  }

  public function index()
  {
    !empty($_SESSION['role_emp']) && $_SESSION['role_emp'] == 'Administrador' ? $this->renderView('employees') : $this->render('404', 'empty');
  }

  public function employeesCRUD()
  {
    $columns = [
      'Employee.id' => 'id',
      'Employee.no_employee' => 'no_employee',
      'Employee.name_s' => 'name_s',
      'Employee.father_last_name' => 'father_last_name',
      'Employee.mother_last_name' => 'mother_last_name',
      'Employee.role_emp' => 'role_emp',
      'Employee.id_dependency' => 'id_dependency',
      'Employee.is_active' => 'is_active',
      'Dependency.name' => 'dependency_name',
    ];
    $joinTables = [
      'Dependency' => 'Employee.id_dependency = Dependency.id',
    ];

    $password = $_POST['password'] ?? '';

    if (!empty($password)) {
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    } else {
      $hashedPassword = '';
    }

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

    if (!empty($hashedPassword)) {
      $data['password'] = $hashedPassword;
    }

    $option = $_POST['option'] ?? '';

    if (!empty($_SESSION['role_emp']) && $_SESSION['role_emp'] == 'Administrador') {
      switch ($option) {
        case 1:
          $this->employeeModel->insert($data);
          $employees = $this->employeeModel->getByJOINS(false, $columns, $joinTables, [], 'id');
          break;
        case 2:
          $this->employeeModel->updateById($data['id'], $data);
          $employees = $this->employeeModel->getByJOINS(false, $columns, $joinTables, [], 'id');
          break;
        case 3:
          $this->employeeModel->deleteById($data['id']);
          $employees = $this->employeeModel->getByJOINS(false, $columns, $joinTables, [], 'id');
          break;
        case 4:
          $employees = $this->employeeModel->getByJOINS(false, $columns, $joinTables, [], 'id');
          break;
      }
    }

    foreach ($employees as &$employee) {
      if ($employee['is_active']) {
        $employee['is_active'] = '<i class="fa-solid fa-check"></i>';
      } else {
        $employee['is_active'] = '<i class="fa-solid fa-xmark"></i>';
      }
    }
    echo json_encode($employees, JSON_UNESCAPED_UNICODE);
  }

  public function test()
  {
    $columns = [
      'Employee.id' => 'id',
      'Employee.no_employee' => 'no_employee',
      'Employee.name_s' => 'name_s',
      'Employee.father_last_name' => 'father_last_name',
      'Employee.mother_last_name' => 'mother_last_name',
      'Employee.role_emp' => 'role_emp',
      'Employee.id_dependency' => 'id_dependency',
      'Employee.is_active' => 'is_active',
      'Dependency.name' => 'dependency_name',
    ];
    $joinTables = [
      'Dependency' => 'Employee.id_dependency = Dependency.id',
    ];
    $employees = $this->employeeModel->getByJOINS(false, $columns, $joinTables, [], 'id');
    echo $employees;
  }
}