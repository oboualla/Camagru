<?php
    class Views {
        public function view($pages, $Data) {
            require('./public/views/' . 'header' . '.php');
            $pages = explode(';', $pages);
            foreach ($pages as $page) {
                require('./public/views/' . $page . '.php');
            }
            require('./public/views/' . 'footer' . '.php');
        }
        public function redirect($url) {
            header('Location: ' . __HOSTADDR__ . $url);
        }
    }
?>