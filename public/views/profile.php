<link rel="stylesheet" href="<?php echo __PROJDIR__?>/public/css/profile.css">
<br>
<div id="profile">
    <div id="img">
        <img src="<?php echo __PROJDIR__?>/public/img/users/<?php echo isset($Data['lastpub']) ? $Data['lastpub']['img'] : 'welcome.png';?>">
    </div>
    <div id="info">
        <h4><?php echo 'login       : <h3>' . $Data['userinfo']['login'] . '</h3>';?></h4>
        <h4><?php echo 'email       : <h3>' . $Data['userinfo']['email'] . '</h3>';?></h4>
        <h4><?php echo 'Join le     : <h3>' . date("D M j Y", strtotime($Data['userinfo']['created_dat'])) . '</h3>';?></h4>
        <h4><?php echo 'modifier le : <h3>' . date("D M j Y", strtotime($Data['userinfo']['modif_dat'])) . '</h3>';?></h4>
        <h4><?php echo 'has ' . $Data['userinfo']['npub'] . ' publication';?></h4>  
    </div>
</div>
<?php
    $Data['title'] = 'publication';
?>