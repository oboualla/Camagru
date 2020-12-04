<link rel="stylesheet" href="<?php echo __PROJDIR__?>/public/css/setting.css">
</br></br>
<div id="setting" class="loginDiv">
    <div>
        <h4 id="message">change any field u want and click OK !!</h4>
    </div>
    <div>
        <strong> notification via email : </strong><input id="notstatus" type="checkbox" name="notstatus" <?php if ($Data['uinfo']['notstatus'] == 'true') echo "checked=\"true\"";?>>
    </div>
    <div>
       <strong>login :</strong> <input id="un" type="text" value="<?php echo $Data['uinfo']['login'];?>">
    </div>
    <div>
        <strong>email :</strong><input id="em" type="email" value="<?php echo $Data['uinfo']['email'];?>">
    </div>
    <div>
        <strong>new password :</strong><input id="pass" type="password" placeholder="enter a new mdps"></br>
        <strong>retype password :</strong><input id="rpass" type="password" placeholder="enter a new mdps">
    </div>
    <div>
        <input id="submit" type="submit" value="Change it">
    </div>
</div>
<script src="<?php echo __PROJDIR__?>/public/script/setting.js">
</script>