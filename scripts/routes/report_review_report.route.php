<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/controllers/report.controller.php';

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/js.core.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {   
        $reports = new ReportController();
        $id = $_GET['id'];

        if ($reports->edit($id, $_POST)){
            JS::alert('Report succesfully reviewed!');
            JS::history(1); 
            exit;
        }


        JS::alert('Something went wrong');
        JS::history(1);
        exit;            
    } catch (PDOException $e) {
        http_response_code(500);
        echo($e->getMessage());
        exit;
    }
}