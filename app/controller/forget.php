<?php
    class Forget extends Views{
        private $db;
        private $mail;

        public function __construct(){
            $this->db = new Database;
            $this->mail = new Mail;
        }

        public function createForgetURL($login) {
            $isexist = [0 => ''];
            while(isset($isexist[0])) {
                $key = rand(1000000000, 9999999999);
                $isexist = $this->db->exec_query('SELECT NULL FROM `confirm` WHERE `key` LIKE ? LIMIT 1', [$key]);
            }
            $this->db->exec_query('INSERT INTO `confirm` (`login`, `key`, `type`) VALUES (?, ?, ?)', [$login, $key, 'reset']);
            return (__HOSTADDR__ . '/forget?login=' . $login . '&key=' . $key);
        }

        public function forget() {
            if ($_SERVER['REQUEST_METHOD'] == 'GET' && !is_array($_GET['login'])
            && !is_array($_GET['key']) && !isset($_GET['login'], $_GET['key'])
            && !isset($_SESSION['user_loggued'])) {
                parent::view('forget', ['message' => 'enter your information']);
            }
            else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_SESSION['user_loggued'])) {
                if (!isset($_POST['login']) || empty($_POST['login']))
                    parent::view('forget', ['message' => 'you must set login or email !?']);
                // search by login, email
                $sql = 'SELECT `email`, `login` FROM `users` WHERE (`login` LIKE ? OR `email` LIKE ?)';
                $result = $this->db->exec_query($sql, [$_POST['login'], $_POST['login']]);
                if ($result['success'] && isset($result['data'][0])) {
                    $message = 'We have sent you a mail, visit your inbox';
                    $URL = $this->createForgetURL($result['data'][0]['login']);
                    $this->mail->resetpasswd($result['data'][0]['email'], $URL);
                }
                else
                    $message = 'No users have this login or email';
                parent::view('forget', ['message' => $message]);
            }
            else if (!is_array($_GET['login']) && !is_array($_GET['key'])
            && isset($_GET['login'], $_GET['key']) && !isset($_SESSION['user_loggued'])) {
                $sql = 'SELECT NULL FROM `confirm` WHERE (`login` LIKE ? AND `key` LIKE ? AND `type` LIKE ?) LIMIT 1';
                $result = $this->db->exec_query($sql, [$_GET['login'], $_GET['key'], 'reset']);
                if ($result['success'] && isset($result['data'][0]) && !preg_match('/[^0-9]+/', $_GET['key'])) {
                    $sql = 'DELETE FROM `confirm` WHERE (`login` LIKE ? AND `key` LIKE ? AND `type` LIKE ?) LIMIT 1';
                    $this->db->exec_query($sql, [$_GET['login'], $_GET['key'], 'reset']);
                    $sql = 'SELECT `email`, `login`, `id` FROM `users` WHERE `login` LIKE ? LIMIT 1';
                    $result = $this->db->exec_query($sql, [$_GET['login']]);
                    $_SESSION['user_loggued'] = $result['data'][0]['login'];
                    $_SESSION['login'] = $result['data'][0]['login'];
                    $_SESSION['email'] = $result['data'][0]['email'];
                    $_SESSION['id'] = $result['data'][0]['id'];
                    $_SESSION['Message'] = 'You are login Successfully, please change to a new password from Setting';
                    parent::redirect('/');
                }
                else {
                    $_SESSION['Message'] = 'This key doesn\'t exist';
                    parent::redirect('/');
                }
            }
            else {
                $_SESSION['Message'] = 'You have to logout before visit this page';
                parent::redirect('/');
            }
        }
    }
?>