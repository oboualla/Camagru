<?php
    class Setting extends Views{
        private $UsMW;
        private $mail;
        public function __construct(){
            $this->UsMW = new UsersMW;
            $this->mail = new Mail;
        }

        public function setting() {
            if (isset($_SESSION['user_loggued'])) {
                $uinfo = [
                    'login' => $_SESSION['login'],
                    'email' => $_SESSION['email'],
                    'notstatus' => ($_SESSION['notstatus'] == 'on') ? 'true' : 'false'
                ];
                parent::view('setting', ['title' => 'Setting', 'uinfo' => $uinfo]);
            }
            else
                parent::redirect('/');
        }

        private function getconfirmURL($login) {
            $url = __HOSTADDR__ . '/users/validation?login=' . $login . '&';
            $sql = 'SELECT NULL FROM `confirm` WHERE (`key` LIKE ? AND `login` LIKE ? AND `type` LIKE ?) LIMIT 1';
            while (1) {
                $key = rand(1000000000, 9999999999);
                $result = $this->UsMW->exec_query($sql, [$key, $login, 'confirm']);
                if ($result['success'] && !isset($result['data'][0]))
                    break ;
            }
            $sql = 'INSERT INTO `confirm` (`login`, `key`, `type`) VALUES (?, ?, ?)';
            $this->UsMW->exec_query($sql, [$login, $key, 'confirm']);
            $url .= 'key=' . $key;
            return ($url);
        }

        public function update() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST'
            && !empty($_SESSION['user_loggued'])
            && isset($_POST['notstatus'], $_POST['login'], $_POST['email'], $_POST['passwd'], $_POST['rpasswd'], $_POST['token'])
            && $_POST['token'] == $_SESSION['token']) {
                $result = $this->UsMW->update(['login' => $_POST['login'], 'email' => $_POST['email'], 'passwd' => $_POST['passwd'], 'rpasswd' => $_POST['rpasswd']]);
                $notstatus = $_POST['notstatus'];
                if ($notstatus != $_SESSION['notstatus']) {
                    $this->UsMW->exec_query('UPDATE `notification` set `notstatus` = ? WHERE `user_id` = ?',
                    [$notstatus, $_SESSION['id']]);
                    $_SESSION['notstatus'] = $notstatus;
                }
                if ($result['success']) {
                    if (strstr($result['message'], 'email')) {
                        $ConfURL = $this->getconfirmURL($_SESSION['login']);
                        $this->mail->confirmEmail($_SESSION['email'], $ConfURL);
                    }
                    if (strstr($result['message'], 'password') || strstr($result['message'], 'email'))
                        session_destroy();session_start();
                    $result['location'] = '/';
                    $_SESSION['Message'] = $result['message'];
                    $_SESSION['Message'] .= (strstr($result['message'], 'email')) ? '</br>You need to confirm your new email' : '';
                }
                die(json_encode($result));
            }
            else
                die('{"success" : false, "error" : true, "message" : "You need to login first or unexpected request is received !!"}');
        }
    }
?>