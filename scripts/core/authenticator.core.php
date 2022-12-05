<?php 

    require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/controllers/dbcontroller.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/session.core.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/controllers/user.controller.php';

    class Auth {
        private $connection = null;
        private $session = null;
        private $table_name = 'users';

        public function __construct()
        {
            $db = new DBController();
            $this->connection = $db->getConnection();
            $this-> session = new Session();
        }

        public function attempt($email, $password){
            $email = htmlspecialchars($email);
            $password = htmlspecialchars($password);

            $query = "SELECT * FROM `{$this->table_name}` WHERE email=:email";
            $stmt = $this->connection->prepare($query);

            $stmt->execute([':email' => $email]);

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $row['password'])) {
                    $this->session->save('id', $row['id']);
                    $this->session->save('username', $row['username']);
                    $this->session->save('role', $row['role']);
                    return true;
                }
            }
            return false; 
        }

        public function logout(){
            $this->session->clear('id', 'username', 'role');
        }

        public function guard($role){
            $login_role = $this->session->get('role');
            if ($role == $login_role && !is_null($login_role)){
                return true;
            }
            return false;
        }
        public function check(){
            return isset($_SESSION);
        }
    }