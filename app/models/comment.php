<?php
    class CommentModel extends Database {
        public function __construct() {
            if (!isset($_SESSION))
                session_start();
            parent::__construct();
        }
        public function getAllComment() {
            $sql = 'SELECT `subject`, `user_id` AS uid, `pub_id` AS pid, `created_dat` FROM `comment`';
            return (parent::exec_query($sql, []));
        }
        public function getCommentWhere($where, $vars) {
            $sql = 'SELECT `subject`, `user_id` AS uid, `id`, `pub_id` AS pid, `created_dat` AS `date` FROM `comment` WHERE ' . $where;
            return (parent::exec_query($sql, $vars));
        }
        public function InsertComment($DataInsert) {
            $sql = 'INSERT INTO `comment` (`subject`, `user_id`, `pub_id`) VALUES (?, ?, ?)';
            if (!isset($DataInsert['comnt']) || $DataInsert['comnt'] == '')
                return (['success' => false, 'error' => true, 'data' => NULL, 'message' => "Comment can't be empty !!"]);
            return (parent::exec_query($sql, [$DataInsert['comnt'], $DataInsert['uid'], $DataInsert['pid']]));
        }
        public function deletecommentWhere($where, $vars) {
            $sql = 'DELETE FROM `comment` ' . $where;
            return parent::exec_query($sql, $vars);
        }
    }
?>