<?php

require_once ROOT . '/app/models/Model.php';
require_once ROOT . '/app/models/Database.php';

class Services extends Model
{
    public function getServicesList()
    {
        $query = "SELECT s.service_id AS serviceId, s.service_name AS serviceName, c.category_name AS categoryName,
            s.category_id AS categoryId, s.service_cost AS serviceCost, s.measurement_unit_id AS measurementUnitId, 
            s.service_duration AS serviceDuration, s.is_deleted AS isDeleted,
            m.measurement_unit_abbr AS measurementUnitAbbr
            FROM services s 
            LEFT JOIN service_categories c ON s.category_id = c.category_id
            LEFT JOIN measurement_units m ON s.measurement_unit_id = m.measurement_unit_id";
        $this->db->query($query);
        $this->db->execute();
        $result = $this->db->resultSet();
        return $result;
    }

    public function getCategoriesList()
    {
        $query = "SELECT category_id AS categoryId, category_name AS categoryName, is_deleted AS isDeleted 
            FROM service_categories";
        $this->db->query($query);
        $this->db->execute();
        $result = $this->db->resultSet();
        return $result;
    }

    public function addCategory($name)
    {
        $query = "INSERT INTO service_categories (category_name) VALUES (:categoryName)";
        $this->db->query($query);
        $this->db->bind(':categoryName', $name, PDO::PARAM_STR);
        $result = $this->db->execute();
        return $result;
    }

    public function addService($data)
    {
        $query = "INSERT INTO services (service_name, category_id, service_cost, measurement_unit_id, service_duration) 
            VALUES (:serviceName, :categoryId, :serviceCost, :measurementUnitId, :service_duration)";
        $this->db->query($query);
        $this->db->bind(':serviceName', $data['serviceName'], PDO::PARAM_STR);
        $this->db->bind(':categoryId', $data['serviceCategoryId'], PDO::PARAM_INT);
        $this->db->bind(':serviceCost', $data['serviceCost'], PDO::PARAM_STR);
        $this->db->bind(':measurementUnitId', $data['measurementUnitId'], PDO::PARAM_INT);
        $this->db->bind(':service_duration', $data['serviceDuration'], PDO::PARAM_STR);
        $result = $this->db->execute();
        return $result;
    }

    public function getMeasurementUnits()
    {
        $query = "SELECT measurement_unit_id AS measurementUnitId, measurement_unit_name AS measurementUnitName 
            FROM measurement_units";
        $this->db->query($query);
        $this->db->execute();
        $result = $this->db->resultSet();
        return $result;
    }

    public function getServiceById($serviceId)
    {
        $query = "SELECT service_id AS serviceId, service_name as serviceName, category_id AS categoryId, 
              service_cost AS serviceCost, measurement_unit_id AS measurementUnitId, 
              service_duration AS serviceDuration, is_deleted AS isDeleted 
              FROM services WHERE service_id = :serviceId";
        $this->db->query($query);
        $this->db->bind(':serviceId', $serviceId, PDO::PARAM_INT);
        $this->db->execute();
        $result = $this->db->result();
        return $result;
    }

    public function getCategoryById($categoryId)
    {
        $query = "SELECT category_id AS categoryId, category_name AS categoryName, is_deleted AS isDeleted 
              FROM service_categories WHERE category_id=:categoryId";
        $this->db->query($query);
        $this->db->bind(':categoryId', $categoryId, PDO::PARAM_INT);
        $this->db->execute();
        $result = $this->db->result();
        return $result;
    }

    public function updateCategory(array $data)
    {
        $query = "UPDATE service_categories SET category_name=:categoryName WHERE category_id=:categoryId";
        $this->db->query($query);
        $this->db->bind(':categoryName', $data['categoryName'], PDO::PARAM_STR);
        $this->db->bind(':categoryId', $data['categoryId'], PDO::PARAM_INT);
        $result = $this->db->execute();
        return $result;
    }

    public function correctServiceDurationIfNeeded(&$data)
    {
        if ($data['measurementUnitId'] == 1) {
            $data['serviceDuration'] = "00:01:00";
        }
    }

    public function updateService($data)
    {
        $query = "UPDATE services 
            SET service_name=:serviceName, category_id=:categoryId, service_cost=:serviceCost,
            measurement_unit_id=:measurementUnitId, service_duration=:serviceDuration 
            WHERE service_id=:serviceId";
        $this->db->query($query);
        $this->db->bind(':serviceName', $data['serviceName'], PDO::PARAM_STR);
        $this->db->bind(':categoryId', $data['categoryId'], PDO::PARAM_INT);
        $this->db->bind(':serviceCost', $data['serviceCost'], PDO::PARAM_STR);
        $this->db->bind(':measurementUnitId', $data['measurementUnitId'], PDO::PARAM_INT);
        $this->db->bind(':serviceDuration', $data['serviceDuration'], PDO::PARAM_STR);
        $this->db->bind(':serviceId', $data['serviceId'], PDO::PARAM_INT);
        $result = $this->db->execute();
        return $result;
    }

    public function deleteService($serviceId)
    {
        $query = "UPDATE services SET is_deleted = 1 WHERE service_id = :serviceId";
        $this->db->query($query);
        $this->db->bind(':serviceId', $serviceId, PDO::PARAM_INT);
        $result = $this->db->execute();
        return $result;
    }

    public function restoreService($serviceId)
    {
        $query = "UPDATE services SET is_deleted = 0 WHERE service_id = :serviceId";
        $this->db->query($query);
        $this->db->bind(':serviceId', $serviceId, PDO::PARAM_INT);
        $result = $this->db->execute();
        return $result;
    }

    public function deleteCategory($categoryId)
    {
        $query = "UPDATE service_categories SET is_deleted = 1 WHERE category_id = :categoryId";
        $this->db->query($query);
        $this->db->bind(':categoryId', $categoryId, PDO::PARAM_INT);
        $result = $this->db->execute();
        return $result;
    }

    public function restoreCategory($categoryId)
    {
        $query = "UPDATE service_categories SET is_deleted = 0 WHERE category_id = :categoryId";
        $this->db->query($query);
        $this->db->bind(':categoryId', $categoryId, PDO::PARAM_INT);
        $result = $this->db->execute();
        return $result;
    }
}