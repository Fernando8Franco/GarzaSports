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
        $data = [
            'id' => $_POST['idEvent'] ?? '',
            'name' => $_POST['name'] ?? '',
            'start_date' => $_POST['start_event'] ?? '',
            'end_date' => $_POST['end_event'] ?? '',
            'ins_start_date' => $_POST['ins_start_event'] ?? '',
            'ins_end_date' => $_POST['ins_end_event'] ?? ''
        ];

        $option = $_POST['option'] ?? '';

        switch ($option) {
            case 1:
                $this->eventModel->insert($data);
                $events = $this->eventModel->getAllDates();
                break;
            case 2:
                $this->eventModel->updateById($data['id'], $data);
                $events = $this->eventModel->getAllDates();
                break;
            case 3:
                $this->eventModel->deleteById($data['id']);
                $events = $this->eventModel->getAllDates();
                break;
            case 4:
                $events = $this->eventModel->getAllDates();
                break;
        }

        $spanishMonthNames = array(
            'Jan' => 'Ene',
            'Feb' => 'Feb',
            'Mar' => 'Mar',
            'Apr' => 'Abr',
            'May' => 'May',
            'Jun' => 'Jun',
            'Jul' => 'Jul',
            'Aug' => 'Ago',
            'Sep' => 'Sep',
            'Oct' => 'Oct',
            'Nov' => 'Nov',
            'Dec' => 'Dic'
        );

        foreach ($events as &$event) {
            $event['start_date'] = strtr($event['start_date'], $spanishMonthNames);
            $event['end_date'] = strtr($event['end_date'], $spanishMonthNames);
            $event['ins_start_date'] = strtr($event['ins_start_date'], $spanishMonthNames);
            $event['ins_end_date'] = strtr($event['ins_end_date'], $spanishMonthNames);
        }

        echo json_encode($events, JSON_UNESCAPED_UNICODE);
    }
}