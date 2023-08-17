<?php

require_once(__DIR__ . '/../models/event.php');

class EventsController extends Controller
{
    private $eventModel;

    public function __construct(PDO $con)
    {
        $this->eventModel = new Event($con);
    }

    public function index()
    {
        // require_once(__DIR__.'/../Views/login.view.php');
        $this->render('events', [], 'session');
    }

    public function eventsCRUD()
    {
        header('Content-Type: application/json');
        $name = (isset($_POST['name'])) ? $_POST['name'] : '';
        $start_event = (isset($_POST['start_event'])) ? $_POST['start_event'] : '';
        $end_event = (isset($_POST['end_event'])) ? $_POST['end_event'] : '';
        $ins_start_event = (isset($_POST['ins_start_event'])) ? $_POST['ins_start_event'] : '';
        $ins_end_event = (isset($_POST['ins_end_event'])) ? $_POST['ins_end_event'] : '';

        $option = (isset($_POST['option'])) ? $_POST['option'] : '';
        $idEvent = (isset($_POST['idEvent'])) ? $_POST['idEvent'] : '';

        switch ($option) {
            case 1:
                $this->eventModel->insert($name, $start_event, $end_event, $ins_start_event, $ins_end_event);
                $events = $this->eventModel->getAllDates();
                break;
            case 4:
                $events = $this->eventModel->getAllDates();
                break;
        }

        echo json_encode($events, JSON_UNESCAPED_UNICODE);
    }
}