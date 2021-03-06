<?php

require_once ROOT . '/app/controllers/Controller.php';
require_once ROOT . '/app/models/Services.php';

class ServicesController extends Controller
{
    public function __construct()
    {
        $this->model = new Services();
    }

    public function actionIndex()
    {
        $pageTitle = 'Services';
        $categoriesList = $this->model->getCategoriesList();
        $measurementUnits = $this->model->getMeasurementUnits();
        $pageContent = ROOT . '/app/views/services.php';
        $modals = [
            [
                'modalTitle' => 'New Service',
                'modalId' => 'modalService',
                'modalContent'=> ROOT . '/app/views/modals/service.php',
            ],
            [
                'modalTitle' => 'New Category',
                'modalId' => 'modalCategory',
                'modalContent' => ROOT . '/app/views/modals/serviceCategory.php',
            ],
        ];
        include_once ROOT . '/app/views/template.php';
    }

    public function actionGetServicesList()
    {
        $result = $this->model->getServicesList();
        echo json_encode($result);
    }

    public function actionGetCategoriesList()
    {
        $result = $this->model->getCategoriesList();
        echo json_encode($result);
    }


    public function actionAddCategory()
    {
        $inputName = $_POST['categoryName'];
        $categoryName = filter_var($inputName, FILTER_SANITIZE_STRING);
        $result = $this->model->addCategory($categoryName);
        echo $result;
    }

    public function actionUpdateCategory()
    {
        $inputData = [
            'categoryId' => $_POST['categoryId'],
            'categoryName' => $_POST['categoryName'],
        ];
        $categoryData = filter_var_array($inputData, FILTER_SANITIZE_STRING);
        $result = $this->model->updateCategory($categoryData);
        echo $result;
    }

    public function actionAddService()
    {
        $inputData = [
            'serviceName' => $_POST['serviceName'],
            'categoryId' => $_POST['categoryId'],
            'serviceCost' => $_POST['serviceCost'],
            'measurementUnitId' => $_POST['measurementUnitId'],
            'serviceDuration' => $_POST['serviceDuration'],
        ];
        $serviceData = filter_var_array($inputData, FILTER_SANITIZE_STRING);
        $result = $this->model->addService($serviceData);
        echo $result;
    }

    public function actionUpdateService()
    {
        $inputData = [
            'serviceId' => $_POST['serviceId'],
            'serviceName' => $_POST['serviceName'],
            'categoryId' => $_POST['categoryId'],
            'serviceCost' => $_POST['serviceCost'],
            'measurementUnitId' => $_POST['measurementUnitId'],
            'serviceDuration' => $_POST['serviceDuration'],
        ];
        $serviceData = filter_var_array($inputData, FILTER_SANITIZE_STRING);
        $this->model->correctServiceDurationIfNeeded($serviceData);
        $result = $this->model->updateService($serviceData);
        echo $result;
    }

    public function actionOpenService()
    {
        $serviceId = $_POST['serviceId'];
        $result = $this->model->getServiceById($serviceId);
        echo json_encode($result);
    }

    public function actionOpenCategory()
    {
        $categoryId = $_POST['categoryId'];
        $result = $this->model->getCategoryById($categoryId);
        echo json_encode($result);
    }

    public function actionDeleteService()
    {
        $serviceId = $_POST['serviceId'];
        $result = $this->model->deleteService($serviceId);
        echo $result;
    }

    public function actionRestoreService()
    {
        $serviceId = $_POST['serviceId'];
        $result = $this->model->restoreService($serviceId);
        echo $result;
    }

    public function actionDeleteCategory()
    {
        $categoryId = $_POST['categoryId'];
        $result = $this->model->deleteCategory($categoryId);
        echo $result;
    }

    public function actionRestoreCategory()
    {
        $categoryId = $_POST['categoryId'];
        $result = $this->model->restoreCategory($categoryId);
        echo $result;
    }
}