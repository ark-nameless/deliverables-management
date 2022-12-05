<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/controllers/school_year.controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/js.core.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $sy = new SchoolYearController();
        $id = $_GET['id'];


        if ($sy->delete($id)){
            JS::alert("School year successfully Removed!");
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