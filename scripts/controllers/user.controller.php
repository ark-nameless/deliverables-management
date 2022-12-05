<?php 

    include_once 'dbcontroller.php';
    include_once 'faculty.controller.php';


    class UserController {
        private $table_name = 'users';
        private $table_link_faculty = 'faculties';
        private $table_link_admin = 'faculties';
        private $connection = null;

        public function __construct(){
            $db = new DBController();
            $this->connection = $db->getConnection();
        }

        private function registerFaculty($data){
            $data['user_id'] = $this->connection->lastInsertId();
            $prep = array();
            foreach ($data as $k => $v) {
                $v = htmlspecialchars($v);
                $prep[':' . $k] = $v;
            }

            $query = "INSERT INTO `{$this->table_link_faculty}` ( " . implode(', ', array_keys($data)) . ") VALUES (" . implode(', ', array_keys($prep)) . ")";
            $stmt = $this->connection->prepare($query);

            if ($stmt->execute($prep)) {
                return true;
            }
            return false;
        }

        private function registerAdmin($data){
            $data['user_id'] = $this->connection->lastInsertId();
            $prep = array();
            foreach ($data as $k => $v) {
                $v = htmlspecialchars($v);
                $prep[':' . $k] = $v;
            }

            $query = "INSERT INTO `{$this->table_link_admin}` ( " . implode(', ', array_keys($data)) . ") VALUES (" . implode(', ', array_keys($prep)) . ")";
            $stmt = $this->connection->prepare($query);

            if ($stmt->execute($prep)) {
                return true;
            }
            return false;
        }

        public function registerUser($credentials, $user_info){
            $prep = array();
            foreach ($credentials as $k => $v) {
                $v = htmlspecialchars($v);
                if ($k == 'password') $v = password_hash($v, PASSWORD_DEFAULT);
                $prep[':' . $k] = $v;
            }

            $query = "INSERT INTO `{$this->table_name}` ( " . implode(', ', array_keys($credentials)) . ") VALUES (" . implode(', ', array_keys($prep)) . ")";
            $stmt = $this->connection->prepare($query);

            if ($stmt->execute($prep)) {
                $info_register = false;
                if (in_array('faculty', $credentials, true)){
                    $info_register = $this->registerFaculty($user_info);
                } else if (in_array('admin', $credentials, true)){
                    $info_register = $this->registerAdmin($user_info);
                }
                return true && $info_register;
            }
            return false;
        }

        public function edit($id, $data){ 
            $set_query = '';
            $prep = [];
            $i = 0;
            foreach ($data as $k => $v) {
                $v = htmlspecialchars($v);
                $prep[':' . $k] = $v;
                $set_query .= "$k=:$k";
                if (++$i < count($data)){
                    $set_query .= ', ';
                }
            }

            $query = "UPDATE `{$this->table_name}` SET $set_query WHERE id=:id";
            $stmt = $this->connection->prepare($query);
            $prep[':id'] = $id;

            if ($stmt->execute($prep)) {
                return true;
            }
            return false;
        }

        public function delete($id){
            $query = "DELETE FROM {$this->table_name} WHERE id=:id";
            $stmt = $this->connection->prepare($query);

            if ($stmt->execute([':id' => $id])) {
                return true;
            }
            return false;
        }



    }