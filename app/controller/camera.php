<?php
    class Camera extends Views{
        private $PubMW;

        public function __construct(){
            $this->PubMW = new PubMW;
        }

        public function camera() {
            if (isset($_SESSION['user_loggued'])) {
                $Data['title'] = 'camera';
                $result = $this->PubMW->getPubWhere('WHERE `user_id` = ? ORDER BY `created_dat` DESC ', [$_SESSION['id']]);
                $Data['pubs'] = $result['success'] ? $result['data'] : [];
                $Data['message'] = $result['error'] ? $result['message'] : NULL;
                parent::view('camera', $Data);
            }
            else {
                $_SESSION['Message'] = 'You need to login to access this page';
                parent::redirect('/');
            }
        }

        private function preparesticker($img, $emoji = "1") {
            if (!file_exists(__SERVROOT__ . '/public/img/stickers/' . $emoji . '.png')) {
                return (false);
            }
            $newimg = imagecreatefromstring($img);
            $stickr = imagecreatefrompng(__SERVROOT__ . '/public/img/stickers/' . $emoji . '.png');
            list($nwidth, $nheight) = getimagesizefromstring($img);
            list($ewidth, $eheight) = getimagesize(__SERVROOT__ . '/public/img/stickers/' . $emoji . '.png');
            $x = floor($nwidth / 6);
            $y = floor($nheight / 8);
            if (imagecopyresampled($newimg, $stickr, $x, $y, 0, 0,
            floor($nwidth / 2) + floor($nwidth / 6), floor($nheight / 2) + floor($nwidth / 6), $ewidth, $eheight) == false){
                return (false);
            }
            imagedestroy($stickr);
            return ($newimg);
        }

        public function save() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['img'], $_SESSION['user_loggued'], $_POST['stick'])) {
                if ($_POST['img'] != '') 
                    $img = base64_decode(str_replace('data:image/png;base64,', '', $_POST['img']));
                else if (isset($_FILES['imguploaded']['tmp_name'])) {
                    $fileType = explode('/', $_FILES['imguploaded']['type']);
                    if ($fileType[0] != 'image'){
                        $_SESSION['Message'] = "image type incorrect";
                        return parent::redirect('/camera');
                    }else if ($fileType[1] != 'jpeg' && $fileType[1] != 'png') {
                        $_SESSION['Message'] = 'only images type jpeg/png';
                        return parent::redirect('/camera');
                    }
                    else
                        $img = file_get_contents($_FILES['imguploaded']['tmp_name']);
                }
                else
                    die("{\"success\" : false, \"error\" : true, \"message\" : \"" . "there is no image received !!" . "\"}");
                $imgname = $_SESSION['login'] . '000';
                $format = '.png';
                while (file_exists('./public/img/users/' . $imgname . $format)) {
                    $imgname = $_SESSION['login'] . rand(0, 1000);
                }
                $newimg = $this->preparesticker($img, $_POST['stick']);
                if ($newimg == false) {
                    $_SESSION['Message'] = "failed to create new image !!";
                    return parent::redirect('/camera');
                }
                imagepng($newimg, './public/img/users/' . $imgname . $format);
                imagedestroy($newimg);
                $ret = $this->PubMW->InsertPub(
                ['subject' => trim($_POST['pub']), 'uid' => $_SESSION['id'], 'img' => $imgname . $format]);
                $_SESSION['Message'] = !empty($ret['message']) ? $ret['message'] : 'your image is saved successfully';
                parent::redirect('/camera');
            }
            else {
                $_SESSION['Message'] = "Something Wrong, maybe request POST missing some vars !!";
                parent::redirect('/camera');
            }
        }
    }
?>