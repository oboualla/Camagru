<link rel="stylesheet" href="<?php echo __PROJDIR__?>/public/css/signin.css">
<br><div class="signinDiv">
    <h2>Signin</h2></br>
    <?php 
      if (isset($Data['message'])) {
         echo "<h4>" . $Data['message'] . "</h4>";
      }
    ?>
    <form action="<?php echo __PROJDIR__?>/users/signin" method="POST">
        <label for="login">login</label></br>
        <input type="text" name="login" placeholder="login"></br>
        <label for="email">email</label></br>
        <input type="email" name="email" placeholder="email"></br>
        <label for="passwd">password</label></br>
        <input type="password" name="passwd" placeholder="password"></br>
        <label for="rpasswd">retype password</label></br>
        <input type="password" name="rpasswd" placeholder="retype password"></br>
        <input type="submit" name="submit" value="OK"></br>
    </form>
</div>