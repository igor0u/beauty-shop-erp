<?php

require_once ROOT . '/app/controllers/Controller.php';
require_once ROOT . '/app/models/Schedule.php';

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->model = new Schedule();
    }

    public function actionIndex()
    {
        $pageTitle = 'Schedule';
        $pageContent = ROOT . '/app/views/schedule.php';
        include_once ROOT . '/app/views/template.php';
    }

    public function actionGetStaffList()
    {
        $result = $this->model->getStaffList();
        echo json_encode($result);
    }

    public function actionAddVisit()
    {
        $data = json_decode($_POST['visit'], true);
        $clientId = $data['common']['clientId'];
        if ($clientId == '' || !$this->model->clientExists($clientId)) {
            $clientData = [
                'surname' => $data['common']['surname'],
                'name' => $data['common']['name'],
                'patronymic' => $data['common']['patronymic'],
                'phoneNumber' => $data['common']['phoneNumber'],
            ];
            $this->model->addClient($clientData);
            $clientId = $this->model->lastInsertId();
        }
        $preparedServices = $this->model->markNextConsecutiveOrders($data['orderedServices']);
        $visitId = $this->model->addVisit($clientId, $data['common']['visitDate']);
        $this->model->addAllOrderedServicesToVisit($visitId, $preparedServices);

        echo "1";
    }

    public function actionFindClientsForLiveSearch()
    {
        $query = $_POST['query'];
        $result = $this->model->getClientsForLiveSearch($query);
        echo $result;
    }

    public function actionFindStaffForLiveSearch()
    {
        $query = $_POST['query'];
        $result = $this->model->getStaffForLiveSearch($query);
        echo json_encode($result);
    }

    public function actionFindServicesForLiveSearch()
    {
        $query = $_POST['query'];
        $result = $this->model->getServicesForLiveSearch($query);
        echo json_encode($result);
    }

    public function actionFindVisitsByDate()
    {
        $dateTime = $_POST['start'];
        $date = explode('T', $dateTime)[0];
        $services = $this->model->getOrderedServicesByDate($date);
        $result = $this->model->prepareVisits($services);
        echo json_encode($result);
    }

    public function actionEditVisit()
    {
        $visitId = $_POST['visitId'];
        $visitInfo = $this->model->getVisitInfoById($visitId);
        $services = $this->model->getOrderedServicesByVisitId($visitId);
        $result = [
            'visitInfo' => $visitInfo,
            'services' => $services,
        ];
        echo json_encode($result);
    }

    public function actionUpdateVisit()
    {
        $newServiceData = json_decode($_POST['visit'], true);
        $visitId = $newServiceData['common']['visitId'];
        $newVisitDate = $newServiceData['common']['visitDate'];
        $visitInfo = $this->model->getVisitInfoById($visitId);
        $services = $this->model->getOrderedServicesByVisitId($visitId);

        if ($newVisitDate != $visitInfo['visitDate']) {
            $this->model->updateVisitDate($visitId, $newVisitDate);
        }
        $servicesToDeleteId = $this->model->getMissingServicesId($services, $newServiceData['orderedServices']);
        if (count($servicesToDeleteId) > 0) {
            $this->model->deleteOrderedServices($servicesToDeleteId);
        }
        $preparedNewServiceData = $this->model->markNextConsecutiveOrders($newServiceData['orderedServices']);
        $servicesToAdd = $this->model->filterNewServices($preparedNewServiceData);
        if (count($servicesToAdd) > 0) {
            $this->model->addAllOrderedServicesToVisit($visitId, $servicesToAdd);
        }
        $servicesToUpdate = $this->model->filterEditedServices($preparedNewServiceData);
        if (count($servicesToUpdate) > 0) {
            $this->model->updateOrderedServices($servicesToUpdate);
        }
        echo '1';
    }

    public function actionDeleteVisit()
    {
        $visitId = $_POST['visitId'];
        $services = $this->model->getOrderedServicesByVisitId($visitId);
        $servicesToDeleteId = $this->model->getMissingServicesId($services);
        $this->model->deleteOrderedServices($servicesToDeleteId);
        $this->model->deleteVisit($visitId);
        echo '1';

    }

}