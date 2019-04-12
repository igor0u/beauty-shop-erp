<?php


class Database
{
    private $host = '';
    private $user = '';
    private $pass = '';
    private $dbname = '';

    private $handler;

    private $statement;

    public function __construct()
    {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        try {
            $this->handler = new PDO($dsn, $this->user, $this->pass);
        } catch (PDOException $e) {
            echo 'Database connection error';
            die($e->getCode());
        }
    }

    public function query($query)
    {
        $this->statement = $this->handler->prepare($query);
    }

    public function bind($param, $value, $type)
    {
        $this->statement->bindValue($param, $value, $type);
    }

    public function execute()
    {
        return $this->statement->execute();
    }

    public function result()
    {
        $this->execute();

        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }

    public function resultSet()
    {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function rowCount()
    {
        return $this->statement->rowCount();
    }

    public function lastInsertId()
    {
        return $this->handler->lastInsertId();
    }

    public function beginTransaction()
    {
        $this->handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->handler->beginTransaction();
    }

    public function commit()
    {
        $this->handler->commit();
    }

    public function rollBack()
    {
        $this->handler->rollBack();
    }
}