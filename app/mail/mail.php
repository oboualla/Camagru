<?PHP
    class Mail {
        private $sender = 'oboualla@CAMAGRU.1337';
        private $headers = '';

        private $subject = [
            'registre' => 'Complete your registration into CAMAGRU',
            'like' => 'new like',
            'comment' => 'new comment',
            'emailchange' => 'email is changed',
            'resetpasswd' => 'reset password',
            'confirmEmail' => 'Confirm email'
        ];

        private $messages = [
            'registre' => 'Thanks for registration into CAMAGRU,<br> click the following link to complete your registration :<br>',
            'like' => 'new like from ',
            'comment' => 'new comment from ',
            'emailchange' => 'you have change your email to ',
            'resetpasswd' => 'you can create new password from the following link :<br>',
            'confirmEmail' => 'You have to confirm your new email by clicking on this following link :<br>'
        ];

        public function __construct(){
            $this->headers = 'From : '. $this->sender . "\r\n" .
            'X-Mailer: PHP/' . phpversion() . "\r\n" .
            "Content-Type: text/html; charset=UTF-8\r\n";
        }

        public function confirmEmail($email, $confirmURL) {
            $message = $this->messages['confirmEmail'] . '<a href="' . $confirmURL . '">Confirm email</a>';
            if (mail($email, $this->subject['confirmEmail'], $message, $this->headers))
                return (true);
            return (false);
        }

        public function registre($to, $confURL) {
            $message = $this->messages['registre'] . '<a href="' . $confURL . '">CONFIRM FROM HERE !!</a>';
            if (mail($to, $this->subject['registre'], $message, $this->headers)) {
                return (true);
            }
            return (false);
        }

        public function comment($to, $from) {
            $message = $this->messages['comment'] . $from;
            if (mail($to, $this->subject['comment'], $message, $this->headers)) {
                return (true);
            }
            return (false);
        }

        public function like($to, $from) {
            $message = $this->messages['like'] . $from;
            if (mail($to, $this->subject['like'], $message, $this->headers)) {
                return (true);
            }
            return (false);
        }

        public function emailchange($to, $newemail) {
            $message = $this->messages['emailchange'] . '<strong>' . $newemail . '</strong>.';
            if (mail($to, $this->subject['emailchange'], $message, $this->headers)) {
                return (true);
            }
            return (false);
        }

        public function resetpasswd($to, $URL) {
            $message = $this->messages['resetpasswd'] . '<a href="' . $URL . '">' . 'RESET PASSWORD' . '</a>';
            if (mail($to, $this->subject['resetpasswd'], $message, $this->headers)) {
                return (true);
            }
            return (false);
        }
    }
?>