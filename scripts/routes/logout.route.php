<?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/authenticator.core.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/guard.core.php';

    $auth = new Auth();


    $auth->logout();

    
    Guard::intercept();

?>