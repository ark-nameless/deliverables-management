<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/controllers/semester.controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/js.core.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $sems = new SemesterController();
        $id = $_GET['id'];


        if ($sems->delete($id)){
            JS::alert("{$_GET['semester']} successfully Removed!");
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