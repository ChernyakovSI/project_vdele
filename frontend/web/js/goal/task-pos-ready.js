let divParamDate = document.getElementById('paramDate');
let divParamID = document.getElementById('paramID');
let divParamText = document.getElementById('paramText');
let divParamResText = document.getElementById('paramResultText');

let valueText = document.getElementById('valueText');

let btnResult = document.getElementById('btnResult');
let btnText = document.getElementById('btnText');

let thisData = {
    'id' : 0,
    'date' : 0,
    'id_sphere' : 0,
    'title' : '',
    'text' : '',
    'num' : 0,
    'status': 0,
    'dateDone': 0,
    'resultText': '',
};

$(document).ready( function() {

    thisData['date'] = divParamDate.innerText;
    valueDate.value = getStringDateFromTimeStamp(divParamDate.innerText);

    if(divParamID.innerText) {
        thisData['id'] = Number(divParamID.innerText);
        thisData['id_sphere'] = Number(divParamIDSphere.innerText);
        thisData['title'] = valueTitle.value;
        thisData['text'] = divParamText.innerText;
        thisData['num'] = Number(divParamNum.innerText);
        thisData['id_level'] = Number(paramLevel);
        thisData['status'] = Number(divParamStatus.innerText);
        thisData['dateDone'] = Number(divParamDateDone.innerText);
        thisData['resultType'] = Number(divParamResType.innerText);
        thisData['resultMark'] = Number(divParamResMark.innerText);;
        thisData['resultText'] = divParamResText.innerText;
    }
    else {
        thisData['id'] = 0;
        thisData['id_sphere'] = 0;
        thisData['title'] = '';
        thisData['text'] = '';
        thisData['num'] = 0;
        thisData['status'] = 0;
        thisData['dateDone'] = 0;
        thisData['resultText'] = '';
    }

    valueText.innerHTML = getNewLinesToBr(divParamResText);

    convertNewLinesToBr(valueText);
    DetectURLs(valueText);
    generatorURLs();

    thisData['resultText'] = valueText.innerHTML.trim();

    valueText.innerHTML = getNewLinesToBr(divParamText);

    convertNewLinesToBr(valueText);
    DetectURLs(valueText);
    generatorURLs();

    if(divParamResText.innerText !== '') {
        btnResult.click();
    }

});

//Events

btnResult.onclick = function(e) {
    isText = btnResult.classList.contains('btn-active');

    if(isText == false) {
        btnResult.classList.add('btn-active');
        btnText.classList.remove('btn-active');

        thisData['text'] = valueText.innerHTML.trim();
        valueText.innerHTML = thisData['resultText'];
    }
};