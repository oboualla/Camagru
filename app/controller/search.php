<?php
    class Search extends views{
        private $SearchMW;

        public function __construct(){
            $this->SearchMW = new SearchMW;
        }

        public function search() {
            $ret = $this->SearchMW->foundSearch();
            parent::view('search;home', ['title' => 'search', 'pubs' => $ret['pubs'], 'users' => $ret['users'], 'message' => $ret['message']]);
        }
    }
?>