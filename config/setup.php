<?php
    require('./database.php');

    function install_db($db_host, $db_name, $db_uname, $db_password, $options) {
        try {
            $PDO = new PDO('mysql:host=' . $db_host, $db_uname, $db_password, $options);
            // create db if exist
            $stm = $PDO->prepare("CREATE DATABASE IF NOT EXISTS `" .  $db_name .  "`");
            if (!$stm)
                return (['success' => false, 'error' => 'Something Wrong !!']);
            $stm->execute([]);
            // reconnixion to connect with the new database;
            unset($PDO);
            $PDO = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name, $db_uname, $db_password, $options);
            // create table `users`
            $stm = $PDO->prepare('CREATE TABLE IF NOT EXISTS `users` (
                `login` VARCHAR(100) NOT NULL,
                `email` VARCHAR(100) NOT NULL,
                `passwd` VARCHAR(500) NOT NULL,
                `id` INT AUTO_INCREMENT NOT NULL,
                `created_dat` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `modif_dat` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY(`id`)
            );');
            $stm->execute([]);
            //create table `publication`
            $stm = $PDO->prepare('CREATE TABLE IF NOT EXISTS `publication` (
                `id` int AUTO_INCREMENT NOT NULL,
                `subject` VARCHAR(500) NOT NULL,
                `img_name` VARCHAR(100) NOT NULL,
                `user_id` int NOT NULL,
                `created_dat` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `modif_dat` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            )');
            $stm->execute([]);
            //create table `likes`
            $stm = $PDO->prepare('CREATE TABLE IF NOT EXISTS `likes` (
                `id` int AUTO_INCREMENT NOT NULL,
                `user_id` int NOT NULL,
                `pub_id` int NOT NULL,
                `created_dat` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `modif_dat` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY(`id`)
            )');
            $stm->execute([]);
            //create table `comment`
            $stm = $PDO->prepare('CREATE TABLE IF NOT EXISTS `comment` (
                `id` int AUTO_INCREMENT NOT NULL,
                `subject` VARCHAR(500) NOT NULL,
                `user_id` int NOT NULL,
                `pub_id` int NOT NULL,
                `created_dat` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `modif_dat` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            )');
            $stm->execute([]);
            // create table `confirm`
            $stm = $PDO->prepare("CREATE TABLE IF NOT EXISTS `confirm` (
                `id` int AUTO_INCREMENT PRIMARY KEY,
                `login` VARCHAR(100) NOT NULL,
                `key` VARCHAR(10) NOT NULL,
                `type` ENUM('confirm', 'reset') NOT NULL
            )");
            $stm->execute([]);

            // create table `notification`
            $stm = $PDO->prepare("CREATE TABLE IF NOT EXISTS `notification` (
                `id` int AUTO_INCREMENT PRIMARY KEY,
                `user_id` int NOT NULL,
                `notstatus` ENUM('on', 'off') NOT NULL
            )");
            $stm->execute([]);
            return (['success' => true, 'error' => false]);
        }

        catch (PDOException $e) {
            return (['success' => false, 'error' => 'Something going wrong while installation : ' . $e->getMessage()]);
        }
    }

    function drop_db($db_host, $db_name, $db_uname, $db_password, $options) {
        try {
            $PDO = new PDO('mysql:host=' . $db_host, $db_uname, $db_password, $options);
            $stm = $PDO->prepare('DROP DATABASE IF EXISTS `' . $db_name . '`');
            if (!$stm)
                return (['success' => false, 'error' => 'somthing wrong']);
            $stm->execute();
            $imgs = __PATH__ . '/public/img/users/';
            $files = scandir($imgs);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..' && $file != 'welcome.png' && strstr($file, '.png'))
                    unlink($imgs . $file);
            }
            return (['success' => true, 'error' => false]);
        }
        catch (PDOException $e) {
            return (['success' => false, 'error' => 'Something going wrong while deleting : ' . $e->getMessage()]);
        }
    }

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $ret = drop_db($db_host, $db_name, $db_uname, $db_password, $options);
    if ($ret['success'])
        $ret = install_db($db_host, $db_name, $db_uname, $db_password, $options);
    session_destroy();
    session_start();
    $_SESSION['Message'] = !$ret['success'] ? $ret['error'] : 'creating of the database is complete successfully';
    header('location: ' . __HOSTADDR__);
?>