<!DOCTYPE html>
<head>
    <title><?php echo $Data['title']?></title>
    <link rel="stylesheet" href="<?php echo __PROJDIR__?>/public/css/header.css">
    <link rel="shortcut icon" href="<?php echo __PROJDIR__?>/favicon.ico" type="image/x-icon"/>
</head>
<body>
<?php
        if (!isset($_SESSION['user_loggued']))
        {
    ?>
    <div class="topnav-out">
        <a class="active" href="./">CAMAGRU</a>
        <a href="<?php echo __PROJDIR__ . '/users/login';?>">LOGIN</a>
        <a href="<?php echo __PROJDIR__ . '/users/signin';?>">SIGNIN</a>
        <a href="<?php echo __PROJDIR__ . '/about';?>">ABOUT</a>
    </div>
    <?php
        }
        else {
    ?>
    <div class="topnav-in">
        <a class="active" href="<?php echo __PROJDIR__?>/">CAMAGRU</a>
        <a href="<?php echo __PROJDIR__?>/profile/<?php echo $_SESSION['login'];?>">PROFILE</a>
        <a href="<?php echo __PROJDIR__?>/camera">TakeShot</a>
        <a href="<?php echo __PROJDIR__  . '/setting'?>">Setting</a>
        <a class="inactive" href="<?php echo __PROJDIR__ . '/users/logout';?>"><?php echo $_SESSION['login'];?></a>
    </div>
    <?php
    }
    ?>
    </br>
    <div id="searchContainer">
        <h4>Search on Camagru .. </h4>
        <textarea id="searchinput" placeholder="type something"></textarea></br>
        <input type="submit" id="searchsubmit" value="OK">
    </div>
<script src="<?php echo __PROJDIR__?>/public/script/header.js">
</script>
<script src="<?php echo __PROJDIR__ ;?>/public/script/session.js"></script>