<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/controllers/semester.controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/js.core.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sems = new SemesterController();
        
        if ($sems->add($_POST)){
            JS::alert("{$_POST['semester']} successfully added!");
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