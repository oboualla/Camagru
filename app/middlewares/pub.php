<?php
    class PubMW extends PubModel {
        private $UsersMD;
        private $LikesMD;
        private $CommMW;

        public function __construct(){
            $this->UsersMD = new UsersModel;
            $this->LikesMD = new LikesModel;
            $this->CommMW = new CommentMW;
            parent::__construct();
        }

        public function CheckIfpubExist($pubid) {
            $result = parent::exec_query('SELECT NULL FROM `publication` WHERE `id` = ? LIMIT 1', [$pubid]);
            if (isset($result['data']) && isset($result['data'][0]))
                return (true);
            return (false);
        }

        private function preparePub(&$pubs) {
            foreach ($pubs as $key => $pub) {
                $user = $this->UsersMD->getUsersWhere('WHERE `id` = ? LIMIT 1', [$pub['uid']]);
                $like = $this->LikesMD->getlikesWhere('WHERE `user_id` = ? AND `pub_id` = ?', [$_SESSION['id'], $pub['pubid']]);
                $nlike = $this->LikesMD->getlikesWhere('WHERE `pub_id` = ?', [$pub['pubid']]);
                $ncmnt = $this->CommMW->prepareCommentWhere('`pub_id` = ?', [$pub['pubid']]);
                $pubs[$key]['date'] = date("D M j G:i:s", strtotime($pub['date']));
                $pubs[$key]['like'] = !isset($like['data'][0]) ? 'like' : 'unlike';
                $pubs[$key]['login'] = ($user['success']) ? $user['data'][0]['login'] : '?????';
                $pubs[$key]['nlike'] = ($nlike['success']) ? count($nlike['data']) : 0;
                $pubs[$key]['ncmnt'] = ($nlike['success']) ? count($ncmnt['data']) : 0;
                $pubs[$key]['comment'] = ($nlike['success']) ? $ncmnt['data'] : [];
            }
        }

        public function prepareAllPub(){
            $result = parent::getAllPub();
            if ($result['error'])
                return $result;
            $pubs = $result['data'];
            $this->preparePub($pubs);
            $result['data'] = $pubs;
            return ($result);
        }

        public function preparePubWhere($where, $vars){
            $sql = 'SELECT `subject`, `user_id` AS `uid`,`created_dat` AS `date`, `img_name` AS `img`, `id` AS `pubid` ' . 
            'FROM `publication` ' . $where;
            $result = parent::getPubWhere($where, $vars);
            if ($result['error'])
                return ($result);
            $pubs = $result['data'];
            $this->preparePub($pubs);
            $result['data'] = $pubs;
            return ($result);
        }

        public function InsertPub($Values) {
            if (preg_match('/[\>\<]+/', $Values['subject']))
                return (['success' => false, 'error' => true, 'data' => NULL, 'message' => "your publication contain invalide symbols !!"]);
            return (parent::InsertPub($Values));
        }

        public function deletepub($pubid) {
            $ret = parent::deletepub($pubid);
            if ($ret['error'])
               return ($ret);
            $ret = $this->LikesMD->DeletelikesWhere('WHERE `pub_id` = ?', [$pubid]);
            if ($ret['error'])
                return ($ret);
            $ret = $this->CommMW->deletecommentWhere('WHERE `pub_id` = ?', [$pubid]);
            if ($ret['error'])
                return ($ret);
            return ($ret);
        }

        public function deletepubWhere($where, $vars){
        }
    }
?>