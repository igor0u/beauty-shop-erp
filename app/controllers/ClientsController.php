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
        $pageContent = ROOT . '/app/views/clients.php';
        $modals = [
            [
                'modalTitle' => 'New Client',
                'modalId' => 'modalNewClient',
                'modalContent'=> ROOT . '/app/views/modals/newClient.php',
            ],
            [
                'modalTitle' => '',
                'modalId' => 'modalClient',
                'modalContent' => ROOT . '/app/views/modals/client.php',
            ],
        ];
        include_once ROOT . '/app/views/template.php';
    }

    public function actionGetClientsList()
    {
        $result = $this->model->getClientsList();
        echo json_encode($result);
    }

    public function actionAddClient()
    {
        $inputData = [
            'surname' => $_POST['surname'],
            'name' => $_POST['name'],
            'patronymic' => $_POST['patronymic'],
            'phone' => $_POST['phone'],
            'dateOfBirth' => $_POST['dateOfBirth'],
        ];
        $clientData = filter_var_array($inputData, FILTER_SANITIZE_STRING);
        $this->model->addClient($clientData);
        echo '1';
    }

    public function actionUpdateClient()
    {
        $inputData = [
            'clientId' => $_POST['clientId'],
            'surname' => $_POST['surname'],
            'name' => $_POST['name'],
            'patronymic' => $_POST['patronymic'],
            'phone' => $_POST['phone'],
            'dateOfBirth' => $_POST['dateOfBirth'],
        ];
        $clientData = filter_var_array($inputData, FILTER_SANITIZE_STRING);
        $result = $this->model->updateClient($clientData);
        echo $result;
    }

    public function actionOpenClient()
    {
        $clientId = $_POST['clientId'];
        $result = $this->model->getClientById($clientId);
        echo json_encode($result);
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