<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/controllers/activity.controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/js.core.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $activity = new ActivityController();
        
        if ($activity->add($_POST)){
            JS::alert("{$_POST['activity_name']} successfully added!");
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