<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/ftp-manager.core.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/mime.types.php';

$ftp = new FTPServer();

$ext = $_GET['ext'];
$filename = $_GET['filename'];
$mime_type = MimeTypes::$mimes[$ext];
$file_full_path = $ftp->getFilePath($filename);

$file = $ftp->get_file($filename);

header("Content-Type: $mime_type");
header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
header('Pragma: public');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Content-Length: '.strlen($file));
header('Content-Disposition: inline; filename="'.basename($filename).'";');
ob_clean(); 
flush(); 

echo $file;

?>