video = document.getElementById('vid');
document.getElementById('1').checked = true;
stickSelectd = 1;

function incheckboxs(id) {
    var i = 1;
    var stick;

    while ((stick = document.getElementById(i))) {
        if (id != i)
            stick.checked = false;
        i = i + 1;
    }
    if (document.getElementById(id).checked == false) {
        document.getElementById('1').checked = true;
        stickSelectd = 1;
    }
    else
        stickSelectd = id;
}


function Start() {
    if (navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({video : true}).then((stream) => {
            video.srcObject = stream;
        });
    }
    else
        console.log('ur navigator does not support getUserMedia !!');
}

function Stop() {
    if (video.srcObject)
        video.srcObject = undefined;
}

var imgDataElem = document.getElementById('imgData');
var stickElem = document.getElementById('stick');
var pubElem = document.getElementById('publication');
var imguploaded = document.getElementById('imguploaded');

function TakeShot() {
    if (video.srcObject || imguploaded.value) {
        if (!imguploaded.value) {
            canvas = document.getElementById('canvas');
            gtc = canvas.getContext('2d');
            gtc.drawImage(video, 0, 0);
            imgData = canvas.toDataURL('image/png');
            gtc.clearRect(0, 0, canvas.width, canvas.height);
            imgDataElem.setAttribute("value", imgData);
        }
        pb = document.getElementById('pb').value;
        stickElem.setAttribute("value", stickSelectd);
        pubElem.setAttribute("value", pb);
        return (true);
    }
    else {
        console.log('you need to use Webcam to take a shot or upload a image');
        return (false);
    }
}
