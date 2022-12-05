<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/controllers/report.controller.php';

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/js.core.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/ftp-manager.core.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/session.core.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {   
        $session = new Session();
        $ftp = new FTPServer();
        $reports = new ReportController();

        $current_id = $session->get('id');
        $ext = strtolower(pathinfo($_FILES['name']['name'], PATHINFO_EXTENSION));
        $file_type = $_FILES['name']['type'];

        $timestamp = $_SERVER['REQUEST_TIME'];
        $filename = 'activity-' . $current_id   . '-' . $timestamp . '.'.$ext;

        $data = [
            'faculty_id' => $current_id,
            'activity_id' => $_GET['id'],
            'attachment' => $filename,
            'description' =>$_POST['description'],
        ];

        if ($reports->add($data)){
            JS::alert('Report Successfully Sent!');
            JS::history(1);
            $ftp->save_file($filename, $_FILES['name']['tmp_name']);
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