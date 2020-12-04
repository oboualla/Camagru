// pagination section {
var limit = 0;
var great_id = 0;

function putpost(post, parent) {
    pubcontainer = document.createElement("div");
    pubcontainer.setAttribute('id', 'pub');

    if (sessionStorage.getItem('login') == post.login) {
        input = document.createElement('input');
        input.setAttribute('class', 'delete');
        input.setAttribute('onclick', 'deletepb(this.id)');
        input.setAttribute('id', 'delete' + post.pubid);
        input.setAttribute('type', 'image');
        input.setAttribute('src', "/public/img/remove.png");
        pubcontainer.appendChild(input);
    }

    divlogin = document.createElement("div");
    pubcontainer.appendChild(divlogin);
    divlogin.setAttribute('id', "loginzone");

    p = document.createElement("p");
    a = document.createElement("a");
    divlogin.appendChild(p);
    divlogin.appendChild(a);
    p.appendChild(a);

    a.setAttribute('href', "/profile/" + post.login);
    a.setAttribute('value', post.login);
    a.innerHTML = post.login;

    divdate = document.createElement("div");
    pubcontainer.appendChild(divdate);
    divdate.setAttribute('id', "datezone");
    p = document.createElement('p');
    divdate.appendChild(p);
    p.setAttribute('style', "font-weight:lighter;");
    p.innerHTML = post.date;

    divpub = document.createElement("div");
    pubcontainer.appendChild(divpub);
    divpub.setAttribute('id', "pubzone");
    p = document.createElement('p');
    strong = document.createElement('strong');
    p.appendChild(strong);
    divpub.appendChild(p);
    strong.innerHTML = post.subject;
    img = document.createElement('img');
    img.setAttribute('src', "/public/img/users/" + post.img);
    divpub.appendChild(img);

    divlike = document.createElement("div");
    pubcontainer.appendChild(divlike);
    divlike.setAttribute('id', "likezone");
    b = document.createElement('b');
    b.setAttribute('style', "margin-left: 3%;");
    b.innerHTML = 'Like by ';
    divlike.appendChild(b);
    b = document.createElement('b');
    b.setAttribute('style', "margin-left: 1%;margin-right:1%;");
    b.setAttribute('id', "nlike" + post.pubid);
    b.innerHTML = post.nlike;
    divlike.appendChild(b);
    b = document.createElement('b');
    b.innerHTML = ' person';
    divlike.appendChild(b);
    input = document.createElement('input');
    input.setAttribute('id', post.pubid);
    input.setAttribute('type', "image");
    input.setAttribute('src', "/public/img/" + post.like + '.png');
    input.setAttribute('onclick', 'like(this.id)');
    divlike.appendChild(input);
    parent.appendChild(pubcontainer);
    parent.appendChild(document.createElement('br'));
    parent.appendChild(document.createElement('br'));
}

function insert_comment(comment, parent, pubid) {
    var subject = comment.subject;
    var login = comment.login;
    var date = comment.date;

    var div = document.createElement('div');
    div.setAttribute('id', 'cmnt' + pubid);
    div.setAttribute('class', 'cmnt');
    var b = document.createElement('b');
    var a = document.createElement('a');
    b.setAttribute('id', 'login');
    a.setAttribute('href', '/profile/' + login);
    a.innerHTML = login;
    b.appendChild(a);
    div.appendChild(b);
    i = document.createElement('i');
    i.setAttribute('id', 'date');
    i.innerHTML = date;
    div.appendChild(i);
    if (sessionStorage.getItem('login') == comment.login) {
        input = document.createElement('input');
        input.setAttribute('class', 'deletecmnt');
        input.setAttribute('onclick', 'deletecmnt(this.id)');
        input.setAttribute('id', 'cmnt' + comment.id);
        input.setAttribute('type', 'image');
        input.setAttribute('src', "/public/img/remove.png");
        div.appendChild(input);
    }
    div.appendChild(document.createElement('br'));
    b = document.createElement('b');
    b.setAttribute('id', 'subject');
    b.innerHTML = subject;
    div.appendChild(b);
    div.appendChild(document.createElement('br'));
    hr = document.createElement('hr');
    hr.setAttribute('class', 'hr');
    parent.appendChild(div);
    parent.appendChild(hr);
}

function insert_commentinput(parent, pubid) {
    var div = document.createElement('div');
    var textarea = document.createElement('textarea');
    var btn = document.createElement('button');

    div.appendChild(textarea);
    div.appendChild(btn);
    div.id = "subcmnt";
    btn.id = pubid;
    // btn.class = "btn";
    btn.innerHTML = "Comment";
    onclickAttr = document.createAttribute('onclick');
    onclickAttr.value = 'comment(this.id)';
    btnclass = document.createAttribute('class');
    btnclass.value = "btn";
    btn.setAttributeNode(btnclass);
    btn.setAttributeNode(onclickAttr);
    textarea.placeholder = "Add a comment";
    textarea.id = 'comment' + pubid;
    parent.appendChild(div);
}

function putcomment(comment, pubid, parent) {
    var i = 0;
    var cmnts = document.createElement('div');
    cmnts.id = "cmnts" + pubid;
    var cclass = document.createAttribute('class');
    cclass.value = 'cmnts';
    cmnts.setAttributeNode(cclass);
    var Allcmnt = document.createElement('div');
    Allcmnt.id = "Allcmnt" + pubid;
    if (comment != false)
    {
        while (comment[i]) {
            insert_comment(comment[i], Allcmnt, pubid);
            i++;
        }
    }
    cmnts.appendChild(Allcmnt);
    insert_commentinput(cmnts, pubid);
    parent.appendChild(cmnts);
    parent.appendChild(document.createElement('br'));
    parent.appendChild(document.createElement('br'));
}

if (window.location.href.search('/search') <= 0) {
    pagination();
}

function pagination() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = () => {
        if (xhttp.status == 200 && xhttp.readyState == 4 && xhttp.responseText != null) {
            var mypubs = JSON.parse(xhttp.responseText);
            if (mypubs == undefined || mypubs == null)
                return ;
            var Allpubs = document.getElementById('ALL_PUBS');
            var i = 0;
            while (mypubs[i]) {
                if (i == 0 && limit == 0)
                    great_id = mypubs[i].pubid;
                putpost(mypubs[i], Allpubs);
                putcomment(mypubs[i].comment, mypubs[i].pubid, Allpubs);
                i++;
            }
        }
    }
    var requestURL = "/publication/get?";
    requestURL = requestURL + 'limit=' + limit;
    if (window.location.href.search('/profile') > 0) {
        var url = window.location.href;
        url = url.split('/');
        var login = url[url.length - 1].split('?');
        login = login[0];
        if (login != undefined && login != null)
            requestURL = requestURL + '&login=' + login;
    }
    xhttp.open("GET", requestURL, true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send();
}

if (window.location.href.search('/search') <= 0)
    setInterval(() => {
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = () => {
            if (xhttp.status == 200 &&
            xhttp.readyState == 4) {
                var Allpubs = document.getElementById('ALL_PUBS');
                if (!Allpubs || Allpubs == undefined)
                    return ;
                var newpost = JSON.parse(xhttp.responseText);
                if (newpost == null || newpost == undefined)
                    return ;
                var newpub = document.createElement('div');
                var i = 0;
                while (newpost[i]) {
                    if (i == 0)
                        great_id = newpost[i].pubid;
                    putpost(newpost[i], newpub);
                    putcomment(newpost[i].comment, newpost[i].pubid, newpub);
                    i++;
                }
                Allpubs.insertBefore(newpub, Allpubs.firstChild);
            }
        }
        var requestURL = '/publication/get?gid=' + great_id;
        if (window.location.href.search('/profile') > 0) {
            var url = window.location.href;
            url = url.split('/');
            var login = url[url.length - 1].split('?');
            login = login[0];
            if (login != undefined && login != null)
                requestURL = requestURL + '&login=' + login;
        }
        xhttp.open('GET', requestURL, true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send();
    }, 3000);

window.onscroll = () => {
    var winscroll = document.body.scrollTop || document.documentElement.scrollTop;
    var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    if (height == winscroll && window.location.href.search('/search') <= 0) {
        limit = limit + 5;
        pagination();
    }
}

function like(btnid) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = () => {
        if (xhttp.status == 200 && xhttp.readyState == 4){
            var nlike = parseInt(document.getElementById('nlike' + btnid).innerHTML);
            var like = document.getElementById(btnid);
            var obj = JSON.parse(xhttp.responseText);

            if (obj.success == true) {
                if (like == null || like == undefined)
                    console.log('something wrong !!');
                var val = document.getElementById(btnid).src;
                if (val.search('/img/like.png') > 0) {
                    document.getElementById('nlike' + btnid).innerHTML = nlike + 1;
                    like.setAttribute('src', '/public/img/unlike.png');
                }
                else {
                    document.getElementById('nlike' + btnid).innerHTML = nlike - 1;
                    like.setAttribute('src', '/public/img/like.png');
                }
            }
            else if (!obj.message)
                console.log('something wrong while insert ur like !!');
            else
                console.log(obj.message);
        }
    }
    xhttp.open('post', '/likes/like', true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("pubid=" + btnid + "&token=" + sessionStorage.getItem('token'));
}

function comment(pubid) {
    var cmnt = document.getElementById('comment' + pubid);

        cmnt = cmnt.value;
        if (cmnt == undefined || cmnt == '' || cmnt.trim(cmnt) == '') {
            console.log('comment invalide !! ');
            return ;
        }
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = () => {
            if (xhttp.status == 200 && xhttp.readyState == 4) {
                var obj = JSON.parse(xhttp.responseText);
                if (obj.success == true) {
                    var parent = document.getElementById('Allcmnt' + pubid);
                    if (parent == null || parent == undefined) {
                        console.log('parent is undefiend');
                        return ;
                    }
                    insert_comment(obj.comment, parent, pubid);
                    document.getElementById('comment' + pubid).value = '';
                }
                else
                    console.log(obj.message);
            }
        }
        xhttp.open('POST', '/comment/postComment');
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send('comnt=' + cmnt + '&pid=' + pubid + "&token=" + sessionStorage.getItem('token'));
}

function deletepb(pubid) {
    var id = pubid.replace("delete", "");
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = () => {
        if (xhttp.status == 200 && xhttp.readyState == 4) {
            var res = xhttp.responseText;
            var obj = JSON.parse(res);
            if (obj.success == true) {
                window.location.href = window.location.href;
            }
            else
                console.log(obj.message);
        }
    }
    xhttp.open('GET', '/publication/remove/' + id + "?" + "token=" + sessionStorage.getItem('token'), true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send();
}

function deletecmnt(cmntid) {
    var id = cmntid.replace('cmnt', '');
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = () => {
        if (xhttp.status == 200 && xhttp.readyState == 4) {
            var obj = JSON.parse(xhttp.responseText);
            if (obj.success == true) {
                window.location.href = window.location.href;
            }
            else
                console.log(obj.message);
        }
    }
    xhttp.open('GET', '/comment/remove?id=' + id + "&token=" + sessionStorage.getItem('token'), true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send();
}