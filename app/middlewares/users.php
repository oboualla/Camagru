<?php
    class UsersMW extends UsersModel {
        private $sendmail;
    
        public function __construct(){
            $this->sendmail = new Mail;
            parent::__construct();
        }
        public function auth($Data) {
            // Check data syntax is correct first
            if (preg_match('/[A-Za-z1-9]+@[A-Za-z1-9]+\.[A-Za-z1-9]+/', $Data['login'])
            && preg_match('/[\>\<\%]/', $Data['login'])) {
                return (['success' => false, 'error' => true, 'message' => 'email is invalide']);
            }
            else if (!preg_match('/[A-Za-z1-9]+@[A-Za-z1-9]+\.[A-Za-z1-9]+/', $Data['login'])
            && preg_match('/[\W]+/', $Data['login'])) {
                return (['success' => false, 'error' => true, 'message' => 'login is invalide']);
            }
            $result = parent::exec_query('SELECT NULL FROM `confirm` WHERE (`login` LIKE ? AND `type` LIKE ?) LIMIT 1', [$Data['login'], 'confirm']);
            if ($result['error'] == true)
                return (array('success' => false, 'error' => true, 'message' => $result['message']));
            if (isset($result['data'][0]))
                return (array('success' => false, 'error' => true, 'message' => 'you have to confirm your account'));
            $sql = 'WHERE (`login` LIKE ? OR `email` LIKE ?) AND (`passwd` LIKE ?) LIMIT 1';
            $result = parent::getUsersWhere($sql, [$Data['login'], $Data['login'], hash('whirlpool' ,$Data['passwd'])]);
            if ($result['success'] && isset($result['data'][0])) {
                $_SESSION['user_loggued'] = $result['data'][0]['login'];
                $_SESSION['login'] = $result['data'][0]['login'];
                $_SESSION['email'] = $result['data'][0]['email'];
                $_SESSION['id'] = $result['data'][0]['id'];
                $notstatus = parent::exec_query('SELECT `notstatus` FROM `notification` WHERE `user_id` = ?', [$_SESSION['id']]);
                $_SESSION['notstatus'] = $notstatus['data'][0]['notstatus'];
                return (array('success' => true, 'error' => false, 'message' => NULL));
            }
            return (array('success' => false, 'error' => true, 'message' => 'login or password is incorrect'));
        }

        private function loginisunique($login, &$result) {
            $sql = 'WHERE `login` LIKE ? LIMIT 1';
            $ret = parent::getUsersWhere($sql, [$login]);
            if ($ret['error'] || isset($ret['data'][0])) {
                $result['message'] = isset($ret['data'][0]) ? 'login already exist' : $ret['message'];
                $result['error'] = true;
                $result['success'] = false;
                return (1);
            }
            if (preg_match('/[\W]+/', $login)) {
                $result['message'] = 'login must contain only uppercase, lowercase and numbers only';
                $result['error'] = true;
                $result['success'] = false;
                return (1);
            }
            return (0);
        }

        private function emailisunique($email, &$result) {
            $sql = 'WHERE `email` LIKE ? LIMIT 1';
            $ret = parent::getUsersWhere($sql, [$email]);
            if ($ret['error'] || isset($ret['data'][0])) {
                $result['message'] = isset($ret['data'][0]) ? 'email already exist' : 'Something happen while checking db ' . $ret['message'];
                $result['error'] = true;
                $result['success'] = false;
                return (1);
            }
            if (!preg_match('/[A-Za-z0-9]+\@[A-Za-z0-9]+\.[A-Za-z0-9]+/', $email)) {
                $result = ['success' => false, 'error' => true, 'message' => 'email format error !!'];
                return (1);
            }
            if (preg_match('/[\<\>\%]+/', $email)) {
                $result = ['success' => false, 'error' => true, 'message' => 'email  must not contain symbols like >,<,%!!'];
                return (1);
            }
            return (0);
        }
        // return 0 on success 1 on fail
        private function checkpasswd($Data, &$result) {
            if (strlen($Data['passwd']) < 8) {
                $result['message'] = 'password must contain at least 8 caractere';
            }
            else if ($Data['passwd'] != $Data['rpasswd']) {
                $result['message'] = 'passwords is different !!';
            }
            else if (!preg_match('/[A-Z]+/', $Data['passwd']) || !preg_match('/[a-z]+/', $Data['passwd'])
            || !preg_match('/[1-9]+/', $Data['passwd']) || !preg_match('/[\W\_]+/', $Data['passwd'])) {
                $result['message'] = 'password must contain uppercase, lowercase, number, special caractere !!';
            }
            else
                return (0);
            $result['success'] = false;
            $result['error'] = true;
            return (1);
        }

        private function update_columns($Data, &$result) {
            $sql = 'UPDATE `users` set ';
            $vars = [];
            $passwdhash = hash('whirlpool', $Data['passwd']);
            $usercolums = parent::exec_query('SELECT * FROM `users` WHERE `id` = ? LIMIT 1', [$_SESSION['id']]);
            if ((!isset($Data['login']) || empty($Data['login']) || $usercolums[0]['login'] == $Data['login'])
            && (!isset($Data['email']) || empty($Data['email']) || $usercolums[0]['email'] == $Data['email'])
            && (!isset($Data['passwd']) || empty($Data['passwd']) || $usercolums[0]['passwd'] == $passwdhash)) {
                $result = ['success' => false, 'error' => true, 'message' => 'nothing to update !!'];
                return ($result);
            }
            if (isset($Data['login']) && !empty($Data['login'])
            && $usercolums[0]['login'] != $Data['login']) {
                $sql = $sql . '`login` = ? ';
                $vars[] = $Data['login'];
                $result['message'] = 'login,';
                $_SESSION['login'] = $Data['login'];
                $_SESSION['user_loggued'] = $Data['login'];
            }
            if (isset($Data['email']) && !empty($Data['email'])
            && $usercolums[0]['email'] != $Data['email']) {
                $sql = (count($vars) > 0) ? $sql . ', `email` = ? ' : $sql . '`email` = ? ';
                $vars[] = $Data['email'];
                $result['message'] = $result['message'] . 'email,';
                $this->sendmail->emailchange($_SESSION['email'], $Data['email']);
                $_SESSION['email'] = $Data['email'];
            }

            if (isset($Data['passwd']) && !empty($Data['passwd']) && $usercolums[0]['passwd'] != $passwdhash) {
                $sql = (count($vars) > 0) ? $sql . ', `passwd` = ? ' : $sql . '`passwd` = ? ';
                $vars[] = $passwdhash;
                $result['message'] = $result['message'] . 'password';
            }
            if ($result['success'] == true && count($vars) > 0) {
                $sql = $sql . ', `modif_dat` = CURRENT_TIMESTAMP WHERE `id` = ?' . PHP_EOL;
                $vars[] = $_SESSION['id'];
                $res = parent::exec_query($sql, $vars);
                $result['message'] = ($res['success'] == true) ? 'your ' . $result['message'] . ' has been updated successfully'
                : 'Something going wrong with db while update ur information ' . $res['message'];
            }
            return ($result);
        }

        public function update($Data) {
            $result = ['success' => true, 'error' => false, 'message' => ''];
            $Data['login'] = trim($Data['login']);
            $Data['email'] = trim($Data['email']);
            $Data['passwd'] = trim($Data['passwd']);
            if (empty($Data['login']) && empty($Data['email']) && empty($Data['passwd']))
                return (['success' => false, 'error' => true, 'message' => 'All fields is empty !!']);
            if ($Data['login'] == $_SESSION['login'])
                unset($Data['login']);
            if ($Data['email'] == $_SESSION['email'])
                unset($Data['email']);
            if (!empty($Data['passwd']) && $this->checkpasswd($Data, $result))
                return $result;
            if (isset($Data['login']) && !empty($Data['login'])
            && $this->loginisunique($Data['login'], $result))
                return ($result);
            if (isset($Data['email']) && !empty($Data['email'])
            && $this->emailisunique($Data['email'], $result))
                return ($result);
            $this->update_columns($Data, $result);
            return ($result);
        }

        public function createConfirURL($login) {
            $isexist = [0 => ''];
            while(isset($isexist[0])) {
                $key = rand(1000000000, 9999999999);
                $isexist = parent::exec_query('SELECT NULL FROM `confirm` WHERE `key` LIKE ? LIMIT 1', [$key]);
            }
            parent::exec_query('INSERT INTO `confirm` (`login`, `key`, `type`) VALUES (?, ?, ?)', [$login, $key, 'confirm']);
            return (__HOSTADDR__ . '/users/validation?login=' . $login . '&key=' . $key);
        }

        public function signin($Data) {
            $result = array('success' => true, 'error' => false, 'message' => NULL);

            foreach ($Data as $champ) {
                if (!isset($champ) || empty($champ)){
                    $result['success'] = false;
                    $result['error'] = true;
                    $result['message'] = 'information can\'t be blank !!';
                    return ($result);
                }
            }
            if ($this->loginisunique($Data['login'], $result))
                return ($result);
            if ($this->emailisunique($Data['email'], $result))
                return ($result);
            if ($this->checkpasswd($Data, $result))
                return ($result);
            $passwd = hash('whirlpool', $Data['passwd']);
            if (strlen($passwd) > 500)
                return (array('succuss' => false, 'error' => true, 'message' => 'password to long ..'));
            $res = parent::insertUsers(['login' => $Data['login'], 'passwd' => $passwd, 'email' => $Data['email']]);
            if ($res['error'])
                return ($res);
            $confURL = $this->createConfirURL($Data['login']);
            $userid = parent::getUsersWhere("WHERE `login` LIKE ? limit 1", [$Data['login']]);
            $userid = $userid['data'][0]['id'];
            parent::exec_query('INSERT INTO `notification` (`user_id`, `notstatus`) VALUES (?, ?)', [$userid, 'on']);
            $this->sendmail->registre($Data['email'], $confURL);
            return ($result);
        }
    }
?>