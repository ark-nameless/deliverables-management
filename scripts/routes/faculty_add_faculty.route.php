<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/controllers/user.controller.php';

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/js.core.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/mailer.core.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $users = new UserController();

        $credentials = [
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'role' => $_POST['role'],
        ];

        $info = [
            'faculty_id' => $_POST['faculty_id'],
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'middlename' => $_POST['middlename'],
            'address' => $_POST['address'],
            'contact_no' => $_POST['contact_no'],
            'profile_img' => '',
        ];

        if ($users->registerUser($credentials, $info)){
            $mail = new Mailer();
            $mail->sendAccountEmail($credentials['email'],$credentials['username'],$credentials['password']);
            JS::alert("{$credentials['username']} successfully registered!");
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