let setPasVisible = document.getElementById('setPasVisible');
let lblSetPasVisible = document.getElementById('lblSetPasVisible');
let setRememberMe = document.getElementById('setRememberMe');

let password = document.getElementById('loginform-password');
let rememberMe = document.getElementById('loginform-rememberme');


$(document).ready( function() {
    rememberMe.hidden = true;
    rememberMe.parentElement.hidden = true;

    setRememberMe.checked = rememberMe.checked;
});

//Events

setPasVisible.onclick = function(e) {
    if(setPasVisible.checked === true) {
        lblSetPasVisible.innerText = 'Скрыть пароль';
        password.setAttribute('type', 'text');
    } else {
        lblSetPasVisible.innerText = 'Отобразить пароль';
        password.setAttribute('type', 'password');
    }
};

setRememberMe.onclick = function(e) {
    rememberMe.checked = setRememberMe.checked;
};