<?php

class IndexController extends Controller {

    public function __construct(PDO $con) {
        parent::__construct($con);
    }
    public function index() {
        $this->render('index', 'index');
    }

    public function registroUAEH() {
        $this->render('intern', 'index');
    }
}