<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/controllers/submitted_file.controller.php';

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/js.core.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/ftp-manager.core.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/session.core.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {   
        echo var_dump($_POST);
        $session = new Session();
        $ftp = new FTPServer();
        $submission = new SubmittedFileController();

        $current_id = $session->get('id');
        $ext = strtolower(pathinfo($_FILES['name']['name'], PATHINFO_EXTENSION));
        $file_type = $_FILES['name']['type'];

        $timestamp = $_SERVER['REQUEST_TIME'];
        $filename = 'downloadable-' . $current_id   . '-' . $timestamp . '.'.$ext;

        $_POST['date_updated'] = date("Y-m-d"); 
        $data = [
            'description' =>$_POST['description'],
            'semester_id' =>$_POST['semester_id'],
            'school_year_id' =>$_POST['school_year_id'],
        ];

        $file_exists = $_FILES['name']['name'];

        if (empty($file_exists)){
        } else {
            $ftp->delete($_GET['filename']);
            $data['filename'] = $filename;
            $data['ext'] = $ext;
        }
        $id = $_GET['id'];

        if ($submission->edit($id, $data)){
            JS::alert('Submission Edit Successfully Sent!');
            JS::history(1);
            if (!empty($file_exists)){
                $ftp->save_file($filename, $_FILES['name']['tmp_name']);
            }
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