<?php

require_once(__DIR__ . '/../models/dependency.php');
require_once(__DIR__ . '/../models/dependencysport.php');

class DependenciesController extends Controller
{

    private $dependencyModel;
    private $dependencySportModel;

    public function __construct(PDO $con)
    {
        parent::__construct($con);
        $this->dependencyModel = new Dependency($con);
        $this->dependencySportModel = new DependencySport($con);
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
        $columns = [
            'id' => 'id',
            'name' => 'name',
            'category' => 'category'
        ];
        $dependencies = $this->dependencyModel->getByJOINS(false, $columns, [], [], 'name');
        $options = '<option value="" disabled selected>Seleccionar...</option>';
        foreach ($dependencies as $dependency) {
            $options .= "<option value='" . $dependency['id'] . "'>" . $dependency['name'] . "</option>";
        }

        echo $options;
    }

    public function getDependenciesWithAll()
    {
        $columns = [
            'id' => 'id',
            'name' => 'name',
            'category' => 'category'
        ];
        $dependencies = $this->dependencyModel->getByJOINS(false, $columns, [], [], 'name');
        $options = '<option value="" disabled selected>Seleccionar...</option>';
        $options .= '<option value="-1">Todas</option>';
        foreach ($dependencies as $dependency) {
            $options .= "<option value='" . $dependency['id'] . "'>" . $dependency['name'] . " - " . $dependency['category'] . "</option>";
        }

        echo $options;
    }

    public function getCategories()
    {
        $categories = $this->dependencyModel->getDistinct('category', 'category');
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

        $columns = [
            'id' => 'id',
            'name' => 'name',
        ];
        $conditionals = [
            'category' => $data['category'] ?? '',
        ];
        

        $dependencies = $this->dependencyModel->getByJOINS(false, $columns, [], $conditionals, 'name');
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

        $columns = [
            'Sport.gender' => 'gender',
        ];
        $joinTables = [
            'Dependency' => 'Dependency_Sport.id_dependency = Dependency.id',
            'Sport' => 'Dependency_Sport.id_sport = Sport.id',
        ];
        $conditionals = [
            'Dependency.id' => $data['dependency'] ?? '',
            'Dependency_Sport.is_active' => 1,
        ];

        $branches = $this->dependencySportModel->getByJOINS(true, $columns, $joinTables, $conditionals, 'Sport.gender');
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

        $columns = [
            'Sport.id' => 'id',
            'Sport.name' => 'name',
        ];
        $joinTables = [
            'Dependency' => 'Dependency_Sport.id_dependency = Dependency.id',
            'Sport' => 'Dependency_Sport.id_sport = Sport.id',
        ];
        $conditionals = [
            'Dependency.id' => $data['dependency'] ?? '',
            'Sport.gender' => $data['gender'] ?? '',
            'Dependency_Sport.is_active' => 1,
        ];

        $sports = $this->dependencySportModel->getByJOINS(false, $columns, $joinTables, $conditionals, 'name');
        $options = '<option value="" disabled selected>Seleccionar...</option>';
        foreach ($sports as $sport) {
            $options .= "<option value='" . $sport['id'] . "'>" . $sport['name'] . "</option>";
        }

        echo $options;
    }
}