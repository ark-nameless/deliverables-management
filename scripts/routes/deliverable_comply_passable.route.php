<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/controllers/deliverable.controller.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/controllers/submitted_file.controller.php';

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/js.core.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/ftp-manager.core.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/session.core.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {   
        $session = new Session();
        $submission = new SubmittedFileController();


        $ext = strtolower(pathinfo($_FILES['name']['name'], PATHINFO_EXTENSION));
        $file_type = $_FILES['name']['type'];

        $timestamp = $_SERVER['REQUEST_TIME'];
        $filename = $_POST['faculty_id'] . '-' . $timestamp . '.'.$ext;

        $data = [
            'name' => $filename,
            'description' =>$_POST['description'],
            'deliverable_id' =>$_POST['deliverable_id'],
            'faculty_id' => $_POST['faculty_id'],
            'semester_id' =>$_POST['semester_id'],
            'school_year_id' =>$_POST['schoo_year_id'],
            'ext' => $ext,
        ];

        if ($submission->add($data)){
            JS::alert('File Successfully Sent!');
            JS::history(1);
            $ftp = new FTPServer($filenamem);
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