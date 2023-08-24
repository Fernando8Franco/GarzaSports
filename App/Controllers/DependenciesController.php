<?php

require_once(__DIR__.'/../models/dependency.php');

class DependenciesController extends Controller
{

    private $dependencyModel;

    public function __construct(PDO $con)
    {
        parent::__construct($con);
        $this->dependencyModel = new Dependency($con);
    }

    public function index()
    {
        $this->renderView('dependencies');
    }

    public function dependenciesCRUD()
    {
        $data = [
            'id' => $_POST['id'] ?? '',
            'name' => $_POST['name'] ?? '',
            'category' => $_POST['category'] ?? ''
        ];

        $option = $_POST['option'] ?? '';

        switch ($option) {
            case 1:
                $this->dependencyModel->insert($data);
                $dependencies = $this->dependencyModel->getAll();
                break;
            case 2:
                $this->dependencyModel->updateById($data['id'], $data);
                $dependencies = $this->dependencyModel->getAll();
                break;
            case 3:
                $this->dependencyModel->deleteById($data['id']);
                $dependencies = $this->dependencyModel->getAll();
                break;
            case 4:
                $dependencies = $this->dependencyModel->getAll();
                break;
        }

        echo json_encode($dependencies, JSON_UNESCAPED_UNICODE);
    }

    public function getDependencies()
    {
        $dependencies = $this->dependencyModel->getDependencies();
        $options = '<option value="" disabled selected>Seleccionar una Dependencia</option>';
        foreach ($dependencies as $dependency) {
            $options .= "<option value='" . $dependency['id'] . "'>" . $dependency['name'] . "</option>";
        }

        echo $options;
    }
}