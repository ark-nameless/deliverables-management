<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/env.core.php';

class FTPServer {
    private $server;
    private $username;
    private $password;
    private $is_ftp;
    private $save_path;

    private $login_result;
    private $connection;

    function __construct()
    {
        $this->server = Env::$env['FTP_HOSTNAME'];
        $this->username = Env::$env['FTP_USERNAME'];
        $this->password = Env::$env['FTP_PASSWORD'];
        $this->is_ftp = Env::$env['FTP_IS_FTP'];
        $this->save_path = Env::$env['SAVE_DATE'];

        $this->connection = ftp_connect($this->server);
        $this->login_result = ftp_login($this->connection, $this->username, $this->password);
        ftp_pasv($this->connection, true);
    }

    function __destruct()
    {
        ftp_close($this->connection);
    }

    public function save_file($filename, $src)
    {
        $path = $this->save_path . $filename;

        if ($this->is_ftp === false){
            $path = $_SERVER['DOCUMENT_ROOT'] . $path;
            move_uploaded_file($src, $path);
        } else if ($this->is_ftp === true){
            echo 'ftp ' . $path;
            $path = '/home/vol17_2/epizy.com/epiz_33113052' . $path;
            return ftp_put($this->connection, $path, $src, FTP_BINARY);
        }
    }

    public function get_file($filename){
        $filename = $_SERVER['DOCUMENT_ROOT'] . $this->save_path . $filename;
        if ($this->is_ftp === true){
            return ftp_get($this->connection, $filename, $filename, FTP_BINARY);
        } else if ($this->is_ftp === false){
            return file_get_contents($filename);
        }
    }

    public function delete($filename){
        $filename = $_SERVER['DOCUMENT_ROOT'] . $this->save_path . $filename;
        unlink($filename);
    }

    public function getFilePath($filename){
        return $_SERVER['DOCUMENT_ROOT'] . $this->save_path . $filename;
    }
}
