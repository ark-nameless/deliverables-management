<?php 

    include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/mailer.core.php';


    $mailer = new Mailer();



    $mailer->sendAccountEmail('luminushistoire@gmail.com', 'histoire', 'sample');