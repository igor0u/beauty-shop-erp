<?php

require_once ROOT . '/app/controllers/Controller.php';
require_once ROOT . '/app/models/Model.php';

class Visits extends Model
{
    public function getVisitsList()
    {
        $query = "SELECT v.visit_id AS visitId, v.visit_date AS visitDate, c.surname, c.name, c.patronymic, 
          IFNULL((SELECT os.total FROM ordered_services os WHERE os.visit_id = v.visit_id GROUP BY os.visit_id), 0.00) AS visitTotalCost
          FROM visits v
          LEFT JOIN clients c ON v.client_id = c.client_id";
        $this->db->query($query);
        $result = $this->db->resultSet();
        foreach ($result as &$visit) {
            $visit['clientName'] = $this->concatFullName($visit['surname'], $visit['name'], $visit['patronymic']);
        }
        return $result;
    }

    public function concatFullName($surname, $name, $patronymic)
    {
        $result = trim(trim($surname . ' ' . $name) . ' ' . $patronymic);
        return $result;
    }
}