<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/controllers/downloadable.controller.php';

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/js.core.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/ftp-manager.core.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/session.core.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {   
        $session = new Session();
        $ftp = new FTPServer();
        $downloadable = new DownloadableController();

        $current_id = $session->get('id');
        $ext = strtolower(pathinfo($_FILES['name']['name'], PATHINFO_EXTENSION));
        $file_type = $_FILES['name']['type'];

        $timestamp = $_SERVER['REQUEST_TIME'];
        $filename = 'downloadable-' . $current_id   . '-' . $timestamp . '.'.$ext;

        $data = [
            'description' =>$_POST['description'],
        ];

        $file_exists = $_FILES['name']['name'];

        if (empty($file_exists)){
        } else {
            $ftp->delete($_GET['filename']);
            $data['filename'] = $filename;
            $data['ext'] = $ext;
        }
        $id = $_GET['id'];

        if ($downloadable->edit($id, $data)){
            JS::alert('File Edit Successfully Sent!');
            JS::history(1);
            if (!$file_exists !== ''){
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