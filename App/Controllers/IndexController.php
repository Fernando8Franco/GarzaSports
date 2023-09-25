<?php

require_once(__DIR__ . '/../models/event.php');

class IndexController extends Controller
{
  private $eventModel;
  public function __construct(PDO $con)
  {
    parent::__construct($con);
    $this->eventModel = new Event($con);
  }
  public function index()
  {
    $this->render('index', 'index');
  }

  public function registroUAEH()
  {
    date_default_timezone_set("America/Mexico_City");
    $actualDate = date("Y-m-d");
    $event = $this->eventModel->getByBetween($actualDate, 'ins_start_date', 'ins_end_date');

    if($event) {
      $this->render('intern', 'intern');
    } else {
      $this->render('404', 'empty');
    }
  }

  public function search()
  {
    $this->render('search', 'search');
  }

  public function print()
  {
    $this->render('print', 'print');
  }
}