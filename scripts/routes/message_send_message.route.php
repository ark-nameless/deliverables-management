<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/controllers/message.controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/js.core.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $messenger = new MessageController();
        
        if ($messenger->newMessage($_POST)){
            JS::history(1);
            exit;            
        }

        JS::history(1);
        exit;            
    } catch (PDOException $e) {
        http_response_code(500);
        echo($e->getMessage());
        exit;
    }
}