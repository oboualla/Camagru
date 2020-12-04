<?php
    class publication extends PubMW {
        public function remove($id = '-1') {
            if ($id != '-1' && $_SESSION['user_loggued'] && isset($_GET['token'])
            && $_GET['token'] == $_SESSION['token']) {
                $ret = parent::getPubWhere('WHERE `id` = ? LIMIT 1', [$id]);
                if (!isset($ret['data'][0]))
                    die("{\"success\" : false, \"error\" : true, \"message\" : \"" . 'publication id c\'ant be found !!' . "\"}");
                if ($ret['data'][0]['uid'] != $_SESSION['id'])
                    die("{\"success\" : false, \"error\" : true, \"message\" : \"" . 'this pub is belong to another user !!' . "\"}");
                unlink(__SERVROOT__ . '/public/img/users/' . $ret[0]['img']);
                $ret = parent::deletepub($id);
                echo json_encode($ret);
            }
            else
                die("{\"success\" : false, \"error\" : true, \"message\" : \"" . 'you need to login or something missing !!' . "\"}");
        }

        private function pubsto_Jsonobj($pubs) {
            $i = 0;
            foreach ($pubs as $pub) {
                $obj[$i] = new stdClass();
                $obj[$i]->login = $pub['login'];
                $obj[$i]->subject = $pub['subject'];
                $obj[$i]->date = $pub['date'];
                $obj[$i]->pubid = $pub['pubid'];
                $obj[$i]->img = $pub['img'];
                $obj[$i]->like = $pub['like'];
                $obj[$i]->nlike = $pub['nlike'];
                $obj[$i]->ncmnt = $pub['ncmnt'];
                $j = 0;
                foreach ($pub['comment'] as $comment) {
                    $obj[$i]->comment[$j] = new stdClass;
                    $obj[$i]->comment[$j]->login = $comment['login'];
                    $obj[$i]->comment[$j]->date = $comment['date'];
                    $obj[$i]->comment[$j]->subject = $comment['subject'];
                    $obj[$i]->comment[$j]->id = $comment['id'];
                    $j++;
                }
                if (!isset($pub['comment'][0])) {
                    $obj[$i]->comment = false;
                }
                $i++;
            }
            return json_encode($obj);
        }

        public function get() {
            if (isset($_GET['limit']) && is_array($_GET['limit']))
                unset($_GET['limit']);
            if (isset($_GET['login']) && is_array($_GET['login']))
                unset($_GET['login']);
            if (isset($_GET['gid']) && is_array($_GET['gid']))
                unset($_GET['gid']);
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 0;
            $login = isset($_GET['login']) ? $_GET['login'] : 0;
            $gid = isset($_GET['gid']) ? $_GET['gid'] : 0;
            $sqllimit = ' LIMIT ?, 5';
            $sqlorder = ' ORDER BY `created_dat` DESC';
            $sqlwhere = 'WHERE ';
            $sqlquery = '';
            if (isset($_GET['login'])) {
                $user_info = parent::exec_query('SELECT `id` FROM `users` WHERE `login` LIKE ?', [$_GET['login']]);
                if (isset($user_info['data'][0])) {
                    $sqlwhere .= '`user_id` = ?';
                    $vars[] = $user_info['data'][0]['id'];
                }
                else
                    die('"success" : false, "error" : true, message : "login not found"');
            }
            if (isset($_GET['gid'])) {
                $sqlwhere .= ' `id` > ? ';
                $vars[] = $gid;
            }
            if (isset($_GET['gid']) || isset($_GET['login']))
                $sqlquery .= $sqlwhere;
            $vars[] = $limit;
            $sqlquery .= $sqlorder . $sqllimit;
            $pubs = parent::preparePubWhere($sqlquery, $vars);
            $pubs = $pubs['data'];
            echo $this->pubsto_Jsonobj($pubs);
        }
    }
?>