<?php

class Controller
{
    protected function __construct(PDO $con)
    {
        session_start();
    }

    protected function render($path, $layout = '')
    {
        ob_start();
        require_once(__DIR__ . '/../views/' . $path . '.view.php');
        $content = ob_get_clean();

        require_once(__DIR__ . '/../views/layouts/' . $layout . '.layout.php');
    }

    protected function renderView($path)
    {
        require_once(__DIR__ . '/../views/' . $path . '.view.php');
    }

    private $spanishMonthNamesAbb = [
        1 => 'Ene',
        2 => 'Feb',
        3 => 'Mar',
        4 => 'Abr',
        5 => 'May',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Ago',
        9 => 'Sep',
        10 => 'Oct',
        11 => 'Nov',
        12 => 'Dic'
    ];

    protected function formatDate($inputDate)
    {
        $date = new DateTime($inputDate);

        $day = $date->format('d');
        $month = $date->format('n');
        $year = $date->format('Y');

        $formattedDate = $day . ' ' . $this->spanishMonthNamesAbb[$month] . ' ' . $year;

        return $formattedDate;
    }

    protected function calculateAge($birthday)
    {
        $currentDate = new DateTime('now', new DateTimeZone('America/Mexico_City'));
        $birthdate = new DateTime($birthday, new DateTimeZone('America/Mexico_City'));
        $ageInterval = $birthdate->diff($currentDate);
        return $ageInterval->y;
    }
}