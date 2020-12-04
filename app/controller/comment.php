<?php
    class comment extends views {
        private $ComMW;
        private $PubMW;
        private $mail;

        public function __construct(){
            $this->ComMW = new CommentMW;
            $this->PubMW = new PubMW;
            $this->mail = new Mail;
        }

        private function sendMail() {
            $postid = $_POST['pid'];
            $post = $this->PubMW->exec_query('SELECT `user_id` FROM `publication` WHERE `id` = ? LIMIT 1', [$postid]);
            $fuid = $post['data'][0]['user_id'];
            $fuinf = $this->PubMW->exec_query('SELECT `email` FROM `users` WHERE `id` = ? LIMIT 1', [$fuid]);
            if ($fuinf['data'][0]['email'] == $_SESSION['email'])
                return (true);
            if ($this->mail->comment($fuinf['data'][0]['email'], '<strong>' . $_SESSION['login'] . '</strong>' . ': ' . trim($_POST['comnt'])))
                return (true);
            return (false);
        }

        public function postComment() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST'
            && isset($_POST['comnt'], $_POST['pid'], $_SESSION['user_loggued'], $_POST['token'])
            && $_POST['token'] == $_SESSION['token']) {
                if ($this->PubMW->CheckIfpubExist($_POST['pid']) == false) {
                    die('{"success" : false, "error" : true, "message" : "publication id does\'t not exist !!"}');
                }
                $DataInsert = [
                    'pid' => $_POST['pid'],
                    'comnt' => preg_replace('/[\s]+/', ' ', $_POST['comnt']),
                    'uid' => $_SESSION['id']
                ];
                $DataInsert['comnt'] = trim($DataInsert['comnt']);
                $ret = $this->ComMW->prepareInsertComment($DataInsert);
                if ($ret['error'])
                    die (json_encode($ret));
                $obj = new stdClass();
                $obj->success = true;
                $obj->error = false;
                $obj->comment = new stdClass();
                $obj->comment->subject = $DataInsert['comnt'];
                $obj->comment->pubid = $_POST['pid'];
                $obj->comment->login = $_SESSION['login'];
                $obj->comment->date = date("D M j G:i:s");
                
                $cmntid = $this->ComMW->exec_query('SELECT `id` FROM `comment` WHERE (`user_id` = ? AND `pub_id` = ?) ORDER BY `id` DESC LIMIT 1', [$_SESSION['id'], $_POST['pid']]);
                $obj->comment->id = $cmntid['data'][0]['id'];
                
                $puid = $this->ComMW->exec_query('SELECT `user_id` FROM `publication` WHERE `id` = ?', [$_POST['pid']]);
                $puid = $puid['data'][0]['user_id'];
                $notstatus = $this->ComMW->exec_query('SELECT `notstatus` FROM `notification` WHERE `user_id` = ?', [$puid]);
                $notstatus = $notstatus['data'][0]['notstatus'];
                if ($notstatus == 'on')
                    $this->sendMail();
                $myjson = json_encode($obj);
                echo $myjson;
            }
            else if ($_SERVER['REQUEST_METHOD'] == 'POST')
                echo "{\"success\" : false, \"error\" : true, \"message\" : \"you have to login first\"}";
            else
                parent::redirect('/');
        }

        public function remove() {
            if ($_SERVER['REQUEST_METHOD'] == 'GET'
            && !is_array($_GET['id']) && isset($_GET['id'], $_GET['token'])
            && $_GET['token'] == $_SESSION['token']) {
                $cmntid = $_GET['id'];
                $user = $this->ComMW->exec_query('SELECT `user_id` FROM `comment` WHERE `id` = ?', [$cmntid]);
                if ($user['error'])
                    die('{"success" : false, "error" : true, "message" : "Something happen while checking db ' . $user['message'] .'"}');
                if (!isset($user['data'][0]))
                    die('{"success" : false, "error" : true, "message" : "this comment id is not exist"}');
                if ($user['data'][0]['user_id'] != $_SESSION['id'])
                    die('{"success" : false, "error" : true, "message" : "this comment is belong to another user"}');
                $res = $this->ComMW->deletecommentWhere('WHERE `id` = ?', [$cmntid]);
                if ($res['error'])
                    die('{"success" : false, "error" : true, "message" : "Something happen while delete comment from db ' . $user['message'] .'"}');
                die('{"success" : true, "error" : false, "message" : ""}');
            }
            die('{"success" : false, "error" : true, "message" : "Something Wrong with this request !!"}');
        }
    }
?>