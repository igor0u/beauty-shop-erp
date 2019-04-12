<?php
require_once ROOT . '/app/models/Database.php';

class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = new Database();
    }
}