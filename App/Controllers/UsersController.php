<?php

class UsersController extends Controller {
    
    public function index() {
        $this->render('users', [], 'session');
    }
}