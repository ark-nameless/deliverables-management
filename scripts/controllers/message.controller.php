<?php 

    include_once 'dbcontroller.php';

    include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/env.core.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/controllers/user.controller.php';


    class MessageController {
        private $table_name = 'messages';
        private $connection = null;

        public function __construct(){
            $db = new DBController();
            $this->connection = $db->getConnection();

            $query = "
            ALTER TABLE `messages` ADD FOREIGN KEY (`from`) REFERENCES `users`(`id`) ON DELETE CASCADE;
            ALTER TABLE `messages` ADD FOREIGN KEY (`to`) REFERENCES `users`(`id`) ON DELETE CASCADE;";
            $stmt = $this->connection->prepare($query);
            $result = $stmt->execute();
        }

        public function add($data){
            $prep = array();
            foreach ($data as $k => $v) {
                $v = htmlspecialchars($v);
                $v = trim($v);
                $prep[':' . $k] = $v;
            }

            $query = "INSERT INTO `{$this->table_name}` ( " . implode(', ', array_keys($data)) . ") VALUES (" . implode(', ', array_keys($prep)) . ")";
            $stmt = $this->connection->prepare($query);

            if ($stmt->execute($prep)) {
                return true;
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

        public function newMessage($data){
            $insert = array(
                ':to' => $data['to'],
                ':from' => $data['from'],
                ':contents' => $data['contents'],
            );

            $query = "
                INSERT INTO 
                    {$this->table_name}(`from`, `to`, `contents`) 
                VALUES (:from, :to, :contents);
            ";

            $stmt = $this->connection->prepare($query);

            if ($stmt->execute($insert)) {
                return true;
            }
            return false;
        }

        public function delete($id){
            $this->connection->query('SET FOREIGN_KEY_CHECKS = 0');

            $query = "SET FOREIGN_KEY_CHECKS = 0; DELETE FROM {$this->table_name} WHERE id=:id; ";
            $stmt = $this->connection->prepare($query);
            $result = $stmt->execute([':id' => $id]);

            $delete_messages  = "DELETE FROM messages WHERE `from` = :id OR `to` = :id; SET FOREIGN_KEY_CHECKS = 1; ";
            $stmt = $this->connection->prepare($delete_messages);
            $stmt->execute([':id' => $id]);

            $this->connection->query('SET FOREIGN_KEY_CHECKS = 1');
            if ($result) {
                return true;
            }
            return false;
        }

        public function getContacts($id) {
            $query = "SELECT * 
                FROM users 
                WHERE id <> :id";

            $stmt = $this->connection->prepare($query);
            $stmt->execute([':id' => $id]);
    
            $data = [];
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($data, $row);
                }
            }
            return $data;
        }

        public function getMyMessages($from, $to){
            $query = "SELECT * 
                    FROM messages
                    WHERE messages.to = :to AND messages.from = :from
                    ORDER BY sent_at";

            $stmt = $this->connection->prepare($query);
            $stmt->execute([':to' => $to, ':from' => $from]);
    
            $data = [];
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($data, $row);
                }
            }
            return $data;
        }

        public function getAll() {
            $query = "SELECT * FROM {$this->table_name}";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
    
            $data = [];
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($data, $row);
                }
            }
            return $data;
        }


        public function renderContacts($id, $role='admin'){
            $origin = Env::$env['URL'];

            foreach ($this->getContacts($id) as &$row){
                echo <<<EOL
                    <a href="/$role-messages.php?name={$row['username']}&from={$row['id']}" class="bg-slate-200 drop-shadow-md rounded-xl p-2">{$row['username']}</a>
                EOL;
            }
        }   
        // self = 1; other = 3;
        public function renderMessages($self, $other){

            foreach ($this->getAll() as &$row){
                // echo $self . ' ' . $other . '<br>';
                if ($row['to'] === $self && $row['from'] == $other){
                    echo <<<EOL
                        <div class="flex w-full space-x-2">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8">
                                    <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <div class="self-start w-[80%] bg-slate-200 drop-shadow-md rounded-xl p-2">
                                {$row['contents']}
                            </div>
                        </div>
                        
                    EOL;
                } 
                else if ($row['from'] === $self && $row['to'] == $other){
                    echo <<<EOL
                        <div class="self-end w-[80%] bg-slate-200 drop-shadow-md rounded-xl p-2">{$row['contents']}</div>
                    EOL;
                }
                
            }
        }








    }