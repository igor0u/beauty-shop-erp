<?php

require_once ROOT . '/app/controllers/Controller.php';
require_once ROOT . '/app/models/Clients.php';

class ClientsController extends Controller
{
    public function __construct()
    {
        $this->model = new Clients();
    }

    public function actionIndex()
    {
        $pageTitle = 'Clients';
        //$staffList = $this->model->getStaffList();
        //$positionsList = $this->model->getPositionsList();
        $pageContent = ROOT . '/app/views/clients.php';
        include_once ROOT . '/app/views/template.php';
    }

    public function actionGetClientsList()
    {
        $result = $this->model->getClientsList();
        echo json_encode($result);
    }

    public function actionAddClient()
    {
        $clientData = [
            'surname' => $_POST['surname'],
            'name' => $_POST['name'],
            'patronymic' => $_POST['patronymic'],
            'phone' => $_POST['phone'],
            'dateOfBirth' => $_POST['dateOfBirth'],
        ];
        $this->model->addClient($clientData);
        echo '1';
    }

    public function actionOpenClient()
    {
        $clientId = $_POST['clientId'];
        $result = $this->model->getClientById($clientId);
        echo json_encode($result);
    }

    public function actionUpdateService()
    {
        $clientData = [
            'clientId' => $_POST['clientId'],
            'surname' => $_POST['surname'],
            'name' => $_POST['name'],
            'patronymic' => $_POST['patronymic'],
            'phone' => $_POST['phone'],
            'dateOfBirth' => $_POST['dateOfBirth'],
        ];
        $result = $this->model->updateClient($clientData);
        echo $result;
    }

    public function actionDeleteClient()
    {
        $clientId = $_POST['clientId'];
        $result = $this->model->deleteClient($clientId);
        echo $result;
    }

    public function actionRestoreClient()
    {
        $clientId = $_POST['clientId'];
        $result = $this->model->restoreClient($clientId);
        echo $result;
    }
}