<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/controllers/deliverable.controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/js.core.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $deliverable = new DeliverableController();
        $id = $_GET['id'];

        if ($deliverable->edit($id, $_POST)){
            JS::alert("Deliverable successfully edited!");
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