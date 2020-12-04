<link rel="stylesheet" href="<?php echo __PROJDIR__?>/public/css/home.css">
<div style="margin: auto;text-align: center;">
    <h1><?php echo $Data['title']?></h1>
    <?php
        if (isset($_SESSION['Message'])) {
            echo "<h4>" . $_SESSION['Message'] . "<h4>";
            unset($_SESSION['Message']);
        }
    ?>
</div>
<div id="ALL_PUBS">
</div>
<?php
    if (isset($Data['pubs'])) {
        $pubs = $Data['pubs'];
        foreach ($pubs as $pub) {
            ?>
        <div id="pub">
        <?php if (isset($_SESSION['id']) && $_SESSION['id'] == $pub['uid']) {
            echo "<input class=\"delete\" id=\"delete" . $pub['pubid'] . "\" type=\"image\" src=\"" . __PROJDIR__ . "/public/img/remove.png\" onclick=\"deletepb(this.id)\">";
        }?>
            <div id="loginzone">
                <p><a href="<?php echo __PROJDIR__?>/profile/<?php echo $pub['login'];?>"><?php echo $pub['login'];?></a></p>
            </div>
            <div id="datezone">
                <p style="font-weight:lighter;"><?php echo $pub['date'];?></p></br>
            </div>
            <div id="pubzone">
                <p><strong><?php echo $pub['subject'];?></strong></p>
                <img src="<?php echo __PROJDIR__?>/public/img/users/<?php echo $pub['img'];?>"></img>
            </div>
            <div id="likezone">
                <b style="margin-left: 3%;">Like by </b>
                <b style="margin-left: 1%;margin-right:1%;" id="nlike<?php echo $pub['pubid'];?>"><?php echo $pub['nlike'] .  ' ';?></b>
                <b> person</b>
                <input id="<?php echo $pub['pubid'];?>" type="image" src="<?php echo __PROJDIR__?>/public/img/<?php echo $pub['like'] . '.png';?>" onclick="like(this.id)">
            </div>
        </div>
            <?php
            if (isset($pub['comment'])) {
                $comments = $pub['comment'];
            ?>
            </br>
            <div id="cmnts<?php echo $pub['pubid'];?>" class="cmnts">
            <?php
                foreach ($comments as $comment) {
            ?>
                <div id="cmnt<?php echo $pub['pubid'];?>" class="cmnt">
                    <b id="login"><a href="<?php echo __PROJDIR__?>/profile/<?php echo $comment['login'];?>"><?php echo $comment['login'];?></a></b>
                    <i id="date"><?php echo $comment['date'];?></i></br>
                    <b id="subject"><?php echo $comment['subject'];?></b>
                </div>
                <hr class="hr">
            <?php
                }
            ?>
                <div id="subcmnt">
                    <textarea id="comment<?php echo $pub['pubid'];?>" placeholder="Add a comment"></textarea>
                    <button id="<?php echo $pub['pubid'];?>" class="btn" onclick="comment(this.id)">Comment</button>
                </div>
            </div>
            <?php
                }
                ?>
            </br></br>
            <?php
            }
        }
?>
<script src="<?php echo __PROJDIR__;?>/public/script/home.js">
</script>