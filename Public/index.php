<?php

require_once(__DIR__.'/../app/autoload.php');

$router = new Router();
$router->run();
    
    
    
    // $database = new Database();
    // $con = $database->getConnection();

    // $dependencyModel = new Dependency($con);
    // $dependencyModel->updateById($dependencyModel->getIdByName('Preparatoria no. 2')['id'], [
    //     'name' => 'Preparatoria no. 2',
    //     'category' => 'BACHILLERATO'
    // ]);
    // $dependencies = $dependencyModel->getAll('Preparatoria no. 2');

    // echo '<pre>';
    // var_dump($dependencies);
    // echo '</pre>';

    // $database->closeConnection();
?>