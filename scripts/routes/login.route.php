<?php

// include_once '../controllers/product.controller.php';
// include_once './../mailer.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/authenticator.core.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/js.core.php';
includE_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/guard.core.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($_POST['email'] == '' || empty($_POST['password'])){
            JS::alert('Please fill up all the field.');
            exit;            
        }
        $auth = new Auth();

        if ($auth->attempt($_POST['email'], $_POST['password'])){
            JS::alert('Correct Authentication');
            Guard::intercept();
            exit;
        } else {
            JS::alert('Invalid Email/Password');
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