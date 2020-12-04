<?php
    class PubModel extends Database{
        public function __construct() {
            if (!isset($_SESSION))
                session_start();
            parent::__construct();
        }
        public function getAllPub(){
            $sql = 'SELECT `subject`, `user_id` AS `uid`,`created_dat` AS `date`, `img_name` AS `img`, `id` AS `pubid` from `publication`';
            return (parent::exec_query($sql, []));
        }
        public function getPubWhere($where, $vars){
            $sql = 'SELECT `subject`, `user_id` AS `uid`,`created_dat` AS `date`, `img_name` AS `img`, `id` AS `pubid` ' . 
            'FROM `publication` ' . $where;
            return (parent::exec_query($sql, $vars));
        }
        public function InsertPub($Values) {
            $sql = 'INSERT INTO `publication` (`subject`, `img_name`, `user_id`) VALUES (?, ?, ?)';
            if (!isset($Values['img'], $Values['uid']))
                return (['success' => false, 'error' => true, 'data' => NULL, 'message' => "Maybe ur image is invalide try Again !!"]);
            return (parent::exec_query($sql, [$Values['subject'], $Values['img'], $Values['uid']]));
        }
        public function deletepub($pubid) {
            $sql = 'DELETE FROM `publication` WHERE `id` = ?';
            return (parent::exec_query($sql, [$pubid]));
        }
        public function deletepubWhere($where, $vars) {
            $sql = 'DELETE FROM `publication` ' . $where;
            return (parent::exec_query($sql, $vars));
        }
    }
?>