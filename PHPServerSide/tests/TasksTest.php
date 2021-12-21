<?php

namespace App;

use PHPUnit\Framework\TestCase;
use App\Helpers;
use App\DB;
use App\TaskService;

class TasksTest extends TestCase
{
    private $db;
    private $task;
    private $service;
    private $request;

    public function setUp(): void
    {
        $this->db = DB::getConnection();
        $this->task = new Task('task');
        $this->service = new TaskService($this->db);
        $this->request = [
            "id" => "1",
            "type" => "appointment",
            "name" => "Meet the customer",
            "description" => "Appoint for meet the customer",
            "notes" => "Bring all devices for portfolio demos",
            "tags" => "webapp, system, script, CRUD, PHP, Angular",
            "prio" => "medium",
            "status" => "inprogress",
            "duedate" => "2022-01-01 10:01:59"
        ];
    }

    public function testDatabaseCanBeCalledAsStatic()
    {
        $this->assertEquals(DB::getConnection(), $this->db);
    }

    public function testTaskObjectHasBeenCreated()
    {
        $this->assertTrue(is_object(Helpers::createTask('meet')));
    }

    public function testServiceObjectHasBeenCreated()
    {
        $this->assertTrue(is_object(new TaskService($this->db)));
    }

    public function testCreateANewTaskRow()
    {
        if (Helpers::isPermittedType($this->request['type'])) {

            foreach (Helpers::permittedProperties() as $key) $this->task->set($key, $this->request[$key]);

            $lastInsertId = $this->service->Add($this->task);

            if (Helpers::greaterThanCero($lastInsertId)) {

                $data = $this->service->GetOne($this->task->get('table_name'), $lastInsertId);

                $this->assertIsArray($data);
                $this->assertTrue(count($data) > 0);
                $this->assertTrue(count($data) == 11);
                $this->assertFalse(empty($data));
            } else $this->assertFalse($this->service->CheckExist($this->task->get('table_name'), $this->request['id']));
        } else $this->assertFalse(Helpers::isPermittedType($this->request['type']));
    }

    public function testEditingTaskRow()
    {
        if (Helpers::numericValid($this->request['id']) && $this->service->CheckExist($this->task->get('table_name'), $this->request['id'])) {

            foreach (Helpers::permittedProperties() as $key) $this->task->set($key, $this->request[$key]);

            $this->task->set('id', $this->request['id']);

            if ($this->service->Edit($this->task)) {

                $data = $this->service->GetOne($this->task->get('table_name'), $this->task->get('id'));

                $this->assertIsArray($data);
                $this->assertTrue(count($data) > 0);
                $this->assertTrue(count($data) == 11);
                $this->assertFalse(empty($data));
            } else $this->assertFalse($this->service->Edit($this->task));
        } else $this->assertFalse($this->service->CheckExist($this->task->get('table_name'), $this->request['id']));
    }

    public function testEditingTaskStatusColumn()
    {
        if (Helpers::numericValid($this->request['id']) && $this->service->CheckExist($this->task->get('table_name'), $this->request['id'])) {

            $this->task->set('status', $this->request['status']);

            $this->task->set('id', $this->request['id']);

            if ($this->service->EditStatus($this->task)) {

                $data = $this->service->GetOne($this->task->table_name, $this->task->get('id'));

                $this->assertIsArray($data);
                $this->assertTrue(count($data) > 0);
                $this->assertTrue(count($data) == 11);
                $this->assertFalse(empty($data));
            } else $this->assertFalse($this->service->EditStatus($this->task));
        } else $this->assertFalse($this->service->CheckExist($this->task->get('table_name'), $this->request['id']));
    }

    public function testATaskRowExist()
    {
        $this->assertTrue($this->service->CheckExist($this->task->get('table_name'), 1));
        $this->assertTrue($this->service->CheckExist($this->task->get('table_name'), 2));

        $this->assertFalse($this->service->CheckExist($this->task->get('table_name'), 9999));
        $this->assertFalse($this->service->CheckExist($this->task->get('table_name'), 8888));
    }

    public function testGetAllRowsData()
    {
        $data = $this->service->GetAll($this->task->get('table_name'));

        $this->assertIsArray($data);
        $this->assertTrue(count($data) > 0);
        $this->assertFalse(empty($data));
    }

    public function testGetMinimumRowsData()
    {
        $data = $this->service->GetMinimum($this->task->get('table_name'));

        $this->assertIsArray($data);
        $this->assertTrue(count($data) > 0);
        $this->assertCount(15, $data);
        $this->assertFalse(empty($data));
    }

    public function testGetOneRowData()
    {
        if (Helpers::numericValid($this->request['id']) && $this->service->CheckExist($this->task->get('table_name'), $this->request['id'])) {

            $result = $this->service->GetOne($this->task->get('table_name'), $this->request['id']);

            $this->assertIsArray($result);
            $this->assertTrue(count($result) > 0);
            $this->assertTrue(count($result) == 11);
            $this->assertFalse(empty($result));
        } else $this->assertFalse($this->service->CheckExist($this->task->get('table_name'), $this->request['id']));
    }

    public function testHardDeleteRowTask()
    {
        $id = 800;

        Helpers::numericValid($id) && $this->service->CheckExist($this->task->get('table_name'), $id)
            ? $this->assertTrue($this->service->HardDelete($this->task->get('table_name'), $id))
            : $this->assertFalse($this->service->CheckExist($this->task->get('table_name'), $id));
    }
}
