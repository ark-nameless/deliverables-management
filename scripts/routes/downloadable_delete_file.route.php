<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/controllers/downloadable.controller.php';

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/js.core.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/ftp-manager.core.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/session.core.php';


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {   
        $ftp = new FTPServer();
        $downloadable = new DownloadableController();


        $ftp->delete($_GET['filename']);
        $id = $_GET['id'];

        if ($downloadable->delete($id)){
            JS::alert('File Deleted Successfully Sent!');
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