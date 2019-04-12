<?php

require_once ROOT . '/app/controllers/Controller.php';
require_once ROOT . '/app/models/Model.php';

class Clients extends Model
{

    public function getClientsList()
    {
        $query = "SELECT c.client_id as clientId, c.surname, c.name, c.patronymic, c.phone, 
            IFNULL(c_total.total, 0.00) AS volumeOfSales, c.date_of_birth AS dateOfBirth, c.is_deleted AS isDeleted,
            (SELECT COUNT(*) FROM visits v WHERE c.client_id=v.client_id) AS numberOfVisits,
            (SELECT v.visit_date FROM visits v WHERE v.client_id=c.client_id ORDER BY v.visit_date DESC LIMIT 1) AS lastVisitDate 
            FROM clients c
            LEFT JOIN (SELECT os.total, v.client_id FROM ordered_services os 
                          LEFT JOIN visits v ON os.visit_id=v.visit_id 
                          GROUP BY v.client_id) AS c_total 
            ON c_total.client_id=c.client_id";
        $this->db->query($query);
        $result = $this->db->resultSet();
        foreach ($result as &$client) {
            $client['fullName'] = $this->concatFullName($client['surname'], $client['name'], $client['patronymic']);
        }
        return $result;
    }

    public function concatFullName($surname, $name, $patronymic)
    {
        $result = trim(trim($surname . ' ' . $name) . ' ' . $patronymic);
        return $result;

    }

    public function addClient($clientData)
    {
        $query = "INSERT INTO clients(surname, name, patronymic, phone, date_of_birth) 
            VALUES (:surname, :name, :patronymic, :phone, :dateOfBirth)";
        $this->db->query($query);
        $this->db->bind(':surname', $clientData['surname'], PDO::PARAM_STR);
        $this->db->bind(':name', $clientData['name'], PDO::PARAM_STR);
        $this->db->bind(':patronymic', $clientData['patronymic'], PDO::PARAM_STR);
        $this->db->bind(':phone', $clientData['phone'], PDO::PARAM_STR);
        $this->db->bind(':dateOfBirth', $clientData['dateOfBirth'], PDO::PARAM_STR);
        $this->db->execute();
    }

    public function getClientById($clientId)
    {
        $query = "SELECT client_id AS clientId, surname AS surname, name AS name, patronymic AS patronymic, 
            phone AS phone, date_of_birth AS dateOfBirth, is_deleted AS isDeleted
            FROM clients WHERE client_id = :clientId";
        $this->db->query($query);
        $this->db->bind(':clientId', $clientId, PDO::PARAM_INT);
        $this->db->execute();
        $result = $this->db->result();
        return $result;
    }

    public function updateClient($clientData)
    {
        $query = "UPDATE clients 
            SET surname=:surname, name=:name, patronymic=:patronymic, phone=:phone, date_of_birth=:dateOfBirth 
            WHERE client_id=:clientId";
        $this->db->query($query);
        $this->db->bind(':clientId', $clientData['clientId'], PDO::PARAM_INT);
        $this->db->bind(':surname', $clientData['surname'], PDO::PARAM_STR);
        $this->db->bind(':name', $clientData['name'], PDO::PARAM_STR);
        $this->db->bind(':patronymic', $clientData['patronymic'], PDO::PARAM_STR);
        $this->db->bind(':phone', $clientData['phone'], PDO::PARAM_STR);
        $this->db->bind(':dateOfBirth', $clientData['dateOfBirth'], PDO::PARAM_STR);
        $result = $this->db->execute();
        return $result;
    }

    public function deleteClient($clientId)
    {
        $query = "UPDATE clients SET is_deleted = 1 WHERE client_id = :clientId";
        $this->db->query($query);
        $this->db->bind(':clientId', $clientId, PDO::PARAM_INT);
        $result = $this->db->execute();
        return $result;
    }

    public function restoreClient($clientId)
    {
        $query = "UPDATE clients SET is_deleted = 0 WHERE client_id = :clientId";
        $this->db->query($query);
        $this->db->bind(':clientId', $clientId, PDO::PARAM_INT);
        $result = $this->db->execute();
        return $result;
    }
}