<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/controllers/faculty.controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/controllers/user.controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/js.core.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $faculty = new FacultyController();
        $users = new UserController();

        $credentials = [
            'email' => $_POST['email'],
            'username' => $_POST['username'],
        ];

        $info = [
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'middlename' => $_POST['middlename'],
            'address' => $_POST['address'],
            'contact_no' => $_POST['contact_no'],
        ];
        
        $id = $_GET['id'];

        if ($faculty->edit($id, $info) && $users->edit($id, $credentials)){
            JS::alert("Faculty successfully edited!");
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