<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/controllers/submitted_file.controller.php';

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/js.core.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {   
        $id = $_GET['id'];
        $_POST['date_updated'] = date("Y-m-d"); 

        $submission = new SubmittedFileController();

        if ($submission->edit($id, $_POST)){
            JS::alert('Deliverable succesfully reviewed!');
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