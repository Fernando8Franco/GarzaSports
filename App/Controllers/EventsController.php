<?php

require_once(__DIR__.'/../models/event.php');

class EventsController extends Controller {
    private $eventModel;

    public function __construct(PDO $con) {
        $this->eventModel = new Event($con);
    }

    public function index() {
        // require_once(__DIR__.'/../Views/login.view.php');
        $this->render('events', [], 'session');
    }

    public function tables() {
        $events = $this->eventModel->getAllDates();

        echo json_encode($events);
    }
}