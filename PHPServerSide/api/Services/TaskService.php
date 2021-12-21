<?php

namespace App;

use App\Task;

class TaskService
{
    public $_conn;

    public function __construct($db)
    {
        $this->_conn = $db;
    }

    function Add(Task $task): int
    {
        $sql = "INSERT INTO " . $task->table_name . " SET id=NULL, type=:type, name=:name, description=:description, notes=:notes, tags=:tags, prio=:prio, status=:status, duedate=:duedate, createdDT=now(), modifiedDT=now()";

        $stmt = $this->_conn->prepare($sql);

        foreach (Helpers::permittedProperties() as $key) $stmt->bindValue(":" . $key, $task->get($key), \PDO::PARAM_STR);

        if ($stmt->execute()) return $this->_conn->lastInsertId();

        return 0;
    }

    function Edit(Task $task): bool
    {
        if ($task->get('id')) {

            $sql = "UPDATE " . $task->table_name . " SET type=:type, name=:name, description=:description, notes=:notes, tags=:tags, prio=:prio, status=:status, duedate=:duedate, modifiedDT=now() WHERE id=:id";

            $stmt = $this->_conn->prepare($sql);

            foreach (Helpers::permittedProperties() as $key) $stmt->bindValue(":" . $key, $task->get($key), \PDO::PARAM_STR);

            $stmt->bindValue(":id", $task->get('id'));

            if ($stmt->execute()) return true;
        }
        return false;
    }

    function EditStatus(Task $task): bool
    {
        if ($task->get('id')) {

            $sql = "UPDATE " . $task->table_name . " SET status=:status, modifiedDT=now() WHERE id=:id";

            $stmt = $this->_conn->prepare($sql);

            $stmt->bindValue(":status", $task->get('status'));

            $stmt->bindValue(":id", $task->get('id'));

            if ($stmt->execute()) return true;
        }
        return false;
    }

    function CheckExist(string $table_name, int $id): bool
    {
        $sql = "SELECT EXISTS(SELECT * FROM " . $table_name . " WHERE id = ?) AS exist";

        $stmt = $this->_conn->prepare($sql);

        $stmt->bindValue(1, $id);

        if ($stmt->execute()) $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result['exist'] ? true : false;
    }

    function GetAll(string $table_name): array
    {
        $sql = "SELECT *  FROM " . $table_name;

        $stmt = $this->_conn->prepare($sql);

        if ($stmt->execute()) return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array();
    }

    function GetMinimum(string $table_name): array
    {
        $sql = "SELECT *  FROM " . $table_name . " ORDER BY id DESC LIMIT 15";

        $stmt = $this->_conn->prepare($sql);

        if ($stmt->execute()) return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array();
    }

    function GetOne(string $table_name, int $id): array
    {
        $sql = "SELECT * FROM " . $table_name . " WHERE id = ? LIMIT 1";

        $stmt = $this->_conn->prepare($sql);

        $stmt->bindValue(1, $id);

        if ($stmt->execute()) return $stmt->fetch(\PDO::FETCH_ASSOC);

        return array();
    }

    function HardDelete(string $table_name, int $id): bool
    {
        $sql = "DELETE FROM " . $table_name . " WHERE id = ?";

        $stmt = $this->_conn->prepare($sql);

        $stmt->bindValue(1, $id);

        if ($stmt->execute()) return true;

        return false;
    }
}
