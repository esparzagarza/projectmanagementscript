<?php

namespace App;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, PATCH, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

require __DIR__ . '/vendor/autoload.php';

use App\DB;
use App\Task;
use App\TaskService;
use App\Helpers;

$jsondata = file_get_contents("php://input");
$request = json_decode($jsondata, TRUE);


if (isset($request) && count($request) > 0) $request = Helpers::cleanVar($request);

$data = array();
$response = array();
$db = DB::getConnection();
$task = new Task('task');
$service = new TaskService($db);

switch ($_SERVER["REQUEST_METHOD"]) {
    
    case 'GET':
        
        if (isset($_GET) && isset($_GET['action']) && $_GET['action'] == 'getOne') {

            if (Helpers::numericValid($_GET['id']) && $service->CheckExist($task->get('table_name'), intval($_GET['id']))) {
                
                $data = $service->GetOne($task->get('table_name'), intval($_GET['id']));
            } else $response = Helpers::formatResponse(400, 'not found', $data);
        } else {

            $data = $service->GetAll($task->get('table_name'));
        }
        $response = Helpers::formatResponse(200, 'completed', $data);
        
        break;
        
        case 'POST':
            
            if (Helpers::isPermittedType($request['type'])) {
                
                foreach (Helpers::permittedProperties() as $key) $task->set($key, $request[$key]);
                
                $lastInsertId = $service->Add($task);

            if (Helpers::greaterThanCero($lastInsertId)) {

                $data = $service->GetOne($task->get('table_name'), $lastInsertId);

                $response = Helpers::formatResponse(200, 'completed', $data);
            } else $response = Helpers::formatResponse(200, 'not created', $data);
        } else $response = Helpers::formatResponse(200, "Not a valid Type", $data);

        break;


    case 'PUT':

        if (Helpers::numericValid($request['id']) && $service->CheckExist($task->get('table_name'), $request['id'])) {

            foreach (Helpers::permittedProperties() as $key) $task->set($key, $request[$key]);

            $task->set('id', $request['id']);

            if ($service->Edit($task)) {

                $data = $service->GetOne($task->get('table_name'), $task->get('id'));

                $response = Helpers::formatResponse(200, 'completed', $data);
            } else $response = Helpers::formatResponse(200, 'not updated', $data);
        } else $response = Helpers::formatResponse(400, 'not found', $data);

        break;


    case 'PATCH':

        if (Helpers::numericValid($request['id']) && $service->CheckExist($task->get('table_name'), $request['id'])) {

            $task->set('status', $request['status']);

            $task->set('id', $request['id']);

            if ($service->EditStatus($task)) {

                $data = $service->GetOne($task->get('table_name'), $task->get('id'));

                $response = Helpers::formatResponse(200, 'completed', $data);
            } else $response = Helpers::formatResponse(200, 'not status updated', $data);
        } else $response = Helpers::formatResponse(400, 'not found', $data);

        break;


    case 'DELETE':

        if (Helpers::numericValid($request['id']) && $service->CheckExist($task->get('table_name'), $request['id'])) {

            if ($service->HardDelete($task->get('table_name'), $request['id'])) $response = Helpers::formatResponse(200, 'completed', $data);

            else $response = Helpers::formatResponse(200, 'not deleted', $data);
        } else $response = Helpers::formatResponse(400, 'not found', $data);
        break;

    default:

        $response = Helpers::formatResponse(200, "Not a valid Verb", $data);

        break;
}

// Exit
if (isset($db)) unset($db);
if (isset($task)) unset($task);
if (isset($service)) unset($service);
Helpers::returnToAction($response);
exit;
