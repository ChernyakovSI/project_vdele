let divParamDate = document.getElementById('paramDate');
let ColorUnused = document.getElementById('paramColorUnused').innerText;
let ColorNone = document.getElementById('paramColorNone').innerText;

let btnBack = document.getElementById('arrow-back');
let btnForward = document.getElementById('arrow-forward');

let divCaption = document.getElementById('caption');

let rowWeek4 = document.getElementById('week4');
let rowWeek5 = document.getElementById('week5');
let arrBack = document.getElementById('arrow-back-high');
let arrForward = document.getElementById('arrow-forward-high');

let ColorNoneArr = ColorNone.split(' ');

let nowServer = new Date();
let currentTimeZoneOffset = nowServer.getTimezoneOffset()/60;
//nowServer.setHours(nowServer.getHours() - currentTimeZoneOffset);

let thisData = {
    'date' : 0,
    'month' : 0,
    'day': 0,
    'startDate': 0,
    'today': 0
};

let numDaysInMonth = 0;
let numStartWeek = 0;
let startDateString = 0;

$(document).ready( function() {
    let strDate = convertTimeStampWithTime(divParamDate.innerText);
    let curDate = new Date(strDate);
    curDate.setHours(curDate.getHours() - currentTimeZoneOffset);
    curDate.setHours(0,0,0,0);

    startDateString = fotmatMonth(curDate.getMonth()) + '-01-' + curDate.getFullYear();
    startDateString = new Date(startDateString);

    thisData['date'] = String(curDate.getTime()).substr(0, 10);
    thisData['startDate'] = String(startDateString.getTime()).substr(0, 10);
    thisData['month'] = String(startDateString.getTime()).substr(0, 10);
    thisData['day'] = startDateString.getDay();

    if(nowServer.getMonth() === startDateString.getMonth() && nowServer.getFullYear() === startDateString.getFullYear()){
        thisData['today'] = nowServer.getDate();
    } else {
        thisData['today'] = 0;
    }

    numDaysInMonth = daysInMonth(curDate.getMonth()+1, curDate.getFullYear());
    numStartWeek = startDateString.getWeek();

    runAjax('/goal/get-data-for-month', thisData);
});

btnBack.onclick = function(e) {
    renewMonth(false);
};

btnForward.onclick = function(e) {
    renewMonth();
};

function renewMonth(forward = true){
    let curDate = new Date(Number(thisData['startDate'] + '000'));

    startDateString = fotmatMonth(curDate.getMonth()) + '-01-' + curDate.getFullYear();
    startDateString = new Date(startDateString);

    if(forward === true) {
        startDateString.setMonth(startDateString.getMonth() + 1);
    } else {
        startDateString.setMonth(startDateString.getMonth() - 1);
    }

    thisData['date'] = String(curDate.getTime()).substr(0, 10);
    thisData['startDate'] = String(startDateString.getTime()).substr(0, 10);
    thisData['month'] = String(startDateString.getTime()).substr(0, 10);
    thisData['day'] = startDateString.getDay();
    if(nowServer.getMonth() === startDateString.getMonth() && nowServer.getFullYear() === startDateString.getFullYear()){
        thisData['today'] = nowServer.getDate();
    } else {
        thisData['today'] = 0;
    }

    if (thisData['day'] === 0) {
        thisData['day'] = 7;
    }

    divCaption.innerText =nameMonth(startDateString.getMonth()) + ' ' + startDateString.getFullYear();

    numDaysInMonth = daysInMonth(startDateString.getMonth()+1, startDateString.getFullYear());
    numStartWeek = startDateString.getWeek();

    runAjax('/goal/get-data-for-month', thisData);
}

function render(dataSet) {
    if(dataSet.allNotes.length > 0) {

        dataSet.allNotes.forEach((data) => {
            let curDate = new Date(Number(data['date'] + '000'));
            data['day'] = curDate.getDate();
        })
    }
    console.log(dataSet);

    let isWork = false;
    let num = 0;
    let Spheres = [];
    let regs = dataSet.regs;

    let maxCell = Number(thisData['day']) + numDaysInMonth - 1;
    let mode = 0;

    if (maxCell === 28) {
        mode = 2;
        rowWeek4.classList.add('visible-not');
        rowWeek5.classList.add('visible-not');

        arrBack.classList.add('h-466px');
        arrBack.classList.remove('h-576px');
        arrBack.classList.remove('h-686px');

        arrForward.classList.add('h-466px');
        arrForward.classList.remove('h-576px');
        arrForward.classList.remove('h-686px');
    } else if (maxCell > 28 && maxCell <= 35) {
        mode = 1;
        rowWeek4.classList.remove('visible-not');
        rowWeek5.classList.add('visible-not');

        arrBack.classList.remove('h-466px');
        arrBack.classList.add('h-576px');
        arrBack.classList.remove('h-686px');

        arrForward.classList.remove('h-466px');
        arrForward.classList.add('h-576px');
        arrForward.classList.remove('h-686px');
    } else if (maxCell > 35) {
        mode = 0;
        rowWeek4.classList.remove('visible-not');
        rowWeek5.classList.remove('visible-not');

        arrBack.classList.remove('h-466px');
        arrBack.classList.remove('h-576px');
        arrBack.classList.add('h-686px');

        arrForward.classList.remove('h-466px');
        arrForward.classList.remove('h-576px');
        arrForward.classList.add('h-686px');
    }

    if(dataSet.allNotes.length > 0) {
        dataSet.allNotes.forEach((data) => {
            if(Spheres[data['day']] === undefined) {
                Spheres[data['day']] = [];
            }
            if(Spheres[data['day']].includes(data['id_sphere']) === false) {
                Spheres[data['day']].push(Number(data['id_sphere']));
            }
        })
    }

    //console.log(regs);

    for(let i=1; i<=42; i++) {
        let divDay = document.getElementById('day'+i);
        let divNDay = document.getElementById('nday'+i);

        clearColor(divDay, i);
        clearColor(divNDay, i);

        if(mode === 1 && i > 28 && i <= 35) {
            continue;
        }
        if(mode === 2 && i > 21 && i <= 35) {
            continue;
        }

        if(isWork === false && i === Number(thisData['day'])) {
            isWork = true;
        }



        if(isWork === true) {
            num = num + 1;
        }

        if(isWork === true && num > numDaysInMonth) {
            isWork = false;
        }


        if(isWork === true) {
            divNDay.innerText = num;
            ColorNoneArr.forEach(curColor => divDay.classList.add(curColor));

            if(thisData['today'] === num){
                divNDay.classList.add('numberCircle');
            }

            fullDayFirst(i, regs[num]);
            if(Spheres[num] !== undefined) {
                fullDay(i, Spheres[num], dataSet.colorStyle);
            }

        } else {
            divNDay.innerText = '';
            divDay.classList.add(ColorUnused);
        }
    }

    num = numStartWeek;
    for(let i=1; i<=6; i++) {

        if(mode === 1 && i === 5) {
            continue;
        }
        if(mode === 2 && i >= 4 && i <= 5) {
            continue;
        }

        if (i > 1) {
            num = num + 1;
        }

        let divWeek = document.getElementById('num-week'+i);



        divWeek.innerText = num;
    }
}

function fullDay(day, Spheres, colors, regs) {
    let divDay2r1c = document.getElementById('r2c1day'+day);
    let divDay2r2c = document.getElementById('r2c2day'+day);

    let divWrap;
    let divCell

    for(let i=1; i<=4; i++) {
        divWrap = document.createElement('div');
        divWrap.className = 'column-40 h-20px content-hide like-table m-l-3px';

        divCell = document.createElement('div');
        if (Spheres.includes(i) === true){
            divCell.className = 'h-16px fullCircle m-t-3px ' + colors[i];
        }
        else {
            divCell.className = 'h-16px m-t-3px';
        }


        divWrap.append(divCell);
        divDay2r1c.append(divWrap);
    }

    for(let i=5; i<=8; i++) {
        divWrap = document.createElement('div');
        divWrap.className = 'column-40 h-20px content-hide like-table m-l-3px';

        divCell = document.createElement('div');
        if (Spheres.includes(i) === true){
            divCell.className = 'h-16px fullCircle m-t-3px ' + colors[i];
        }
        else {
            divCell.className = 'h-16px m-t-3px';
        }

        divWrap.append(divCell);
        divDay2r2c.append(divWrap);
    }
}

function fullDayFirst(day, regs) {
    let divDay1r = document.getElementById('r1c2day'+day);
    let divWrap = document.createElement('div');
    divWrap.className = 'column-90 h-20px content-hide like-table m-l-3px';
    let divCell = document.createElement('div');
    divCell.className = 'h-16px color-text-profit m-t-3px text-s-13px';
    if(regs[1] !== 0) {
        divCell.innerText = formatSum(regs[1]);
    }
    divWrap.append(divCell);
    divDay1r.append(divWrap);

    divWrap = document.createElement('div');
    divWrap.className = 'column-90 h-20px content-hide like-table m-l-3px';
    divCell = document.createElement('div');
    divCell.className = 'h-16px btn-active m-t-3px text-s-13px';
    if(regs[0] !== 0) {
        divCell.innerText = formatSum(regs[0]);
    }
    divWrap.append(divCell);
    divDay1r.append(divWrap);
}

function formatSum(Sum) {

    let Stepen = '';
    if (Sum > 999999999999) {
        Stepen = 'M';
        Sum = Math.floor(Sum/1000000);
    } else
    if (Sum > 999999999) {
        Stepen = 'Т';
        Sum = Math.floor(Sum/1000);
    } else
    if (Sum > 999999) {
        Sum = Math.floor(Sum);
    }

    let strSum = String(Sum.toLocaleString());
    strSum = strSum.substr(0, 9) + Stepen;

    return strSum;
}

function runAjax(url, value, typeReq = 'post'){
    floatingCirclesGMain.hidden = false;

    $.ajax({
        type : typeReq,
        url : url,
        data : value
    }).done(function(data) {
        if (data.error === null || data.error === undefined || data.error === '') {
            render(data);

        } else {
            if (data.error !== '' || data.error !== null || data.error !== undefined){
                //showError(data);
            }
        }

        floatingCirclesGMain.hidden = true;
    }).fail(function() {
        floatingCirclesGMain.hidden = true;
    });
}

function clearColor(divDay, day) {
    ColorNoneArr.forEach(curColor => divDay.classList.remove(curColor));
    divDay.classList.remove(ColorUnused);
    divDay.classList.remove('numberCircle');

    let divDay2r1c = document.getElementById('r2c1day'+day);
    let divDay2r2c = document.getElementById('r2c2day'+day);
    let divDay1r = document.getElementById('r1c2day'+day);

    divDay2r1c.innerHTML = '';
    divDay2r2c.innerHTML = '';
    divDay1r.innerHTML = '';
}

function fotmatMonth(month) {
    month++;
    return month < 10 ? '0' + month : month;
}

function nameMonth(month) {
    month++;
    if(month === 1) {
        return 'Январь';
    }
    if(month === 2) {
        return 'Февраль';
    }
    if(month === 3) {
        return 'Март';
    }
    if(month === 4) {
        return 'Апрель';
    }
    if(month === 5) {
        return 'Май';
    }
    if(month === 6) {
        return 'Июнь';
    }
    if(month === 7) {
        return 'Июль';
    }
    if(month === 8) {
        return 'Август';
    }
    if(month === 9) {
        return 'Сентябрь';
    }
    if(month === 10) {
        return 'Октябрь';
    }
    if(month === 11) {
        return 'Ноябрь';
    }
    if(month === 12) {
        return 'Декабрь';
    }
    return '';
}

function convertTimeStampWithTime(timestamp) {
    let condate = new Date(timestamp*1000);

    strDate = [
        condate.getFullYear(),           // Get day and pad it with zeroes
        ('0' + (condate.getMonth()+1)).slice(-2),      // Get month and pad it with zeroes
        ('0' + condate.getDate()).slice(-2)                          // Get full year
    ].join('-');  // Glue the pieces together

    strDate = strDate + 'T';

    strTime = [
        ('0' + (condate.getHours())).slice(-2),
        ('0' + condate.getMinutes()).slice(-2)
    ].join(':');

    return strDate+strTime;
}

Date.prototype.daysInMonth = function() {
    return 33 - new Date(this.getFullYear(), this.getMonth(), 33).getDate();
};

function daysInMonth(month, year) {
    return new Date(year, month, 0).getDate();
}

Date.prototype.getWeek = function() {
    let onejan = new Date(this.getFullYear(), 0, 1);
    return Math.ceil((((this - onejan) / 86400000) + onejan.getDay() + 1) / 7);
};

//-