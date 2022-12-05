<?php




    class Session {

        function __construct()
        {
            Session::open();
        }

        static public function open(){
            error_reporting(E_ERROR | E_PARSE);
            $status = session_status();
            if($status === PHP_SESSION_NONE){
                session_start();
            }
        }

        public function save(string $id, $val){
            $_SESSION[$id] = $val;
        }

        public function get(string $id) {
            return $_SESSION[$id];
        }

        public function clear() {
            $ids = func_get_args();
            for ($i = 0; $i < count($ids); $i++){
                unset($_SESSION[$ids[$i]]);
            }
            session_unset();
            session_destroy();
        }

    }