<?php

require_once ROOT . '/app/controllers/Controller.php';
require_once ROOT . '/app/models/Visits.php';

class VisitsController extends Controller
{
    public function __construct()
    {
        $this->model = new Visits();
    }

    public function actionIndex()
    {
        $pageTitle = 'Visits';
        $pageContent = ROOT . '/app/views/visits.php';
        $modals = [
            [
                'modalTitle' => '',
                'modalId' => 'modalVisit',
                'modalContent'=> ROOT . '/app/views/modals/visit.php',
            ],
        ];
        include_once ROOT . '/app/views/template.php';
    }

    public function actionGetVisitsList()
    {
        $result = $this->model->getVisitsList();
        echo json_encode($result);
    }
}