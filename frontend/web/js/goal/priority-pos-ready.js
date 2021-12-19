
let divParamDate = document.getElementById('paramDate');
let divParamDateFinish = document.getElementById('paramDateFinish');
let divParamName = document.getElementById('paramName');

let valuePeriodFrom = document.getElementById('valuePeriodFrom');
let valuePeriodTo = document.getElementById('valuePeriodTo');
let valueName = document.getElementById('valueName');

let tblExams = document.getElementById('list-dreams1');
let tblZachet = document.getElementById('list-dreams2');

let btnSave = document.getElementById('btnSave');

let floatingCirclesGMain = document.getElementById('floatingCirclesGMain');


let thisData = {
    'id' : 0,
    'date' : 0,
    'dateFinish' : 0,
    'name' : ''
};

$(document).ready( function() {
    thisData['date'] = divParamDate.innerText;
    valuePeriodFrom.value = getStringDateFromTimeStamp(divParamDate.innerText, false);

    thisData['dateFinish'] = divParamDateFinish.innerText;
    valuePeriodTo.value = getStringDateFromTimeStamp(divParamDateFinish.innerText, false);

    thisData['name'] = divParamName.innerText;
    valueName.value = divParamName.innerText;

    resize();
});

//Events -------------------------------------------------------------------------------------

valueName.onchange = function(event){
    thisData['name'] = this.value;
    btnSave.hidden = false;
};

valuePeriodFrom.onchange = function(event){
    thisData['date'] = getTimeStampFromElement(this);
    btnSave.hidden = false;
};

valuePeriodTo.onchange = function(event){
    thisData['dateFinish'] = getTimeStampFromElement(this);
    btnSave.hidden = false;
};

btnSave.onclick = function(event){
    btnSave.hidden = true;

    runAjax('/goal/semester-save', thisData, floatingCirclesGMain);
};

//HELPERS ------------------------------------------------------------------------------------

function resize(mode = 0) {
    let children = tblExams.childNodes;
    resizeTable(children, mode);

    children = tblZachet.childNodes;
    resizeTable(children, mode);

}

function resizeTable(children, mode = 0) {
    if (children.length > 0) {
        let divRow;

        let colNum, colADate, colDream, colMark, colDateDone, maxHeight;

        for(child in children){
            maxHeight = 0;
            divRow = children[child].childNodes;

            if (divRow == undefined || divRow.length == 0) {
                continue
            } else {
                if(mode == 0) {
                    for(column in divRow){
                        if(divRow[column].nodeName == 'A') {
                            divRow = divRow[column].childNodes;
                            break;
                        }
                    }
                }
            }

            if (mode == 0 && (divRow == undefined || divRow.length == 0)) {
                continue
            }

            for(column in divRow){
                if (divRow.length > 0){
                    if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameNum') > -1) {
                        colNum = divRow[column];
                        if(maxHeight < colNum.clientHeight) {
                            maxHeight = colNum.clientHeight;
                        }
                    }
                    if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameDeadline') > -1) {
                        colADate = divRow[column];
                        if(maxHeight < colADate.clientHeight) {
                            maxHeight = colADate.clientHeight;
                        }
                    }
                    if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameDream') > -1) {
                        colDream = divRow[column];
                        if(maxHeight < colDream.clientHeight) {
                            maxHeight = colDream.clientHeight;
                        }
                    }
                    if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameMark') > -1) {
                        colMark = divRow[column];
                        if(maxHeight < colMark.clientHeight) {
                            maxHeight = colMark.clientHeight;
                        }
                    }
                    if(divRow[column].nodeName == 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameDateDone') > -1) {
                        colDateDone = divRow[column];
                        if(maxHeight < colDateDone.clientHeight) {
                            maxHeight = colDateDone.clientHeight;
                        }
                    }
                }
            }
            if(colNum != undefined && colNum.clientHeight != undefined < maxHeight) {
                colNum.style.height = maxHeight + "px";
            }
            if(colADate != undefined && colADate.clientHeight != undefined < maxHeight) {
                colADate.style.height = maxHeight + "px";
            }
            if(colDream != undefined && colDream.clientHeight != undefined < maxHeight) {
                colDream.style.height = maxHeight + "px";
            }
            if(colMark != undefined && colMark.clientHeight != undefined < maxHeight) {
                colMark.style.height = maxHeight + "px";
            }
            if(colDateDone != undefined && colDateDone.clientHeight != undefined < maxHeight) {
                colDateDone.style.height = maxHeight + "px";
            }
            colNum = undefined;
            colADate = undefined;
            colDream = undefined;
            colMark = undefined;
            colDateDone = undefined;

        }
    }
}