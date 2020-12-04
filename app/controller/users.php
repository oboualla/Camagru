<?php
    class Users extends Views{
        private $UsersMW;
        private $LikesModel;
        private $mail;
    
        public function __construct() {
            $this->UsersMW = new UsersMW;
            $this->LikesModel = new LikesModel;
            $this->mail = new Mail;
        }

        public function login() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_SESSION['USER_LOGGUED'])
            && isset($_POST['login'], $_POST['passwd'])){
                $Data = array('login' => $_POST['login'], 'passwd' => $_POST['passwd']);
                $ret = $this->UsersMW->auth($Data);
                if ($ret['success'] == true) {
                    parent::redirect('/');
                }
                else {
                    parent::view('login', ['title' => 'login', 'message' => $ret['message']]);
                }
            }
            else if (!isset($_SESSION['user_loggued'])) {
                parent::view('login', ['title' => 'login']);
            }
            else
                parent::redirect('/');
        }

        public function signin() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_SESSION['USER_LOGGUED'])
            && isset($_POST['login'], $_POST['passwd'], $_POST['email'], $_POST['rpasswd'])) {
                $info = array('login' => $_POST['login'],
                            'email' => $_POST['email'],
                            'passwd' => $_POST['passwd'],
                            'rpasswd' => $_POST['rpasswd']);
                $ret = $this->UsersMW->signin($info);
                if ($ret['success'] == true) {
                    $_SESSION['Message'] = 'You must validate ur email';
                    parent::redirect('/');
                }
                else {
                    parent::view('signin', ['title' => 'signin', 'message' => $ret['message']]);
                }
            }
            else if (!isset($_SESSION['user_loggued']))
                parent::view('signin', ['title' => 'signin']);
            else
                parent::redirect('/');
        }

        public function logout() {
            if (isset($_SESSION['user_loggued']))
                session_destroy();
            parent::redirect('/');
        }

        public function validation() {
            if (!is_array($_GET['key']) && !is_array($_GET['login'])
            && isset($_GET['key'], $_GET['login'])) {
                if (preg_match('/[\D]+/', $_GET['key'])) {
                    $_SESSION['Message'] = 'Your key is invalide';
                }
                else {
                    $result = $this->UsersMW->exec_query('SELECT `id` FROM `confirm` WHERE' . 
                    ' (`key` LIKE ? AND `login` LIKE ? AND `type` LIKE ?) LIMIT 1',
                    [$_GET['key'], $_GET['login'], 'confirm']);
                    if (!isset($result['data'][0])) {
                        $_SESSION['Message'] = 'key or login is doesn\'t exist !!';
                    }
                    else {
                        $this->UsersMW->exec_query('DELETE FROM `confirm` WHERE (`key` LIKE ? AND `login` LIKE ? AND `type` LIKE ?) LIMIT 1',
                        [$_GET['key'], $_GET['login'], 'confirm']);
                        $_SESSION['Message'] = 'your account is confirmed successfully !!';
                    }
                }
                parent::redirect('/');
            }
        }
    }
?>