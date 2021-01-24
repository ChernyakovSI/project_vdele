
let divParamPeriodFrom = document.getElementById('paramPeriodFrom');
let divParamPeriodTo = document.getElementById('paramPeriodTo');

let floatingCirclesGMain = document.getElementById('floatingCirclesGMain');

let valuePeriodFrom = document.getElementById('valuePeriodFrom');
let valuePeriodTo = document.getElementById('valuePeriodTo');

let nowServer = new Date();
let currentTimeZoneOffset = nowServer.getTimezoneOffset()/60;
nowServer.setHours(nowServer.getHours() - currentTimeZoneOffset);

let curDateFrom = new Date(valuePeriodFrom.value);
let curDateTo = new Date(valuePeriodTo.value);
let thisData = {
    'selPeriodFrom' : String(curDateFrom.getTime()).substr(0, 10),
    'selPeriodTo' : String(curDateTo.getTime()).substr(0, 10),
};


$(document).ready( function() {

    let strDate = convertTimeStamp(divParamPeriodFrom.innerHTML);
    let curDate = new Date(strDate);
    curDate.setHours(curDate.getHours() - currentTimeZoneOffset);
    valuePeriodFrom.value = curDate.toISOString().substring(0, 10);
    //console.log(curDate.toISOString().substring(0, 10));

    strDate = convertTimeStamp(divParamPeriodTo.innerHTML);
    curDate = new Date(strDate);
    curDate.setHours(curDate.getHours() - currentTimeZoneOffset);
    valuePeriodTo.value = curDate.toISOString().substring(0, 10);
});


function runAjax(url, value, typeReq = 'post'){
    floatingCirclesGMain.hidden = false;

    $.ajax({
        type : typeReq,
        url : url,
        data : value
    }).done(function(data) {
        if (data.error === null || data.error === undefined) {
            rerender(data);
        } else {
            if (data.error !== '' || data.error !== null || data.error !== undefined){
                console.log(data.error);
            }
        }

        floatingCirclesGMain.hidden = true;

    }).fail(function() {
        console.log('error');
        floatingCirclesGMain.hidden = true;
    });
}


function convertTimeStamp(timestamp) {
    let condate = new Date(timestamp*1000);

    return [
        condate.getFullYear(),           // Get day and pad it with zeroes
        ('0' + (condate.getMonth()+1)).slice(-2),      // Get month and pad it with zeroes
        ('0' + condate.getDate()).slice(-2)                          // Get full year
    ].join('.');                                  // Glue the pieces together
}

valuePeriodFrom.onchange = function(event){
    readTable();
};

valuePeriodTo.onchange = function(event){
    readTable();
};

function readTable(){

    curDateFrom = new Date(valuePeriodFrom.value);
    curDateTo = new Date(valuePeriodTo.value);
    curDateTo.setHours(23,59,59,999);

    value = {
        'selPeriodFrom' : String(curDateFrom.getTime()).substr(0, 10),
        'selPeriodTo' : String(curDateTo.getTime()).substr(0, 10),
    };

    runAjax('/fin/reports', value)
}

function rerender(dataSet) {
    let listExp = document.getElementById('list-expenses');
    listExp.innerHTML = '';

    let SumFormatExp = dataSet.SumFormatExp;

    if(dataSet.dataExp.length > 0){
        dataSet.dataExp.forEach(function(data, i, arrData){
            let divRow = document.createElement('div');
            divRow.className = 'fin-acc-row expense-back interactive-only';

            let divMainCat = document.createElement('div');
            divMainCat.className = 'fin-reg-cat-60 table-text';

            let divWrapCat = document.createElement('div');
            divWrapCat.className = 'message-wrapper-title';

            let divTextCat = document.createElement('div');
            divTextCat.className = 'message-text-line';

            divTextCat.innerHTML = data['CatName'];

            divWrapCat.append(divTextCat);
            divMainCat.append(divWrapCat);
            divRow.append(divMainCat);

            // let divMainSub = document.createElement('div');
            // divMainSub.className = 'fin-reg-sub table-text';
            //
            // let divWrapSub = document.createElement('div');
            // divWrapSub.className = 'message-wrapper-title';
            //
            // let divTextSub = document.createElement('div');
            // divTextSub.className = 'message-text-line';
            //
            // if (data['id_type'] !== '2') {
            //     divTextSub.innerHTML = data['SubName'];
            // }
            // else
            // {
            //     divTextSub.innerHTML = '';
            // }
            //
            // divWrapSub.append(divTextSub);
            // divMainSub.append(divWrapSub);
            // divRow.append(divMainSub);

            let divMainAmount = document.createElement('div');
            divMainAmount.className = 'fin-reg-amount-end table-text';

            let divWrapAmount = document.createElement('div');
            divWrapAmount.className = 'message-wrapper-title';

            let divTextAmount = document.createElement('div');
            divTextAmount.className = 'message-text-line right-text';
            divTextAmount.innerHTML = SumFormatExp[data['id_category']];

            divWrapAmount.append(divTextAmount);
            divMainAmount.append(divWrapAmount);
            divRow.append(divMainAmount);

            let hrLine = document.createElement('hr');
            hrLine.className = 'line';

            let divClear = document.createElement('div');
            divClear.className = 'clearfix';

            divClear.append(hrLine);
            divRow.append(divClear);

            listExp.append(divRow);
        });

        // resize();
    }
    else
    {
        let divInfo = document.createElement('div');
        divInfo.className = 'text-font text-center margin-v20';
        divInfo.setAttribute('id', 'infoExp');
        divInfo.innerHTML = 'Нет движений';

        listExp.append(divInfo);
    }

    let divTotalExp = document.getElementById('totalExp');
    divTotalExp.innerHTML = dataSet.totalExp;

    // Profits

    let listProfit = document.getElementById('list-profits');
    listProfit.innerHTML = '';

    let SumFormatProf = dataSet.SumFormatProf;

    if(dataSet.dataProf.length > 0){
        dataSet.dataProf.forEach(function(data, i, arrData){
            let divRow = document.createElement('div');
            divRow.className = 'fin-acc-row profit-back interactive-only';

            let divMainCat = document.createElement('div');
            divMainCat.className = 'fin-reg-cat-60 table-text';

            let divWrapCat = document.createElement('div');
            divWrapCat.className = 'message-wrapper-title';

            let divTextCat = document.createElement('div');
            divTextCat.className = 'message-text-line';

            divTextCat.innerHTML = data['CatName'];

            divWrapCat.append(divTextCat);
            divMainCat.append(divWrapCat);
            divRow.append(divMainCat);

            // let divMainSub = document.createElement('div');
            // divMainSub.className = 'fin-reg-sub table-text';
            //
            // let divWrapSub = document.createElement('div');
            // divWrapSub.className = 'message-wrapper-title';
            //
            // let divTextSub = document.createElement('div');
            // divTextSub.className = 'message-text-line';
            //
            // if (data['id_type'] !== '2') {
            //     divTextSub.innerHTML = data['SubName'];
            // }
            // else
            // {
            //     divTextSub.innerHTML = '';
            // }
            //
            // divWrapSub.append(divTextSub);
            // divMainSub.append(divWrapSub);
            // divRow.append(divMainSub);

            let divMainAmount = document.createElement('div');
            divMainAmount.className = 'fin-reg-amount-end table-text';

            let divWrapAmount = document.createElement('div');
            divWrapAmount.className = 'message-wrapper-title';

            let divTextAmount = document.createElement('div');
            divTextAmount.className = 'message-text-line right-text';
            divTextAmount.innerHTML = SumFormatProf[data['id_category']];

            divWrapAmount.append(divTextAmount);
            divMainAmount.append(divWrapAmount);
            divRow.append(divMainAmount);

            let hrLine = document.createElement('hr');
            hrLine.className = 'line';

            let divClear = document.createElement('div');
            divClear.className = 'clearfix';

            divClear.append(hrLine);
            divRow.append(divClear);

            listProfit.append(divRow);
        });

        // resize();
    }
    else
    {
        let divInfo = document.createElement('div');
        divInfo.className = 'text-font text-center margin-v20';
        divInfo.setAttribute('id', 'infoProf');
        divInfo.innerHTML = 'Нет движений';

        listProfit.append(divInfo);
    }

    let divTotalProf = document.getElementById('totalProf');
    divTotalProf.innerHTML = dataSet.totalProf;
};