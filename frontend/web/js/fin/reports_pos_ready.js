
let divParamPeriodFrom = document.getElementById('paramPeriodFrom');
let divParamPeriodTo = document.getElementById('paramPeriodTo');

let floatingCirclesGMain = document.getElementById('floatingCirclesGMain');

let valuePeriodFrom = document.getElementById('valuePeriodFrom');
let valuePeriodTo = document.getElementById('valuePeriodTo');

let settingsPeriodFrom = document.querySelector('#settingsPeriodFrom');
let settingsPeriodTo = document.querySelector('#settingsPeriodTo');
let spanDelta = document.querySelector('#delta');

let nowServer = new Date();
let currentTimeZoneOffset = nowServer.getTimezoneOffset()/60;
nowServer.setHours(nowServer.getHours() - currentTimeZoneOffset);

let chkSub = document.getElementById('setVisibleSub');

let curDateFrom = new Date(valuePeriodFrom.value);
let curDateTo = new Date(valuePeriodTo.value);
let thisData = {
    'selPeriodFrom' : String(curDateFrom.getTime()).substr(0, 10),
    'selPeriodTo' : String(curDateTo.getTime()).substr(0, 10),
    'is_sub' : chkSub.checked
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

chkSub.onchange = function(event){
    readTable();
};

function readTable(){

    curDateFrom = new Date(valuePeriodFrom.value);
    curDateTo = new Date(valuePeriodTo.value);
    curDateTo.setHours(23,59,59,999);

    thisData = {
        'selPeriodFrom' : String(curDateFrom.getTime()).substr(0, 10),
        'selPeriodTo' : String(curDateTo.getTime()).substr(0, 10),
        'is_sub' : chkSub.checked
    };

    runAjax('/fin/reports', thisData)
}

function rerender(dataSet) {
    let listExp = document.getElementById('list-expenses');
    listExp.innerHTML = '';

    let SumFormatExp = dataSet.SumFormatExp;

    let exColnameCat = document.getElementById('expenses-colname-cat');
    let exColnameAmo = document.getElementById('expenses-colname-amo');
    let exColtotalCat = document.getElementById('expenses-coltotal-cat');
    let exColtotalAmo = document.getElementById('expenses-coltotal-amo');

    let exColnameSub = document.getElementById('expenses-colname-sub');
    let exColtotalSub = document.getElementById('expenses-coltotal-sub');

    exColnameCat.classList.remove('fin-reg-cat-60');
    exColnameCat.classList.remove('fin-reg-cat-40');

    exColtotalCat.classList.remove('fin-reg-cat-60');
    exColtotalCat.classList.remove('fin-reg-cat-40');

    exColnameAmo.classList.remove('fin-reg-amount-end');
    exColnameAmo.classList.remove('fin-reg-amount-end-sub');

    exColtotalAmo.classList.remove('fin-reg-amount-end');
    exColtotalAmo.classList.remove('fin-reg-amount-end-sub');

    if(chkSub.checked){
        exColnameCat.classList.add('fin-reg-cat-40');
        exColtotalCat.classList.add('fin-reg-cat-40');
        exColnameAmo.classList.add('fin-reg-amount-end-sub');
        exColtotalAmo.classList.add('fin-reg-amount-end-sub');

        exColnameSub.innerHTML = '';
        exColnameSub.classList.add('fin-reg-cat-40');
        let divWrapSubName = document.createElement('div');
        divWrapSubName.className = 'message-wrapper-title';
        let divTextSubName = document.createElement('div');
        divTextSubName.className = 'message-text-line table-caption';
        divTextSubName.innerHTML = 'Подкатегория';
        divWrapSubName.append(divTextSubName);
        exColnameSub.append(divWrapSubName);

        exColtotalSub.innerHTML = '';
        exColtotalSub.classList.add('fin-reg-cat-40');
        let divWrapSubTotal = document.createElement('div');
        divWrapSubTotal.className = 'message-wrapper-title';
        let divTextSubTotal = document.createElement('div');
        divTextSubTotal.className = 'message-text-line';
        divWrapSubTotal.append(divTextSubTotal);
        exColtotalSub.append(divWrapSubTotal);
    }
    else{
        exColnameCat.classList.add('fin-reg-cat-60');
        exColtotalCat.classList.add('fin-reg-cat-60');
        exColnameAmo.classList.add('fin-reg-amount-end');
        exColtotalAmo.classList.add('fin-reg-amount-end');

        exColnameSub.innerHTML = '';
        exColnameSub.classList.remove('fin-reg-cat-40');
        exColtotalSub.innerHTML = '';
        exColtotalSub.classList.remove('fin-reg-cat-40');
    }

    // Заголовки доходов

    let prColnameCat = document.getElementById('profits-colname-cat');
    let prColnameAmo = document.getElementById('profits-colname-amo');
    let prColtotalCat = document.getElementById('profits-coltotal-cat');
    let prColtotalAmo = document.getElementById('profits-coltotal-amo');

    let prColnameSub = document.getElementById('profits-colname-sub');
    let prColtotalSub = document.getElementById('profits-coltotal-sub');

    prColnameCat.classList.remove('fin-reg-cat-60');
    prColnameCat.classList.remove('fin-reg-cat-40');

    prColtotalCat.classList.remove('fin-reg-cat-60');
    prColtotalCat.classList.remove('fin-reg-cat-40');

    prColnameAmo.classList.remove('fin-reg-amount-end');
    prColnameAmo.classList.remove('fin-reg-amount-end-sub');

    prColtotalAmo.classList.remove('fin-reg-amount-end');
    prColtotalAmo.classList.remove('fin-reg-amount-end-sub');

    if(chkSub.checked){
        prColnameCat.classList.add('fin-reg-cat-40');
        prColtotalCat.classList.add('fin-reg-cat-40');
        prColnameAmo.classList.add('fin-reg-amount-end-sub');
        prColtotalAmo.classList.add('fin-reg-amount-end-sub');

        prColnameSub.innerHTML = '';
        prColnameSub.classList.add('fin-reg-cat-40');
        let prdivWrapSubName = document.createElement('div');
        prdivWrapSubName.className = 'message-wrapper-title';
        let prdivTextSubName = document.createElement('div');
        prdivTextSubName.className = 'message-text-line table-caption';
        prdivTextSubName.innerHTML = 'Подкатегория';
        prdivWrapSubName.append(prdivTextSubName);
        prColnameSub.append(prdivWrapSubName);

        prColtotalSub.innerHTML = '';
        prColtotalSub.classList.add('fin-reg-cat-40');
        let prdivWrapSubTotal = document.createElement('div');
        prdivWrapSubTotal.className = 'message-wrapper-title';
        let prdivTextSubTotal = document.createElement('div');
        prdivTextSubTotal.className = 'message-text-line';
        prdivWrapSubTotal.append(prdivTextSubTotal);
        prColtotalSub.append(prdivWrapSubTotal);
    }
    else{
        prColnameCat.classList.add('fin-reg-cat-60');
        prColtotalCat.classList.add('fin-reg-cat-60');
        prColnameAmo.classList.add('fin-reg-amount-end');
        prColtotalAmo.classList.add('fin-reg-amount-end');

        prColnameSub.innerHTML = '';
        prColnameSub.classList.remove('fin-reg-cat-40');
        prColtotalSub.innerHTML = '';
        prColtotalSub.classList.remove('fin-reg-cat-40');
    }

    if(dataSet.dataExp.length > 0){
        dataSet.dataExp.forEach(function(data, i, arrData){
            let divRow = document.createElement('div');
            divRow.className = 'fin-acc-row expense-back interactive-only';

            let divMainCat = document.createElement('div');
            if(chkSub.checked){
                divMainCat.className = 'fin-reg-cat-40 table-text';
            }
            else{
                divMainCat.className = 'fin-reg-cat-60 table-text';
            }

            let divWrapCat = document.createElement('div');
            divWrapCat.className = 'message-wrapper-title';

            let divTextCat = document.createElement('div');
            divTextCat.className = 'message-text-line';

            divTextCat.innerHTML = data['CatName'];

            divWrapCat.append(divTextCat);
            divMainCat.append(divWrapCat);
            divRow.append(divMainCat);

            if(chkSub.checked){
                let divMainSub = document.createElement('div');
                divMainSub.className = 'fin-reg-cat-40 table-text';

                let divWrapSub = document.createElement('div');
                divWrapSub.className = 'message-wrapper-title';

                let divTextSub = document.createElement('div');
                divTextSub.className = 'message-text-line';

                divTextSub.innerHTML = data['SubName'];

                divWrapSub.append(divTextSub);
                divMainSub.append(divWrapSub);
                divRow.append(divMainSub);
            }

            let divMainAmount = document.createElement('div');

            let divWrapAmount = document.createElement('div');
            divWrapAmount.className = 'message-wrapper-title';

            let divTextAmount = document.createElement('div');
            divTextAmount.className = 'message-text-line right-text';

            if(chkSub.checked){
                divTextAmount.innerHTML = SumFormatExp[data['id_subcategory']];
                divMainAmount.className = 'fin-reg-amount-end-sub  table-text';
            }
            else{
                divTextAmount.innerHTML = SumFormatExp[data['id_category']];
                divMainAmount.className = 'fin-reg-amount-end  table-text';
            }

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
            if(chkSub.checked){
                divMainCat.className = 'fin-reg-cat-40 table-text';
            }
            else{
                divMainCat.className = 'fin-reg-cat-60 table-text';
            }

            let divWrapCat = document.createElement('div');
            divWrapCat.className = 'message-wrapper-title';

            let divTextCat = document.createElement('div');
            divTextCat.className = 'message-text-line';

            divTextCat.innerHTML = data['CatName'];

            divWrapCat.append(divTextCat);
            divMainCat.append(divWrapCat);
            divRow.append(divMainCat);

            if(chkSub.checked){
                let divMainSub = document.createElement('div');
                divMainSub.className = 'fin-reg-cat-40 table-text';

                let divWrapSub = document.createElement('div');
                divWrapSub.className = 'message-wrapper-title';

                let divTextSub = document.createElement('div');
                divTextSub.className = 'message-text-line';

                divTextSub.innerHTML = data['SubName'];

                divWrapSub.append(divTextSub);
                divMainSub.append(divWrapSub);
                divRow.append(divMainSub);
            }

            let divMainAmount = document.createElement('div');

            let divWrapAmount = document.createElement('div');
            divWrapAmount.className = 'message-wrapper-title';

            let divTextAmount = document.createElement('div');
            divTextAmount.className = 'message-text-line right-text';
            if(chkSub.checked){
                divTextAmount.innerHTML = SumFormatProf[data['id_subcategory']];
                divMainAmount.className = 'fin-reg-amount-end-sub table-text';
            }
            else{
                divTextAmount.innerHTML = SumFormatProf[data['id_category']];
                divMainAmount.className = 'fin-reg-amount-end table-text';
            }

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

    settingsPeriodFrom.innerHTML = convertTimeStampVisible(dataSet.periodFrom);
    settingsPeriodTo.innerHTML = convertTimeStampVisible(dataSet.periodTo);

    spanDelta.innerHTML = dataSet.totalDelta;
};

function convertTimeStampVisible(timestamp) {
    let condate = new Date(timestamp*1000);

    return [
        ('0' + condate.getDate()).slice(-2),
        ('0' + (condate.getMonth()+1)).slice(-2),
        condate.getFullYear()
    ].join('.');
}