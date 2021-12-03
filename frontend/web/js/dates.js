

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