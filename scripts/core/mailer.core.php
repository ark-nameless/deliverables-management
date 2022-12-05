<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/env.core.php';

class Mailer { 

    private $mailer = null;

    public function __construct(){
        $this->mailer = new PHPMailer(true);
    }

    public function sendAccountEmail(string $email, string $username, string $password){ 
        try {
            $source = Env::$env['URL'];
            $name = Env::$env['MAILER_NAME'];
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                           //Enable verbose debug output
            $this->mailer->isSMTP();                                          //Send using SMTP
            $this->mailer->Host       = Env::$env['MAILER_HOST'];                     //Set the SMTP server to send through
            $this->mailer->SMTPAuth   = true;                                 //Enable SMTP authentication
            $this->mailer->Username   = Env::$env['MAILER_USERNAME'];              //SMTP username
            $this->mailer->Password   = Env::$env['MAILER_PASSWORD'];                   //SMTP password
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       //Enable implicit TLS encryption
            $this->mailer->Port       = Env::$env['MAILER_PORT'];                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            $this->mailer->setFrom(Env::$env['MAILER_USERNAME'], Env::$env['MAILER_NAME']);
            $this->mailer->addAddress($email, $username);     //Add a recipient
        
            //Content
            $this->mailer->isHTML(true);                                  //Set email format to HTML
            $this->mailer->Subject = 'Account Details';
            $this->mailer->Body    = "<h2>Welcome to the {$name}!</h2>
                                        <h3>Please use these credentails to login to the system.</h3>
                                        <a href='{$source}'>{$name}&rarr;</a>
                                        <h2>Email</h2>
                                        <h3>{$email}</h3>
                                        <h2>Password</h2>
                                        <h3>{$password}</h3>";
        
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            $msg = Mailer::$mailer->ErrorInfo;
            echo "Message could not be sent. Mailer Error: {$msg}";
            return false;
        }
    }

    public function sendPasswordResetToken(string $email, $token){ 
        try {
            $source = Env::$env['URL'];
            $name = Env::$env['MAILER_NAME'];
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                           //Enable verbose debug output
            $this->mailer->isSMTP();                                          //Send using SMTP
            $this->mailer->Host       = Env::$env['MAILER_HOST'];                     //Set the SMTP server to send through
            $this->mailer->SMTPAuth   = true;                                 //Enable SMTP authentication
            $this->mailer->Username   = Env::$env['MAILER_USERNAME'];              //SMTP username
            $this->mailer->Password   = Env::$env['MAILER_PASSWORD'];                   //SMTP password
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       //Enable implicit TLS encryption
            $this->mailer->Port       = Env::$env['MAILER_PORT'];                                  //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            $this->mailer->setFrom(Env::$env['MAILER_USERNAME'], Env::$env['MAILER_NAME']);
            $this->mailer->addAddress($email, $username);     //Add a recipient
        
            //Content
            Mailer::$mailer->isHTML(true);                                  //Set email format to HTML
            Mailer::$mailer->Subject = 'Password Reset';
            Mailer::$mailer->Body    = "<h2>Welcome to {$name}!</h2>
                                        <h3>Please follow this link for resetting your password</h3>
                                        <a href='{$source}/change-password.php'>Reset Password &rarr;</a>
                                        <h3>{$token}</h3>
                                        ";
        
            Mailer::$mailer->send();
            return true;
        } catch (Exception $e) {
            $msg = Mailer::$mailer->ErrorInfo;
            echo "Message could not be sent. Mailer Error: {$msg}";
        }
    }
}