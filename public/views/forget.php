<link rel="stylesheet" href="<?php echo __PROJDIR__?>/public/css/forget.css">
<br><div class="loginDiv">
    <h2>Forget</h2></br>
    <?php if (isset($Data['message'])) {
        echo "<h4>" . $Data['message'] . "</h4>";
    }
    ?>
        <form method="POST" action="<?php echo __PROJDIR__ . '/forget'?>">
            <label for="login">Login or email</label></br>
            <input id="login" type="text" name="login" placeholder="login or email"></br>
            <input id="submit" type="submit" name="submit" value="OK"></br>
        </form>
</div>