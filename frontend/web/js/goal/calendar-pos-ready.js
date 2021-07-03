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
nowServer.setHours(nowServer.getHours() - currentTimeZoneOffset);

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

    render();
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

    render();
}

function render() {
    let isWork = false;
    let num = 0;

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

    for(let i=1; i<=42; i++) {
        let divDay = document.getElementById('day'+i);
        let divNDay = document.getElementById('nday'+i);

        clearColor(divDay);
        clearColor(divNDay);

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
        } else {
            divNDay.innerText = '';
            divDay.classList.add(ColorUnused);
        }
    }

    for(let i=1; i<=6; i++) {
        let divWeek = document.getElementById('num-week'+i);

        num = numStartWeek + i - 1;

        divWeek.innerText = num;
    }
}

function clearColor(divDay) {
    ColorNoneArr.forEach(curColor => divDay.classList.remove(curColor));
    divDay.classList.remove(ColorUnused);
    divDay.classList.remove('numberCircle');
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