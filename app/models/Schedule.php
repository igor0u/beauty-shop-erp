<?php

require_once ROOT . '/app/models/Model.php';
require_once ROOT . '/app/models/Database.php';

class Schedule extends Model
{
    public function getStaffList()
    {
        $query = "SELECT st.employee_id as employeeId, st.surname, st.name, st.patronymic, st.position_id, 
            p.position_name AS positionName 
            FROM staff st 
            LEFT JOIN positions p on st.position_id = p.position_id 
            WHERE st.is_deleted=0";
        $this->db->query($query);
        $source = $this->db->resultSet();
        $result = [];
        foreach ($source as $row) {
            $result[] = [
                'id' => $row['employeeId'],
                'title' => trim($row['surname'] . " " . $row['name'] . " " . $row['patronymic']),
                'position' => $row['positionName'],

            ];
        }
        return $result;
    }

    public function addClient(array $clientData)
    {
        $query = "INSERT INTO clients(surname, name, patronymic, phone) VALUES (:surname, :name, :patronymic, :phone)";
        $this->db->query($query);
        $this->db->bind(':surname', $clientData['surname'], PDO::PARAM_STR);
        $this->db->bind(':name', $clientData['name'], PDO::PARAM_STR);
        $this->db->bind(':patronymic', $clientData['patronymic'], PDO::PARAM_STR);
        $this->db->bind(':phone', $clientData['phoneNumber'], PDO::PARAM_STR);
        $this->db->execute();
    }

    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }

    public function clientExists($clientId)
    {
        $query = "SELECT * FROM clients WHERE client_id = (:clientId)";
        $this->db->query($query);
        $this->db->bind(':clientId', $clientId, PDO::PARAM_INT);
        $this->db->execute();
        if ($this->db->result()) {
            return true;
        }
        return false;
    }

    public function addVisit($clientId, $visitDate)
    {
        $query = "INSERT INTO visits(client_id, visit_date)  VALUES (:clientId, :visitDate)";
        $this->db->query($query);
        $this->db->bind(':clientId', $clientId, PDO::PARAM_INT);
        $this->db->bind(':visitDate', $visitDate, PDO::PARAM_STR);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function addAllOrderedServicesToVisit($visitId, $orderedServices)
    {
        foreach ($orderedServices as $serviceData) {
            $this->addOrderedServiceToVisit($visitId, $serviceData);
        }
        return true;
    }

    public function addOrderedServiceToVisit($visitId, $serviceData)
    {
        $query = "INSERT INTO ordered_services 
            (service_id, visit_id, start_time, end_time, quantity, cost, discount, total, employee_id, is_next_consecutive) 
            VALUES (:serviceId, :visitId, :startTime, :endTime, :quantity, :cost, :discount, :total, :employeeId, :isNextConsecutive)";
        $this->db->query($query);
        $this->db->bind(':visitId', $visitId, PDO::PARAM_INT);
        $this->db->bind(':serviceId', $serviceData['serviceId'], PDO::PARAM_INT);
        $this->db->bind(':startTime', $serviceData['startTime'], PDO::PARAM_STR);
        $this->db->bind(':endTime', $serviceData['endTime'], PDO::PARAM_STR);
        $this->db->bind(':quantity', $serviceData['quantity'], PDO::PARAM_INT);
        $this->db->bind(':cost', $serviceData['cost'], PDO::PARAM_STR);
        $this->db->bind(':discount', $serviceData['discount'], PDO::PARAM_INT);
        $this->db->bind(':total', $serviceData['totalPrice'], PDO::PARAM_STR);
        $this->db->bind(':employeeId', $serviceData['employeeId'], PDO::PARAM_INT);
        $this->db->bind(':isNextConsecutive', $serviceData['isNextConsecutive'], PDO::PARAM_INT);
        $this->db->execute();
    }

    public function getClientsForLiveSearch($querySymbols)
    {
        $querySymbols = "%" . trim($querySymbols) . "%";
        $query = "SELECT client_id AS clientId, surname, name, patronymic, phone 
            FROM clients 
            WHERE surname LIKE :querySymbols OR name LIKE :querySymbols OR patronymic LIKE :querySymbols OR phone LIKE :querySymbols 
            ORDER BY surname";
        $this->db->query($query);
        $this->db->bind(':querySymbols', $querySymbols, PDO::PARAM_STR);
        $this->db->execute();
        $result = $this->db->resultSet();
        return json_encode($result);
    }

    public function getStaffForLiveSearch($querySymbols)
    {
        $querySymbols = "%" . trim($querySymbols) . "%";
        $query = "SELECT employee_id AS employeeId, surname, name, patronymic 
            FROM staff 
            WHERE is_deleted = 0 AND (surname LIKE :querySymbols OR name LIKE :querySymbols OR patronymic LIKE :querySymbols)";
        $this->db->query($query);
        $this->db->bind(':querySymbols', $querySymbols, PDO::PARAM_STR);
        $this->db->execute();
        $result = $this->db->resultSet();
        return $result;
    }

    public function getServicesForLiveSearch($querySymbols)
    {
        $querySymbols = "%" . trim($querySymbols) . "%";
        $query = "SELECT service_id AS serviceId, service_name AS serviceName, service_cost AS serviceCost, 
            service_duration AS serviceDuration, s.measurement_unit_id AS measurementUnitId, 
            m.measurement_unit_name AS measurementUnit, m.measurement_unit_abbr AS measurementUnitAbbr 
            FROM services s 
            INNER JOIN measurement_units m on s.measurement_unit_id = m.measurement_unit_id 
            WHERE service_name LIKE :querySymbols";
        $this->db->query($query);
        $this->db->bind(':querySymbols', $querySymbols, PDO::PARAM_STR);
        $this->db->execute();
        $result = $this->db->resultSet();
        return $result;
    }

    public function getOrderedServicesByDate($date)
    {
        $query = "SELECT v.visit_id as visitId, v.client_id AS clientId, v.visit_date AS visitDate,
            os.ordered_service_id AS orderedServiceId, os.service_id AS serviceId, os.employee_id AS employeeId,
            os.start_time AS startTime, os.end_time AS endTime, os.total AS totalPrice,
            os.is_next_consecutive AS isNextConsecutive, c.surname AS clientSurname, c.name AS clientName,
            c.patronymic AS clientPatronymic, c.phone AS clientPhone, s.service_name AS serviceName
            FROM visits v 
            INNER JOIN ordered_services os ON v.visit_id = os.visit_id 
            INNER JOIN clients c ON v.client_id = c.client_id
            INNER JOIN services s ON os.service_id = s.service_id
            WHERE v.visit_date = :date";
        $this->db->query($query);
        $this->db->bind(':date', $date, PDO::PARAM_STR);
        $this->db->execute();
        $result = $this->db->resultSet();
        return $result;
    }

    public function prepareVisits($visits)
    {
        $result = [];
        foreach ($visits as $visit) {
            $clientFullName = [
                $visit['clientSurname'],
                $visit['clientName'],
                $visit['clientPatronymic'],

            ];
            $extendedProps = [
                'serviceName' => $visit['serviceName'],
                'totalPrice' => $visit['totalPrice'],
                'visitId' => $visit['visitId'],
                'clientPhone' => $visit['clientPhone'],
                'isNextConsecutive' => $visit['isNextConsecutive'],
            ];
            $result[] = [
                'id' => $visit['orderedServiceId'],
                'resourceId' => $visit['employeeId'],
                'start' => "{$visit['visitDate']}T{$visit['startTime']}",
                'end' => "{$visit['visitDate']}T{$visit['endTime']}",
                'title' => $this->concatFullName($clientFullName),
                'extendedProps' => $extendedProps,
            ];
        }
        return $result;
    }

    private function concatFullName($data)
    {
        $result = "";
        foreach ($data as $item)
            if (trim($item) != "") {
                $result .= $item . " ";
            }
        return trim($result);
    }

    public function getOrderedServicesByVisitId($visitId)
    {
        $query = "SELECT os.ordered_service_id AS orderedServiceId, 
          os.service_id AS serviceId, os.employee_id AS employeeId, os.start_time AS startTime, os.end_time AS endTime, 
          os.quantity AS quantity, os.cost AS cost, os.discount AS discount, 
          s.service_name AS serviceName, st.surname AS employeeSurname, st.name AS employeeName,
          st.patronymic AS employeePatronymic, s.measurement_unit_id AS measurementUnitId,
          m.measurement_unit_name AS measurementUnit,  m.measurement_unit_abbr AS measurementUnitAbbr 
          FROM ordered_services os 
          INNER JOIN services s ON os.service_id = s.service_id
          INNER JOIN staff st ON os.employee_id = st.employee_id
          INNER JOIN measurement_units m on s.measurement_unit_id = m.measurement_unit_id
          WHERE os.visit_id = :visitId
          ORDER BY os.start_time";
        $this->db->query($query);
        $this->db->bind(':visitId', $visitId, PDO::PARAM_STR);
        $this->db->execute();
        $result = $this->db->resultSet();
        return $result;
    }

    public function getVisitInfoById($visitId)
    {
        $query = "SELECT v.visit_id AS visitId, v.visit_date AS visitDate, v.client_id AS clientId, 
            c.surname AS clientSurname, c.name AS clientName, c.patronymic AS clientPatronymic, c.phone AS clientPhone
            FROM visits v 
            INNER JOIN clients c ON v.client_id = c.client_id
            WHERE v.visit_id = :visitId";
        $this->db->query($query);
        $this->db->bind(':visitId', $visitId, PDO::PARAM_STR);
        $this->db->execute();
        $result = $this->db->result();
        return $result;
    }

    public function filterEditedServices($newServices)
    {
        $result = array_filter($newServices, function ($service) {
            return $service['orderedServiceId'] != '';
        });
        return $result;
    }


    public function getMissingServicesId($services, $newServices = null)
    {
        $servicesId = [];
        foreach ($services as $service) {
            $servicesId[] = $service['orderedServiceId'];
        };
        if ($newServices === null) {
            return $servicesId;
        }
        $newServicesId = [];
        foreach ($newServices as $service) {
            if ($service['orderedServiceId'] != '') {
                $newServicesId[] = $service['orderedServiceId'];
            }
        };

        $result = array_diff($servicesId, $newServicesId);
        return $result;
    }

    public function filterNewServices($newServices)
    {
        $result = array_filter($newServices, function ($service) {
            return $service['orderedServiceId'] == '';
        });
        return $result;
    }

    public function updateVisitDate($visitId, $newVisitDate)
    {
        $query = "UPDATE visits SET visit_date=:visitDate WHERE visit_id=:visitId";
        $this->db->query($query);
        $this->db->bind(':visitId', $visitId, PDO::PARAM_INT);
        $this->db->bind(':visitDate', $newVisitDate, PDO::PARAM_STR);
        $this->db->execute();
    }

    public function deleteOrderedServices($servicesToDeleteId)
    {
        $servicesId = implode(',', $servicesToDeleteId);
        $query = "DELETE FROM ordered_services WHERE ordered_service_id IN (:servicesId)";
        $this->db->query($query);
        $this->db->bind(':servicesId', $servicesId, PDO::PARAM_STR);
        $this->db->execute();
    }

    public function updateOrderedServices($servicesToUpdate)
    {
        foreach ($servicesToUpdate as $service) {
            $this->updateOneOrderedService($service);
        };
    }

    public function updateOneOrderedService($serviceData)
    {
        $query = "UPDATE ordered_services SET service_id=:serviceId, start_time=:startTime,end_time=:endTime, 
            quantity=:quantity, cost=:cost, discount=:discount, total=:total, employee_id=:employeeId, 
            is_next_consecutive=:isNextConsecutive 
            WHERE ordered_service_id=:orderedServiceId";
        $this->db->query($query);
        $this->db->bind(':orderedServiceId', $serviceData['orderedServiceId'], PDO::PARAM_INT);
        $this->db->bind(':serviceId', $serviceData['serviceId'], PDO::PARAM_INT);
        $this->db->bind(':startTime', $serviceData['startTime'], PDO::PARAM_STR);
        $this->db->bind(':endTime', $serviceData['endTime'], PDO::PARAM_STR);
        $this->db->bind(':quantity', $serviceData['quantity'], PDO::PARAM_INT);
        $this->db->bind(':cost', $serviceData['cost'], PDO::PARAM_STR);
        $this->db->bind(':discount', $serviceData['discount'], PDO::PARAM_INT);
        $this->db->bind(':total', $serviceData['totalPrice'], PDO::PARAM_STR);
        $this->db->bind(':employeeId', $serviceData['employeeId'], PDO::PARAM_INT);
        $this->db->bind(':isNextConsecutive', $serviceData['isNextConsecutive'], PDO::PARAM_INT);
        $this->db->execute();
    }

    public function deleteVisit($visitId)
    {
        $query = "DELETE FROM visits WHERE visit_id=:visitId";
        $this->db->query($query);
        $this->db->bind(':visitId', $visitId, PDO::PARAM_INT);
        $this->db->execute();
    }

    public function markNextConsecutiveOrders($orderedServices)
    {
        $source = $orderedServices;
        $count = count($source);
        while ($count > 0) {
            $comparedService = array_shift($source);
            $comparedService['isNextConsecutive'] = 0;
            foreach ($source as $item) {
                $hasSameEmployee = ($comparedService['employeeId'] == $item['employeeId']);
                $areConsecutive = $comparedService['startTime'] == $item['endTime'];
                if ($hasSameEmployee && $areConsecutive) {
                    $comparedService['isNextConsecutive'] = 1;
                }
            }
            $source[] = $comparedService;
            $count--;
        }
        return $source;
    }
}