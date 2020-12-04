<link rel="stylesheet" href="<?php echo __PROJDIR__?>/public/css/login.css">

<br><div class="loginDiv">
    <h2>Login</h2></br>
    <?php if (isset($Data['message'])) {
        echo "<h4>" . $Data['message'] . "</h4>";
    }
    ?>
        <form method="POST" action="<?php echo __PROJDIR__ . '/users/login'?>">
            <label for="login">Login or email</label></br>
            <input id="login" type="text" name="login" placeholder="login or email"></br>
            <label for="passwd">Password</label></br>
            <input id="passwd" type="password" name="passwd" placeholder="password"></br>
            <input id="submit" type="submit" name="submit" value="OK"></br>
        </form>
        forget ur passwd ? <a href="<?php echo __PROJDIR__ . '/forget' ?>">Click here</a></br>
</div>