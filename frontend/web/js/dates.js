let nowServer = new Date();
let currentTimeZoneOffset = nowServer.getTimezoneOffset()/60;
nowServer.setHours(nowServer.getHours() - currentTimeZoneOffset);

function cutMiliseconds (time) {

    time = time.toString();
    time = time.slice(0, -3);
    time = parseInt(time);

    return time;
}

function addMiliseconds (time) {

    time = time.toString();
    time = time + '000';
    time = parseInt(time);

    return time;
}

function beginDay (date) {

    date.setHours(0,0,0,0);

    return date;
}

function endDay (date) {

    date.setHours(23,59,59,999);

    return date;
}

function endDay_timestamp(timestamp) {
    let strDate = convertTimeStampWithTime(timestamp);
    let curDate = new Date(strDate);
    curDate = endDay(curDate);
    let endTimeStamp = Math.floor(curDate.getTime()/1000);

    return endTimeStamp;
}

//++ 1-2-3-004 26/07/2022
function beginMinute (date) {

    date.setSeconds(0);

    return date;
}

function beginMinute_timestamp(timestamp) {
    let strDate = convertTimeStampWithTime(timestamp);
    let curDate = new Date(strDate);
    curDate = beginMinute(curDate);
    let endTimeStamp = Math.floor(curDate.getTime()/1000);

    return endTimeStamp;
}
//-- 1-2-3-004 26/07/2022

function NowTimeStamp_Sec() {
    return Math.round(new Date().getTime()/1000)
}

//Получить представление даты с или без времени yyyy-mm-dd
function getStringDateFromTimeStamp(timeStamp, withTime = true) {
    let strDate = convertTimeStampWithTime(timeStamp);
    let curDate = new Date(strDate);
    curDate.setHours(curDate.getHours() - currentTimeZoneOffset);

    if(withTime == true) {
        result = curDate.toISOString().substring(0, 16);
    } else {
        result = curDate.toISOString().substring(0, 10);
    }
    return result;
}

//Получить представление даты с или без времени dd.mm.yyyy hh:mm
function getStringDateFromTimeStamp2(timestamp, withTime = true) {
    let condate;

    if (timestamp == '') {
        condate = new Date();
    } else {
        condate = new Date(timestamp*1000);
    }

    strDate = [
        ('0' + condate.getDate()).slice(-2),                          // Get full year
        ('0' + (condate.getMonth()+1)).slice(-2),      // Get month and pad it with zeroes
        condate.getFullYear()           // Get day and pad it with zeroes
    ].join('.');  // Glue the pieces together

    let strTime = '';
    if(withTime == true) {
        strDate = strDate + ' ';

        strTime = [
            ('0' + (condate.getHours())).slice(-2),
            ('0' + condate.getMinutes()).slice(-2)
        ].join(':');
    }

    return strDate+strTime;
}

function getDateFromTimeStamp(timeStamp) {
    let strDate = convertTimeStampWithTime(timeStamp);
    let curDate = new Date(strDate);
    curDate.setHours(curDate.getHours() - currentTimeZoneOffset);

    return curDate;
}

function getTimeStampFromElement(element) {
    let curDate = new Date(element.value);
    return String(curDate.getTime()).substr(0, 10);
}

//++ 1-3-1-003 21/02/2023
function getTimeStampFromElementTime(element) {
    let timest = Number(element.value.substring(0, 2))*60 +  Number(element.value.substring(3, 5));
    return String(timest);
}

function convertMinutesToTime(Minutes) {
    let hour = Math.floor(Number(Minutes)/60)
    let minute = Number(Minutes) % 60

    strTime = [
        ('0' + hour).slice(-2),
        ('0' + minute).slice(-2)
    ].join(':');

    return strTime;
}

function convertSecondsToTime(Seconds) {
    let hour = Math.floor(Number(Seconds)/3600)
    let SecondsInHour = Number(Seconds) % 3600
    let minute = Math.floor(Number(SecondsInHour)/60)

    strTime = [
        ('0' + hour).slice(-2),
        ('0' + minute).slice(-2)
    ].join(':');

    return strTime;
}

function getNowTimeStampWithoutDate() {
    timestamp = NowTimeStamp_Sec()
    timestamp = timestamp - (60*60*currentTimeZoneOffset)
    timestampTime = timestamp % (24*60*60)

    return convertSecondsToTime(timestampTime);
}
//-- 1-3-1-003 21/02/2023

function convertTimeStampWithTime(timestamp) {
    let condate;

    if (timestamp == '') {
        condate = new Date();
    } else {
        condate = new Date(timestamp*1000);
    }

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

//Получить представление времени hh:mm
function getStringTimeFromTimeStamp(timestamp) {
    let condate;

    if (timestamp == '') {
        condate = new Date();
    } else {
        condate = new Date(timestamp*1000);
    }

    strTime = [
        ('0' + (condate.getHours())).slice(-2),
        ('0' + condate.getMinutes()).slice(-2)
    ].join(':');

    return strTime;
}

