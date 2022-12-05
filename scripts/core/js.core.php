<?php


    class JS {

        static public function redirect(string $location){
            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

            echo "<script>window.location.replace('$actual_link/{$location}');</script>";
        }

        static public function history(int $history){
            $history *= -1;
            echo("<script>location.href='javascript:history.go({$history})';</script>");
        }

        static public function alert(string $prompt){
            echo "
                <script>alert(\"$prompt\");</script>
            ";
        }
    }