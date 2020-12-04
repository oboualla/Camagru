<?php
    class LikesModel extends Database {
        public function __construct(){
            if (!isset($_SESSION))
                session_start();
            parent::__construct();
        }
        public function getAlllikes() {
            $sql = 'SELECT * from `likes`';
            return ($likes = parent::exec_query($sql, []));
        }
        public function getlikesWhere($where, $vars) {
            $sql = 'SELECT * from `likes` ' . $where;
            return (parent::exec_query($sql, $vars));
        }
        public function insertlikes($value) {
            $sql = 'INSERT INTO `likes` (`user_id`, `pub_id`) VALUES (?, ?)';
            return (parent::exec_query($sql, [$value['user_id'], $value['pub_id']]));
        }
        public function DeletelikesWhere($where, $vars) {
            $sql = 'DELETE FROM `likes` ' . $where;
            return (parent::exec_query($sql, $vars));
        }
    }
?>