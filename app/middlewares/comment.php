<?php
    class CommentMW extends CommentModel {
        private $UsersModel;

        public function __construct() {
            if (!isset($_SESSION))
                session_start();
            $this->UsersModel = new UsersModel;
            parent::__construct();
        }

        private function prepareDataUser(&$coms) {
            $WhereUser = 'WHERE `id` = ? LIMIT 1';
            foreach ($coms as $key => $com) {
                $login = $this->UsersModel->getUsersWhere($WhereUser, [$com['uid']]);
                $coms[$key]['login'] = ($login['success']) ? $login['data'][0]['login'] : '';
                $coms[$key]['date'] = date("D M j G:i:s", strtotime($com['date']));
            }
        }

        public function prepareAllComment() {
            $result = parent::getAllComment();
            if ($result['error'])
                return ($result);
            $coms = $result['data'];
            $this->prepareDataUser($coms);
            $result['data'] = $coms;
            return ($result);
        }

        public function prepareCommentWhere($where, $vars) {
            $result = parent::getCommentWhere($where, $vars);
            if ($result['error'])
                return ($result);
            $coms = $result['data'];
            $this->prepareDataUser($coms);
            $result['data'] = $coms;
            return ($result);
        }

        public function prepareInsertComment($DataInsert) {
            if ($DataInsert['comnt'] == '' || preg_match('/[\<\>\%]+/', $DataInsert['comnt']))
                return(["success" => false, "error" => true, "message" => ($DataInsert['comnt'] == '') ? "Comment can\'t be empty !!" : "comment has invalide symbols !!"]);
            return parent::InsertComment($DataInsert);
        }

        public function deletecommentWhere($where, $vars) {
            return parent::deletecommentWhere($where, $vars);
        }
    }
?>
