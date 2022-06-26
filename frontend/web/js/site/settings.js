let divParamID = document.getElementById('paramID');
let divParamFinUserColor = document.getElementById('divParamFinUserColor');
let divParamGenSubscribeNews = document.getElementById('divParamGenSubscribeNews');

let tabMain = document.getElementById('tab-main');
let tabSys = document.getElementById('tab-sys');
let tabFin = document.getElementById('tab-fin');
let tabCon = document.getElementById('tab-con');

let contentMain = document.getElementById('content-main');
let contentSys = document.getElementById('content-sys');
let contentFin = document.getElementById('content-fin');
let contentCon = document.getElementById('content-con');

let divB_fin_UserColor = document.getElementById('b_fin_UserColor');
let divB_gen_SubscribeNews = document.getElementById('b_gen_SubscribeNews');

let btnCancel = document.getElementById('button-cancel');
let btnSave = document.getElementById('button-save');

let thisData = {
    'id_user' : 0,
    'b_gen_SubscribeNews' : 0,

    'b_fin_UserColor' : 0,
};

let IsModified = false;
let thisDataBefore = {
    'b_gen_SubscribeNews' : 0,

    'b_fin_UserColor' : 0,
};

let numTab = 0;
if (tabMain.classList.contains('btn-active')) {
    numTab = 1;
}
if (tabSys.classList.contains('btn-active')) {
    numTab = 2;
}
if (tabFin.classList.contains('btn-active')) {
    numTab = 3;
}
if (tabCon.classList.contains('btn-active')) {
    numTab = 4;
}

$(document).ready( function() {

    if(divParamID.innerText) {
        thisData['id_user'] = Number(divParamID.innerText);
        thisData['b_gen_SubscribeNews'] = Number(divParamGenSubscribeNews.innerText);
        thisData['b_fin_UserColor'] = Number(divParamFinUserColor.innerText);
    }

    thisDataBefore['b_gen_SubscribeNews'] = thisData['b_gen_SubscribeNews'];
    thisDataBefore['b_fin_UserColor'] = thisData['b_fin_UserColor'];
});

//Events

divB_gen_SubscribeNews.onchange = function(event){
    if(this.checked === false) {
        thisData['b_gen_SubscribeNews'] = 0;
    } else {
        thisData['b_gen_SubscribeNews'] = 1;
    }

    WasModified();
};

divB_fin_UserColor.onchange = function(event){
    if(this.checked === false) {
        thisData['b_fin_UserColor'] = 0;
    } else {
        thisData['b_fin_UserColor'] = 1;
    }

    WasModified();
};

btnCancel.onclick = function(e) {
    let urlString = '/';

    if (IsModified === false) {
        window.location.href = urlString;
    } else {
        let ans = confirm('Не сохранять изменения?');
        if(ans === true) {
            window.location.href = urlString;
        }
    }
};

btnSave.onclick = function(e) {
    runAjax('/settings-save', thisData);
};

tabMain.onclick = function(e){

    if(numTab !== 1){
        unpushMenu();
        numTab = 1;
        this.classList.add('btn-active');
    }

    visiblePanels();
};

tabSys.onclick = function(e){

    if(numTab !== 2){
        unpushMenu();
        numTab = 2;
        this.classList.add('btn-active');
    }

    visiblePanels();
};

tabFin.onclick = function(e){

    if(numTab !== 3){
        unpushMenu();
        numTab = 3;
        this.classList.add('btn-active');
    }

    visiblePanels();
};

tabCon.onclick = function(e){

    if(numTab !== 4){
        unpushMenu();
        numTab = 4;
        this.classList.add('btn-active');
    }

    visiblePanels();
};

//Helpers

function showError(data){
    console.log(data);
}

function WasModified() {
    IsModified = false;

    if ((thisDataBefore['b_gen_SubscribeNews'] == thisData['b_gen_SubscribeNews']
        && thisDataBefore['b_fin_UserColor'] == thisData['b_fin_UserColor']
    ) == false) {
        IsModified = true;
    }

    if(IsModified === true) {
        if (btnCancel.classList.contains('col-back-rea-light') === false) {
            btnCancel.classList.add('col-back-rea-light');
        }
    } else {
        if (btnCancel.classList.contains('col-back-rea-light') === true) {
            btnCancel.classList.remove('col-back-rea-light');
        }
    }
}

function render(data){
    let urlString = '/';

    window.location.href = urlString;
}

function unpushMenu() {
    tabMain.classList.remove('btn-active');
    tabSys.classList.remove('btn-active');
    tabFin.classList.remove('btn-active');
    tabCon.classList.remove('btn-active');
}

function visiblePanels(){
    hidePanels();
    if(numTab === 1){
        contentMain.hidden = false;
    }
    if(numTab === 2){
        contentSys.hidden = false;
    }
    if(numTab === 3){
        contentFin.hidden = false;
    }
    if(numTab === 4){
        contentCon.hidden = false;
    }
}

function hidePanels() {
    contentMain.hidden = true;
    contentSys.hidden = true;
    contentFin.hidden = true;
    contentCon.hidden = true;
}