<?php
    class Likes extends LikesModel{
        private $mail;
        private $PubMW;

        public function __construct(){
            $this->mail = new Mail;
            $this->PubMW = new PubMW;
            parent::__construct();
        }

        private function likemail() {
            $postid = $_POST['pubid'];
            $post = $this->PubMW->exec_query('SELECT `user_id` FROM `publication` WHERE `id` = ? LIMIT 1', [$postid]);
            $fuid = $post['data'][0]['user_id'];
            $fuinf = $this->PubMW->exec_query('SELECT `email` FROM `users` WHERE `id` = ? LIMIT 1', [$fuid]);
            if ($fuinf['data'][0]['email'] == $_SESSION['email'])
                return (true);
            if ($this->mail->like($fuinf['data'][0]['email'], $_SESSION['login']))
                return (true);
            return (false);
        }

        public function like() {
            if ($_SESSION['user_loggued']
            && isset($_POST['pubid'], $_POST['token']) && $_POST['token'] == $_SESSION['token']) {
                // check pub is exist
                if (!$this->PubMW->CheckIfpubExist($_POST['pubid'])) {
                    die('{"success" : false, "error" : true, "message" : "publication id does\'t not exist !!"}');
                }
                $sql = 'WHERE `pub_id` = ? AND `user_id` = ? LIMIT 1';
                $res = parent::getlikesWhere($sql, [$_POST['pubid'], $_SESSION['id']]);
                if (isset($res['data'][0])) {
                    // # DELETE likes
                    $sql = "WHERE `user_id` = ? AND `pub_id` = ? LIMIT 1";
                    $ret = parent::DeletelikesWhere($sql, [$_SESSION['id'], $_POST['pubid']]);
                }
                else {
                    // # Insert likes
                    $ret = parent::insertlikes(['user_id' => $_SESSION['id'],
                    'pub_id' => $_POST['pubid']]);
                    $puid = $this->PubMW->exec_query('SELECT `user_id` FROM `publication` WHERE `id` = ?', [$_POST['pubid']]);
                    $puid = $puid['data'][0]['user_id'];
                    $notstatus = $this->PubMW->exec_query('SELECT `notstatus` FROM `notification` WHERE `user_id` = ?', [$puid]);
                    $notstatus = $notstatus['data'][0]['notstatus'];
                    if ($notstatus == 'on')
                        $this->likemail();
                }
                if ($ret['error'])
                   die('{"success" : false, "error" : true}');
                else
                   die('{"success" : true, "error" : false}');
                return ;
            }
            die('{"success" : false, "error" : true, "message" : "you need to login first !!"}');
        }
    }
?>