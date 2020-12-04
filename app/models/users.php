<?php
    class UsersModel extends Database{
        public function __construct(){
            if (!isset($_SESSION))
                session_start();
            parent::__construct();
        }
        public function getAllusers() {
            $sql = 'SELECT `login`, `email`, `id`, `created_dat`, `modif_dat` FROM `users`';
            return (parent::exec_query($sql, []));
        }
        public function getUsersWhere($where, $vars) {
            $sql = 'SELECT `login`, `email`, `id`, `created_dat`, `modif_dat` FROM `users` ' . $where;
            return (parent::exec_query($sql, $vars));
        }
        public function insertUsers($value) {
            $sql = 'INSERT INTO `users` (`login`, `email`, `passwd`) VALUE (?, ?, ?)';
            return (parent::exec_query($sql, [$value['login'], $value['email'], $value['passwd']]));
        }
    }
?>