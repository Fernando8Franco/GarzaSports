<?php

class EventsController extends Controller {
    public function index() {
        // require_once(__DIR__.'/../Views/login.view.php');
        $this->render('events', [], 'session');
    }

}