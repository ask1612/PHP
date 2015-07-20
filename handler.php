<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
if (!isset($_SESSION['id']) && !isset($_SESSION['name'])) {
    exit;
}
$response_str = '{
          "head": "",
          "success": "",
          "data":"",
           "msg":""
     }';
//convert to stdclass object
$response = json_decode($response_str);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "db_connect.php";
    $db = new DB_Connect(); //connect to database\F
    if ($db) {
        $response->success = 'yes';
    } else {
        $responce->success = 'no';
        $response->msg = 'Не могу подключиться к базе данных';
        return;
    }
    $json_str = $_POST["json_str"];
    //convert to stdclass object
    $json_obj = json_decode($json_str);
    $response->head = $json_obj->head;
    switch ($json_obj->head) {
        case "disabled_enabled":
            switch ($json_obj->name_tbl) {
                case DB_MSG:
                case DB_ACT:
                    $row = $db->selectPrepare($json_obj->doc_id);
                    break;
                case DB_PREPARE:
                    $row = $db->selectReview($json_obj->doc_id);
                    if (count($row) == 0) {
                        $row = $db->selectInform($json_obj->doc_id);
                    }
                    break;
                case DB_REVIEW:
                    $row = $db->selectPenalty($json_obj->doc_id);
                    if (count($row) == 0) {
                        $row = $db->selectTermination($json_obj->doc_id);
                    }
                    break;
                case DB_PENALTY:
                    $row = $db->selectInform($json_obj->doc_id);
                    break;
            }
            if (count($row) != 0) {
                $response->data = 'disabled';
            } else {
                $response->data = 'enabled';
            }
          break;
        case "html":
            require_once "get_data.php";
            $response->data = getData($json_obj->doc_id);
            break;
        case "delete":
            $result = $db->deleteDoc($json_obj->name_tbl, $json_obj->doc_id); //delete doc .
            if ($result)
                $response->data = 'Данные успешно удалены!';
            else
                $response->data = 'Ошибка при удалении данных!';
            break;
    }
    //$response->msg = "Response";
    $str = json_encode($response);
    echo $str;
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
