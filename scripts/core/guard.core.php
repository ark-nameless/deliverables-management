<?php

    include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/authenticator.core.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/session.core.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/js.core.php';



    class Guard {

        public static $unified_origin = 'dashboard';

        public static function check($role){
            $origin = Guard::$unified_origin;
            $auth = new Auth();
            $session = new Session();

            $current_role = $session->get('role');


            if ($auth->guard($role)){
                return true;
            } else if (is_null($current_role)){
                JS::alert('Not logged in! Please login');
                JS::redirect('login.php');
                exit;
            }
            JS::alert('Incorrect Authority! Redirecting to your appropriate access control');
            JS::redirect("$current_role-{$origin}.php");
            exit;
        }

        public static function intercept($not_login = true) {
            $auth = new Auth();
            $session = new Session();

            $current_role = $session->get('role');

            if (is_null($current_role) === false){
                $role = $session->get('role');
                $origin = Guard::$unified_origin;
                
                JS::redirect("$role-$origin.php");
                exit;
            }
            if ($not_login){
                JS::redirect("login.php");
            }
            return true;
        }
    }