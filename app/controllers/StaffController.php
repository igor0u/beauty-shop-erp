<?php

require_once ROOT . '/app/controllers/Controller.php';
require_once ROOT . '/app/models/Staff.php';

class StaffController extends Controller
{

    public function __construct()
    {
        $this->model = new Staff();
    }

    public function actionIndex()
    {
        $pageTitle = 'Staff';
        $staffList = $this->model->getStaffList();
        $positionsList = $this->model->getPositionsList();
        $pageContent = ROOT . '/app/views/staff.php';
        $modals = [
            [
                'modalTitle' => 'New Employee',
                'modalId' => 'modalEmployee',
                'modalContent'=> ROOT . '/app/views/modals/employee.php',
            ],
            [
                'modalTitle' => 'New Position',
                'modalId' => 'modalPosition',
                'modalContent' => ROOT . '/app/views/modals/position.php',
            ],
        ];
        include_once ROOT . '/app/views/template.php';
    }

    public function actionOpenEmployee()
    {
        $employeeId = $_POST['employeeId'];
        $employeeData = $this->model->getEmployeeById($employeeId);
        echo json_encode($employeeData);
    }

    public function actionOpenPosition()
    {
        $positionId = $_POST['positionId'];
        $positionData = $this->model->getPositionById($positionId);
        echo json_encode($positionData);
    }

    public function actionUpdateEmployee()
    {
        if (!isset($_POST['employeeId'])) {
            return false;
        }
        $inputData = [
            'employeeId' => $_POST['employeeId'],
            'surname' => $search_html = $_POST['surname'],
            'name' => $_POST['name'],
            'patronymic' => $_POST['patronymic'],
            'position' => $_POST['position'],
            'dateOfBirth' => $_POST['dateOfBirth'],
            'email' => $_POST['email'],
            'phoneNumber' => $_POST['phoneNumber'],
        ];
        $employeeData = filter_var_array($inputData, FILTER_SANITIZE_STRING);
        $result = $this->model->updateEmployee($employeeData);
        echo $result;
    }

    public function actionAddEmployee()
    {
        $inputData = [
            'surname' => $_POST['surname'],
            'name' => $_POST['name'],
            'patronymic' => $_POST['patronymic'],
            'position' => $_POST['position'],
            'dateOfBirth' => $_POST['dateOfBirth'],
            'email' => $_POST['email'],
            'phoneNumber' => $_POST['phoneNumber'],
        ];
        $employeeData = filter_var_array($inputData, FILTER_SANITIZE_STRING);
        $result = $this->model->addEmployee($employeeData);
        echo $result;
    }

    public function actionAddPosition()
    {
        $inputName = $_POST['positionName'];
        $positionName = filter_var($inputName, FILTER_SANITIZE_STRING);
        $result = $this->model->addPosition($positionName);
        echo $result;
    }

    public function actionUpdatePosition()
    {
        if (!isset($_POST['positionId'])) {
            return false;
        }
        $inputData = [
            'positionId' => $_POST['positionId'],
            'positionName' => $_POST['positionName'],
        ];
        $positionData = filter_var_array($inputData, FILTER_SANITIZE_STRING);
        $result = $this->model->updatePosition($positionData);
        echo $result;
    }

    public function actionGetStaffList()
    {
        $staffList = $this->model->getStaffList();
        $preparedStaffList = $this->model->prepareStaffList($staffList);
        echo json_encode($preparedStaffList);
    }

    public function actionGetPositionsList()
    {
        $result = $this->model->getPositionsList();
        echo json_encode($result);
    }

    public function actionDeleteEmployee()
    {
        $employeeId = $_POST['employeeId'];
        $result = $this->model->deleteEmployee($employeeId);
        echo $result;
    }

    public function actionRestoreEmployee()
    {
        $employeeId = $_POST['employeeId'];
        $result = $this->model->restoreEmployee($employeeId);
        echo $result;
    }

    public function actionDeletePosition()
    {
        $positionId = $_POST['positionId'];
        $result = $this->model->deletePosition($positionId);
        echo $result;
    }

    public function actionRestorePosition()
    {
        $positionId = $_POST['positionId'];
        $result = $this->model->restorePosition($positionId);
        echo $result;
    }
}
