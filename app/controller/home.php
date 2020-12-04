<?php
    class Home extends Views{
        private $PubMW;
        private $UsersMD;

        public function __construct(){
            $this->PubMW = new PubMW;
            $this->UsersMD = new UsersModel;
        }

        public function home() {
            parent::view('home', ['title' => 'home', 'pubs' => NULL]);
        }

        public function profile($user = '') {
            if ($user == '')
                parent::redirect('/');
            $userinfo = $this->UsersMD->getUsersWhere('WHERE `login` LIKE ? LIMIT 1', [$user]);
            if (!isset($userinfo['data'][0])) {
                $_SESSION['Message'] = 'user not found !!';
                parent::redirect('/');
            }
            $userinfo = $userinfo['data'][0];
            $pubs = $this->PubMW->preparePubWhere("WHERE `user_id` = ? ORDER BY `created_dat` DESC", [$userinfo['id']]);
            $userinfo['npub'] = count($pubs['data']);
            $Data = [
                'title' => $userinfo['login'], 'userinfo' => $userinfo, 'lastpub' => (isset($pubs['data'][0])) ? $pubs['data'][0] : NULL
            ];
            parent::view('profile;home', $Data);
        }

        public function about() {
            parent::view('about', ['title' => 'About']);
        }

        public function session() {
            if (!isset($_SESSION))
                session_start();
            $info = new stdClass();
            $info->login = (isset($_SESSION['login'])) ? $_SESSION['login'] : '';
            $info->email = (isset($_SESSION['email'])) ? $_SESSION['email'] : '';
            // $info->token = (isset($_SESSION['token'])) ? $_SESSION['token'] : '';
            $info->id = (isset($_SESSION['id'])) ? $_SESSION['id'] : '';
            if (isset($_SESSION['login'])) {
                $_SESSION['token'] = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 16);
                $info->token = $_SESSION['token'];
            }
            else
                $info->token = '';
            echo json_encode($info);
        }
    }
?>
