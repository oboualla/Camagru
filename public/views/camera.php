<link rel="stylesheet" href="<?php echo __PROJDIR__?>/public/css/camera.css">
    </style>
    <?php echo "<div style=\"margin:auto;text-align:center;\"><h1>" . $Data['title'] . "</h1></div>";?>
    <b id="message"><?php
        echo isset($Data['message']) ? $Data['message'] : '';
        echo isset($_SESSION['Message']) ? $_SESSION['Message'] : '';
        if (isset($_SESSION['Message']))
            unset($_SESSION['Message']);
    ?></b></br>
    </br>
    
    <div class="camera">
        <div class="pub">
            <p id="st">Status :</p>
            <textarea id="pb" placeholder="type something ..."></textarea>
        </div>
            <div>
                <button id="start" class="btn" onclick="Start()">Start</button>
                <button id="stop" class="btn" onclick="Stop()">Stop</button>
        <form action="<?php echo __SERVROOT__?>/camera/save" method="POST" onsubmit="return TakeShot()" enctype="multipart/form-data">
                <input id="imgData" type="hidden" name="img">
                <input id="stick" type="hidden" name="stick">
                <input id="publication" type="hidden" name="pub">
                <input id="takeshot" type="submit" class="btn" name="submit" value="takeShot">
            </div>
        <div id="imgs">
    <?php
        $pubs = $Data['pubs'];
        foreach ($pubs as $pub) {
            echo "<img src=\"" . __PROJDIR__ . '/public/img/users/'.  $pub['img'] . "\"></img>";
        }
    ?>
        </div>
        <div id="video" style="margin: auto;text-align:center;">
            <input type="file" name="imguploaded" id="imguploaded">
            <video autoplay id="vid"></video>
        </div>
        </form>
    <div id="emoji">
    <?php
        $stickrs = scandir(__SERVROOT__ . '/public/img/stickers/');
        foreach ($stickrs as $stick) {
            if ($stick != '.' && $stick != '..') {
            ?>
            <div>
                <input id="<?php echo str_replace('.png', '', $stick);?>" type="checkbox" onclick="incheckboxs(this.id)">
                <img src="<?php echo __PROJDIR__?>/public/img/stickers/<?php echo $stick;?>"></br>
            </div>
    <?php
            }
        }
    ?>
    </div>
    </div>
    <canvas id="canvas" width="640" height="480"></canvas>
<script src="<?php echo __PROJDIR__;?>/public/script/camera.js">
</script>