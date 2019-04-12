<?php

require_once ROOT . '/app/models/Model.php';
require_once ROOT . '/app/models/Database.php';

class Staff extends Model
{
    public function getStaffList()
    {
        $query = "SELECT st.employee_id as employeeId, st.surname, st.name, st.patronymic, st.position_id, 
            st.date_of_birth as dateOfBirth, st.is_deleted AS isDeleted, p.position_name AS positionName 
            FROM staff st 
            LEFT JOIN positions AS p on st.position_id = p.position_id";
        $this->db->query($query);
        $result = $this->db->resultSet();
        return $result;
    }

    public function getEmployeeById($employeeId)
    {
        $query = "SELECT employee_id as employeeId, surname, name, patronymic, position_id AS positionId, 
            date_of_birth AS dateOfBirth, is_deleted AS isDeleted 
            FROM staff WHERE employee_id = :employeeId";
        $this->db->query($query);
        $this->db->bind(':employeeId', $employeeId, PDO::PARAM_INT);
        $result = $this->db->result();
        return $result;
    }

    public function updateEmployee($data)
    {

        $query = "UPDATE staff 
            SET surname = :surname, name = :name, patronymic = :patronymic, position_id=:position, 
                date_of_birth=:dateOfBirth 
            WHERE employee_id = :employeeId";
        $this->db->query($query);
        $this->db->bind(':employeeId', $data['employeeId'], PDO::PARAM_INT);
        $this->db->bind(':surname', $data['surname'], PDO::PARAM_STR);
        $this->db->bind(':name', $data['name'], PDO::PARAM_STR);
        $this->db->bind(':patronymic', $data['patronymic'], PDO::PARAM_STR);
        $this->db->bind(':position', $data['position'], PDO::PARAM_INT);
        $this->db->bind(':dateOfBirth', $data['dateOfBirth'], PDO::PARAM_STR);
        $result = $this->db->execute();
        return $result;
    }

    public function addEmployee($data)
    {
        $query = "INSERT INTO staff (surname, name, patronymic, position_id, date_of_birth) 
            VALUES (:surname, :name, :patronymic, :position,:dateOfBirth)";
        $this->db->query($query);
        $this->db->bind(':surname', $data['surname'], PDO::PARAM_STR);
        $this->db->bind(':name', $data['name'], PDO::PARAM_STR);
        $this->db->bind(':patronymic', $data['patronymic'], PDO::PARAM_STR);
        $this->db->bind(':position', $data['position'], PDO::PARAM_INT);
        $this->db->bind(':dateOfBirth', $data['dateOfBirth'], PDO::PARAM_STR);
        $result = $this->db->execute();
        return $result;
    }

    public function getPositionsList()
    {
        $query = "SELECT position_id as positionId, position_name as positionName, is_deleted AS isDeleted 
            FROM positions";
        $this->db->query($query);
        $result = $this->db->resultSet();
        return $result;
    }

    public function getPositionById($positionId)
    {
        $query = "SELECT position_id as positionId, position_name as positionName, is_deleted AS isDeleted 
            FROM positions 
            WHERE position_id = :positionId";
        $this->db->query($query);
        $this->db->bind(':positionId', $positionId, PDO::PARAM_INT);
        $result = $this->db->result();
        return $result;
    }

    public function addPosition($name)
    {
        $query = "INSERT INTO positions (position_name) VALUES (:positionName)";
        $this->db->query($query);
        $this->db->bind(':positionName', $name, PDO::PARAM_STR);
        $result = $this->db->execute();
        return $result;
    }

    public function updatePosition($data)
    {
        $query = "UPDATE positions SET position_name = :positionName WHERE position_id = :positionId";
        $this->db->query($query);
        $this->db->bind(':positionId', $data['positionId'], PDO::PARAM_INT);
        $this->db->bind(':positionName', $data['positionName'], PDO::PARAM_STR);
        $result = $this->db->execute();
        return $result;
    }

    public function prepareStaffList($staffList)
    {
        $result = [];
        foreach ($staffList as $item) {
            $employee = [
                'employeeId' => $item['employeeId'],
                'name' => $item['surname'] . ' ' . $item['name'] . ' ' . $item['patronymic'],
                'position' => $item['positionName'],
                'dateOfBirth' => ($item['dateOfBirth'] === '0000-00-00') ? '' : $item['dateOfBirth'],
                'isDeleted' => $item['isDeleted'],
            ];
            array_push($result, $employee);
        }
        return $result;
    }

    public function deleteEmployee($employeeId)
    {
        $query = "UPDATE staff SET is_deleted = 1 WHERE employee_id = :employeeId";
        $this->db->query($query);
        $this->db->bind(':employeeId', $employeeId, PDO::PARAM_INT);
        $result = $this->db->execute();
        return $result;
    }

    public function restoreEmployee($employeeId)
    {
        $query = "UPDATE staff SET is_deleted = 0 WHERE employee_id = :employeeId";
        $this->db->query($query);
        $this->db->bind(':employeeId', $employeeId, PDO::PARAM_INT);
        $result = $this->db->execute();
        return $result;
    }

    public function deletePosition($positionId)
    {
        $query = "UPDATE positions SET is_deleted = 1 WHERE position_id = :positionId";
        $this->db->query($query);
        $this->db->bind(':positionId', $positionId, PDO::PARAM_INT);
        $result = $this->db->execute();
        return $result;
    }

    public function restorePosition($positionId)
    {
        $query = "UPDATE positions SET is_deleted = 0 WHERE position_id = :positionId";
        $this->db->query($query);
        $this->db->bind(':positionId', $positionId, PDO::PARAM_INT);
        $result = $this->db->execute();
        return $result;
    }
}