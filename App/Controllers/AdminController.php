<?php

class AdminController extends Controller
{
  private $con;
  public function __construct(PDO $con)
  {
    parent::__construct($con);
    $this->con = $con;
  }
  public function index()
  {
    $this->render('login', 'login');
  }

  public function dashboard()
  {
    $this->render('dashboard', 'dashboard');
  }

  public function start()
  {
    $this->renderView('start');
  }

  public function login()
  {
    if (session_status() === PHP_SESSION_ACTIVE) {
      session_unset();
    }

    $no_employee = $_POST['no_employee'] ?? '';
    $password = $_POST['password'] ?? '';

    $query = "SELECT E.name_s, E.role_emp, E.id_dependency, E.password, D.name AS name_dependency FROM dbo.Employee AS E 
                    INNER JOIN dbo.Dependency AS D ON E.id_dependency = D.id WHERE E.no_employee = :no_employee AND E.is_active = 1";
    $stm = $this->con->prepare($query);
    $stm->bindValue(":no_employee", $no_employee);
    $stm->execute();
    $result = $stm->fetch();

    if ($result !== false && password_verify($password, $result['password'])) {
      $_SESSION['name_s'] = $result['name_s'];
      $_SESSION['role_emp'] = $result['role_emp'];
      $_SESSION['id_dependency'] = $result['id_dependency'];
      $_SESSION['name_dependency'] = $result['name_dependency'];
      echo "Acceso consedido";
    } else {
      echo "Número de cuenta o contraseña incorrecta";
    }
  }

  public function logout()
  {
    if (session_status() === PHP_SESSION_ACTIVE) {
      session_unset();
      session_destroy();
      header("Location: " . URL_PATH . "/admin");
      exit();
    } else {
      exit();
    }
  }
}