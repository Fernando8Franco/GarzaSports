<?php

class IndexController extends Controller {

    public function __construct(PDO $con) {
        parent::__construct($con);
    }
    public function index() {
        // require_once(__DIR__.'/../Views/login.view.php');
        $this->render('index', 'index');
    }

    public function listar() {
        echo 'estoy en listar';
    }

    public function modificar() {
        echo 'estoy en modificar';
    }

    public function nuevo() {
        echo 'estoy en nuevo';
    }
}