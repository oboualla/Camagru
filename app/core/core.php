<?php
    define ('__PROJDIR__', '');
    define ('__PATH__', $_SERVER['DOCUMENT_ROOT'] . __PROJDIR__);
    define('__HOSTADDR__', 'http://' . $_SERVER['HTTP_HOST'] . __PROJDIR__);
    define('__DBNAME__', 'CAMAGRU');
    define('__DBUSER__', 'root');
    define('__DBPASSWD__', 'root');
    define('__DBHOST__', 'localhost');
    define ('__SERVROOT__', '.');

    $Dirstorequire = array('views' => __SERVROOT__ . '/app/views/',
    'mail' => __SERVROOT__ . '/app/mail/',
    'database' => __SERVROOT__ . '/app/Databases/',
    'models' => __SERVROOT__ . '/app/models/',
    'middlewares' => __SERVROOT__ . '/app/middlewares/',
    'controller' => __SERVROOT__ . '/app/controller/');

    foreach ($Dirstorequire as $dir) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..' && strstr($file, '.php') && file_exists($dir . $file)){
                require($dir . $file);
            }
        }
    }
?>