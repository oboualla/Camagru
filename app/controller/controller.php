<?php
    class Controller {
        public $url = array('controller' => 'home',
                            'method' => NULL, 'data' => NULL);
        public $controller;

        public function __construct()
        {
            if (isset($_GET['url']) && !is_array($_GET['url']))
                $url = $_GET['url'];
            if (isset($url)){
                $url = preg_replace('/[\/]+/', '/', $url);
                $url = explode('/', $url);
                if (file_exists(__SERVROOT__ . '/app/controller/' . $url[0] . '.php')) {
                    $this->url['controller'] = $url[0];
                    $this->url['method'] = isset($url[1]) ? $url[1] : NULL;
                    foreach ($url as $key => $val) {
                        if ($key > 1)
                            $this->url['data'][] = $val;
                    }
                }
                else if (!file_exists(__SERVROOT__ . '/public/views/' . $url[0] . '.php'))
                    header('Location: ' . __HOSTADDR__);
                else {
                    $this->url['method'] = $url[0];
                    foreach ($url as $key => $val) {
                        if ($key > 0)
                            $this->url['data'][] = $val;
                    }
                }
            }
            if (!isset($this->url['method'])) {
                $this->url['method'] = $this->url['controller'];
            }
            $this->controller = new $this->url['controller'];
            if (isset($this->url['method']) && method_exists($this->controller, $this->url['method'])) {
                if (!isset($this->url['data'])){
                    call_user_func([$this->controller, $this->url['method']]);
                }
                else {
                    call_user_func_array([$this->controller, $this->url['method']], $this->url['data']);
                }
            }
            else
                header('Location: ' . __HOSTADDR__);
        }
    }
?>