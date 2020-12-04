<?php
    class SearchMW extends Database{
        private $models;
        private $id_cf = '';
        private $id_pf = '';
        private $id_uf = '';
    
        public function __construct(){
            $this->models = array('users' => new UsersModel,
            'pubs' => new PubModel, 'comment' => new CommentModel, 'pubsMW' => new PubMW);
        }

        private function insertusers(&$found, $users) {
            foreach ($users as $user) {
                if (!strstr($this->id_uf, strval($user['id']))) {
                    $lastpubs = $this->models['pubsMW']->preparePubWhere(
                    'WHERE `user_id` LIKE ? ORDER BY `created_dat` DESC LIMIT 1', [$user['id']]);
                    $found[] = [
                            'login' => $user['login'],
                            'img' => isset($lastpubs['data'][0]) ? $lastpubs['data'][0]['img'] : 'welcome.png'
                        ];
                    $this->id_uf .= ';' . $user['id'];
                }
            }
        }

        private function insertpubs(&$found, $pubs) {
            foreach ($pubs as $pub) {
                if (!strstr($this->id_pf, strval($pub['pubid']))) {
                    $res = $this->models['pubsMW']->preparePubWhere(
                        'WHERE `id` = ? LIMIT 1', [$pub['pubid']]);
                    $found[] = ($res['success']) ? $res['data'][0] : [];
                    $this->id_pf .= ';' . $pub['pubid'];
                }
            }
        }

        private function insertcomments(&$found, $comments) {
            foreach ($comments as $comment) {
                if (!strstr($this->id_cf, strval($comment['id']))
                && !strstr($this->id_pf, strval($comment['pid']))) {
                    $res = $this->models['pubsMW']->preparePubWhere(
                    'WHERE `id` = ? LIMIT 1', [$comment['pid']]
                    );
                    $found[] = ($res['success']) ? $res['data'][0] : [];
                    $this->id_cf .= ';' . $comment['id'];
                    $this->id_pf .= ';' . $comment['pid'];
                }
            }
        }

        public function foundSearch() {
            if (isset($_GET['search']) && !is_array($_GET['search'])) {
                //special trim for search input
                $search = trim($_GET['search']);
                $search = preg_replace('/[\s]+/', ' ', $search);
                // make words from search input
                $words = explode(' ', $search);

                $sql_users = 'WHERE `login` LIKE ? OR `email` LIKE ?';
                $sql_pubs = 'WHERE `subject` LIKE ?';
                $sql_comment = '`subject` LIKE ?';
                $usersfound = NULL;
                $pubsfound = NULL;
                foreach ($words as $word) {
                    $data  = $this->models['users']->getUsersWhere($sql_users, [$word, $word]);
                    if ($data['success']) {
                        isset($data['data'][0]) ? $this->insertusers($usersfound, $data['data']) : 0;
                    }
                    $data = $this->models['pubs']->getPubWhere($sql_pubs, ['%' . $word . '%']);
                    if ($data['success']) 
                        isset($data['data'][0]) ? $this->insertpubs($pubsfound, $data['data']) : 0;
                    $data = $this->models['comment']->getCommentWhere($sql_comment, ['%' . $word . '%']);
                    if ($data['success'])
                        isset($data['data'][0]) ? $this->insertcomments($pubsfound, $data['data']) : 0;
                }
                return (['users' => $usersfound, 'pubs' => $pubsfound, 'message' => $data['message']]);
            }
        }
    }
?>