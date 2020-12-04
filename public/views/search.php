<link rel="stylesheet" href="<?php echo __PROJDIR__?>/public/css/search.css">
</br></br>
<?php
    if (isset($Data['message'])) {
        ?> <b><?php echo $Data['message'] ; }?></b>
<?php
    if (isset($Data['users'])) {
        ?>
        <div id="users">
        <h3>Users</h3>
<?php
        $users = $Data['users'];
        foreach($users as $user) {
?>
    <div id="user">
        <img src="<?php echo __PROJDIR__?>/public/img/users/<?php echo $user['img']?>"></br>
        <b><a href="<?php echo __PROJDIR__?>/profile/<?php echo $user['login']?>"><?php echo $user['login'];?></a></b>
    </div></br>
<?php
        }
?>
        </div>
<?php
    }
    $Data['title'] = 'publication';
?>
</br>