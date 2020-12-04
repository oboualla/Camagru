login = document.getElementById("un");
    email = document.getElementById("em");
    passwd = document.getElementById("pass");
    rpasswd = document.getElementById("rpass");
    notstatus = document.getElementById("notstatus");

    document.getElementById("submit").onclick = () => {
        newlogin = login.value;
        newemai = email.value;
        newmdps = passwd.value;
        rmdps = rpasswd.value;
        notst = (notstatus.checked) ? 'on' : 'off';
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = () => {
            if (xhttp.status == 200 && xhttp.readyState == 4) {
                obj = JSON.parse(xhttp.responseText);
                if (obj.success == true) {
                    if (obj.location)
                        window.location.href = '' + obj.location;
                    else {
                        document.getElementById('message').innerHTML = obj.message;
                        login.value = newlogin;
                        email.value = newemai;
                        passwd.value = '';
                        rpasswd.value = '';
                    }
                }
                else {
                    document.getElementById('message').innerHTML = obj.message;
                }
            }
        }
        xhttp.open("POST", "/setting/update", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("login=" + newlogin + "&email=" + newemai + "&passwd=" + newmdps + '&rpasswd=' + rmdps + '&notstatus=' + notst + "&token=" + sessionStorage.getItem('token'));
}