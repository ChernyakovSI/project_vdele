let setPasVisible = document.getElementById('setPasVisible');
let lblSetPasVisible = document.getElementById('lblSetPasVisible');

let password = document.getElementById('signupform-password');
let passwordA = document.getElementById('signupform-passwordagain');

//Events

setPasVisible.onclick = function(e) {
    if(setPasVisible.checked === true) {
        lblSetPasVisible.innerText = 'Скрыть пароль';
        password.setAttribute('type', 'text');
        passwordA.setAttribute('type', 'text');
    } else {
        lblSetPasVisible.innerText = 'Отобразить пароль';
        password.setAttribute('type', 'password');
        passwordA.setAttribute('type', 'password');
    }
};