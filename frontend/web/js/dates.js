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

function NowTimeStamp_Sec() {
    return Math.round(new Date().getTime()/1000)
}

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

function getTimeStampFromElement(element) {
    let curDate = new Date(element.value);
    return String(curDate.getTime()).substr(0, 10);
}

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