<?php

class UsersController extends Controller {
    
    private $userModel;

    public function __construct(PDO $con)
    {
        $this->userModel = new Event($con);
    }

    public function index() {
        $this->render('users', [], 'session');
    }
}