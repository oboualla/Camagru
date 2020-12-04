function setSessionVars() {
    xhttp = new XMLHttpRequest;

    xhttp.onreadystatechange = () => {
        if (xhttp.status == 200
            && xhttp.readyState == 4) {
                var obj = JSON.parse(xhttp.responseText);
                sessionStorage.setItem("login", obj.login);
                sessionStorage.setItem("email", obj.email);
                sessionStorage.setItem("id", obj.id);
                sessionStorage.setItem("token", obj.token);
        }
    }
    xhttp.open('GET', '/home/session', true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send();
}

setSessionVars();