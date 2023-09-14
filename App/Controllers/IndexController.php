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
    $events = $this->eventModel->getEventByIns();

    if($events > 0) {
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