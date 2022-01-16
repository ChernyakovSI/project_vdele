let divParamID = document.getElementById('paramID');
let divParamDate = document.getElementById('paramDate');
let divParamDateFinish = document.getElementById('paramDateFinish');
let divParamName = document.getElementById('paramName');
let paramPeriodType = document.getElementById('paramPeriodType');
let paramDateFrom = document.getElementById('paramDateFrom');
let paramDateTo = document.getElementById('paramDateTo');

let selValuePriority = document.getElementById('selValuePriority');

let btnClearPriority = document.getElementById('btnClearPriority');

let valuePeriodFrom = document.getElementById('valuePeriodFrom');
let valuePeriodTo = document.getElementById('valuePeriodTo');

let setPeriodAll = document.getElementById('setPeriodAll');
let setPeriodPriority = document.getElementById('setPeriodPriority');
let setPeriodManual = document.getElementById('setPeriodManual');

let wrapValuePriority = document.getElementById('wrapValuePriority');
let capPeriodFrom = document.getElementById('capPeriodFrom');
let wrapPeriodFrom = document.getElementById('wrapPeriodFrom');
let capPeriodTo = document.getElementById('capPeriodTo');
let wrapPeriodTo = document.getElementById('wrapPeriodTo');

let listPriority = document.getElementById('list_priority_sel');

let thisData = {
    'id' : 0,
    'date' : 0,
    'dateFinish' : 0,
    'name' : '',
    'typePeriod' : 0,
    'dateFrom' : 0,
    'dateTo' : 0,
};

$(document).ready( function() {
    thisData['date'] = divParamDate.innerText;
    if( thisData['date'] == 0){
        thisData['date'] = getTimeStampFromElement(valuePeriodFrom);
    }

    thisData['dateFinish'] = divParamDateFinish.innerText;
    if( thisData['dateFinish'] == 0){
        thisData['dateFinish'] = getTimeStampFromElement(valuePeriodTo);
    }

    thisData['name'] = divParamName.innerText;
    thisData['id'] = divParamID.innerText;

    thisData['typePeriod'] = paramPeriodType.innerText;

    thisData['dateFrom'] = paramDateFrom.innerText;
    valuePeriodFrom.value = getStringDateFromTimeStamp(thisData['dateFrom'], false);

    thisData['dateTo'] = endDay_timestamp(paramDateTo.innerText);
    valuePeriodTo.value = getStringDateFromTimeStamp(thisData['dateTo'], false);

    if(thisData['id'] == 0) {

    } else {
        selValuePriority.value = getNameSemester();
    }
});

//Events -------------------------------------------------------------------------------------

btnClearPriority.onclick = function(event){
    thisData['id'] = 0;
    thisData['name'] = '';
    thisData['date'] = 0;
    thisData['dateFinish'] = 0;
    selValuePriority.value = '';
};

valuePeriodFrom.onchange = function(event){
    thisData['dateFrom'] = getTimeStampFromElement(this);
};

valuePeriodTo.onchange = function(event){
    thisData['dateTo'] = endDay_timestamp(getTimeStampFromElement(this));
};

setPeriodAll.onchange = function(event){
    if(this.checked == true) {
        thisData['typePeriod'] = 0;
        wrapValuePriority.hidden = true;
        btnClearPriority.hidden = true;

        capPeriodFrom.hidden = true;
        wrapPeriodFrom.hidden = true;
        capPeriodTo.hidden = true;
        wrapPeriodTo.hidden = true;
    }
};

setPeriodPriority.onchange = function(event){
    if(this.checked == true) {
        thisData['typePeriod'] = 1;
        wrapValuePriority.hidden = false;
        btnClearPriority.hidden = false;

        capPeriodFrom.hidden = true;
        wrapPeriodFrom.hidden = true;
        capPeriodTo.hidden = true;
        wrapPeriodTo.hidden = true;
    }
};

setPeriodManual.onchange = function(event){
    if(this.checked == true) {
        thisData['typePeriod'] = 2;
        wrapValuePriority.hidden = true;
        btnClearPriority.hidden = true;

        capPeriodFrom.hidden = false;
        wrapPeriodFrom.hidden = false;
        capPeriodTo.hidden = false;
        wrapPeriodTo.hidden = false;
    }
};

btnRefresh.onclick = function(event){
    if(thisData['typePeriod'] == 0) {
        window.location.href = '/goal/results?typePeriod=' + thisData['typePeriod'];
    } else if (thisData['typePeriod'] == 1) {
        if(thisData['id'] > 0) {
            window.location.href = '/goal/results?typePeriod=' + thisData['typePeriod'] + '&sem=' + thisData['id'];
        } else {
            window.location.href = '/goal/results?typePeriod=0';
        }
    } else {
        if(thisData['dateFrom'] < thisData['dateTo'] && thisData['dateFrom'] > 0) {
            window.location.href = '/goal/results?typePeriod=' + thisData['typePeriod'] + '&from=' + thisData['dateFrom'] +
                '&to=' + thisData['dateTo'];
        } else {
            window.location.href = '/goal/results?typePeriod=0';
        }
    }
};

selValuePriority.onchange = function(event){
    let curPriority = this.value.trim();
    let idPriority = 0;

    let children = listPriority.childNodes;
    for(child in children){
        if(children[child].nodeName === 'OPTION' && children[child].innerText.trim() === curPriority.trim()) {
            idPriority = children[child].getAttribute('data-id');
            thisData['id'] = idPriority;
            break;
        }
    }

    if (idPriority === 0) {
        this.value = '';
    }
};

//HELPERS ------------------------------------------------------------------------------------

function getNameSemester() {
    let name = thisData['name'] + ' (' + getStringDateFromTimeStamp2(thisData['date'], false) + ' - ' +
        getStringDateFromTimeStamp2(thisData['dateFinish'], false) + ')';

    return name;
}