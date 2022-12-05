<?php


include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/controllers/faculty.controller.php';

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/js.core.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $faculty = new FacultyController();
        $id = $_GET['id'];
        $username = $_GET['username'];


        if ($faculty->delete($id)){
            JS::alert("Faculty {$username} successfully Removed!");
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