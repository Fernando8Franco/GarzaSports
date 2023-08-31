<?php

require_once(__DIR__ . '/../models/dependency.php');

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
                $this->dependencyModel->deleteByIdDependency_Sport($data['id']);
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

    public function getCategories()
    {
        $categories = $this->dependencyModel->getCategories();
        $options = '<option value="" disabled selected>Seleccionar...</option>';
        foreach ($categories as $category) {
            $options .= "<option value='" . $category['category'] . "'>" . $category['category'] . "</option>";
        }

        echo $options;
    }

    public function getDependenciesByCategory()
    {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);
        $category = $data['category'] ?? '';
        
        $dependencies = $this->dependencyModel->getDependenciesByCategory($category);
        $options = '<option value="" disabled selected>Seleccionar...</option>';
        foreach ($dependencies as $depenency) {
            $options .= "<option value='" . $depenency['id'] . "'>" . $depenency['name'] . "</option>";
        }

        echo $options;
    }

    public function getbranches()
    {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);
        $dependency = $data['dependency'] ?? '';
        
        $branches = $this->dependencyModel->getbranches($dependency);
        $options = '<option value="" disabled selected>Seleccionar...</option>';
        foreach ($branches as $branch) {
            $options .= "<option value='" . $branch['gender'] . "'>" . $branch['gender'] . "</option>";
        }

        echo $options;
    }

    public function getSportsByDependency()
    {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);
        $dependency = $data['dependency'] ?? '';
        $gender = $data['gender'] ?? '';
        
        $sports = $this->dependencyModel->getSportsByDependency($dependency, $gender);
        $options = '<option value="" disabled selected>Seleccionar...</option>';
        foreach ($sports as $sport) {
            $options .= "<option value='" . $sport['id'] . "'>" . $sport['name'] . "</option>";
        }

        echo $options;
    }
}