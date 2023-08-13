<?php

class AdminController extends Controller {
    
    public function index() {
        $this->render('login', [], 'index');
    }

    public function dashboard() {
        $this->render('dashboard', [], 'dashboard');
    }

    public function start() {
        $this->render('start', [], 'session');
    }
}