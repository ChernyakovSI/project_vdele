
function generatorURLs() {
    let ahrefs = document.getElementsByClassName('elem_href');
    //console.log(ahrefs);

    let arrHrefs = Array.from(ahrefs);
    arrHrefs.forEach(function(item, i, arr) {
        if (item.getAttribute('href') != '') {
            item.onclick = function() {
                window.open(item.getAttribute('href'), "_blank");
            }
            //++ 1-2-2-010 19/04/2022
            if(item.innerHTML.length > 100) {
                item.innerHTML = item.innerHTML.substring(0, 30) + '...' + item.innerHTML.substring(item.innerHTML.length - 30, item.innerHTML.length)
            }
            //-- 1-2-2-010 19/04/2022
        }
    });
}

function DetectURLs(element) {
    /*let url_regex = /(\b(https?|ftp|file):\/\/[\-A-Z0-9+&@#\/%?=~_|!:,.;]*[\-A-Z0-9+&@#\/%=~_|])/ig;
    element.innerHTML = element.innerHTML.replace(url_regex, '<a href="$1">$1</a>');
*/
    //++ 1-2-2-010 19/04/2022
    rebuildURL();
    //-- 1-2-2-010 19/04/2022

    let textHTML = element.innerText;

    //++ 1-2-2-012 24/04/2022
    let urlRegex = /&nbsp;/g;
    textHTML = textHTML.replace(urlRegex, ' ');

    //urlRegex = /(https?:\/\/[^\s]+)/g;
    urlRegex = getRegURL();
    textHTML = textHTML.replace(urlRegex, '<a href="$1" class="elem_href">$1</a>')
    //-- 1-2-2-012 24/04/2022

    //Сохраняем переносы в форме HTML
    //++ 1-2-2-011 21/04/2022
    //*-
    //let urlRegex = /(?:\r\n|\r|\n)/g;
    //*+
    //
    //++ 1-2-2-012 24/04/2022
    //*-
    //let urlRegex = getRegRN();
    //*+
    urlRegex = getRegRN();
    //-- 1-2-2-012 24/04/2022
    //-- 1-2-2-011 21/04/2022
    textHTML = textHTML.replace(urlRegex, '<br>');



    textHTML = deleteLastBr(textHTML);

    //++ 1-2-2-012 24/04/2022
    //-- 1-2-2-012 24/04/2022

    element.innerHTML = textHTML;
}

function convertNewLinesToBr(element) {
    //Конвертируем переносы создаваемые автоматически div в br
    let urlRegex = /<div><br><\/div>/g;
    let textHTML = element.innerHTML;
    textHTML = textHTML.replace(urlRegex, '<br>');

    //++ 1-2-2-011 21/04/2022
    urlRegex = /<br><\/div>/g;
    textHTML = textHTML.replace(urlRegex, '<\/div>');
    //-- 1-2-2-011 21/04/2022

    //Первый div заменить переносом
    //++ 1-2-2-010 19/04/2022
    //*-
    //urlRegex = /<div>/;
    //textHTML = textHTML.replace(urlRegex, '<br>');
    //*+
    while (textHTML.slice(0, 5) == '<div>') {
        textHTML = textHTML.substring(5, textHTML.length);

        urlRegex = /<\/div>/;
        textHTML = textHTML.replace(urlRegex, '<br>');
    }
    //-- 1-2-2-010 19/04/2022

    //Следующие
    urlRegex = /<div>/g;
    //++ 1-2-2-010 19/04/2022
    //*-
    //textHTML = textHTML.replace(urlRegex, '');
    //*+
    textHTML = textHTML.replace(urlRegex, '<br>');
    //-- 1-2-2-010 19/04/2022

    urlRegex = /<\/div>/g;
    //++ 1-2-2-010 19/04/2022
    //*-
    //textHTML = textHTML.replace(urlRegex, '<br>');
    //*+
    textHTML = textHTML.replace(urlRegex, '');
    //-- 1-2-2-010 19/04/2022

    //++ 1-2-2-010 19/04/2022
    //++ 1-2-2-012 24/04/2022
    //*-
    //urlRegex = /&nbsp;/g;
    //textHTML = textHTML.replace(urlRegex, '');
    //*+
    urlRegex = /&nbsp;/g;
    textHTML = textHTML.replace(urlRegex, ' ');
    //-- 1-2-2-012 24/04/2022
    //-- 1-2-2-010 19/04/2022

    //++ 1-2-2-011 21/04/2022
    //*-
    //urlRegex = /(?:\r\n|\r|\n)/g;
    //*+
    urlRegex = getRegRN();
    //-- 1-2-2-011 21/04/2022
    textHTML = textHTML.replace(urlRegex, '<br>');

    //++ 1-2-2-010 19/04/2022
    textHTML = deleteFirstBr(textHTML);
    //-- 1-2-2-010 19/04/2022

    //Удалить послдений br
    textHTML = deleteLastBr(textHTML);

    //++ 1-2-2-011 21/04/2022
    urlRegex = /<br><span/g;
    textHTML = textHTML.replace(urlRegex, '<span');

    //++ 1-2-2-012 24/04/2022
    urlRegex = /&nbsp;<a/g;
    textHTML = textHTML.replace(urlRegex, ' <a');
    //-- 1-2-2-012 24/04/2022

    //++ 1-2-2-012 24/04/2022
    //urlRegex = /&nbsp/g;
    //textHTML = textHTML.replace(urlRegex, '');
    //-- 1-2-2-012 24/04/2022

    //Заменить все br на обычные переносы, убрать все теги, вернуть br
    textHTML = getBrToNewLines_Text(textHTML);
    textHTML = cutTegs(textHTML);
    textHTML = getNewLinesToBr_Text(textHTML);
    //-- 1-2-2-011 21/04/2022

    element.innerHTML = textHTML;
}

function getBrToNewLines(element) {

    //Конвертируем переносы создаваемые автоматически br в div
    let urlRegex = /<br>/g;
    let textHTML = element.innerText;

    textHTML = deleteLastBr(textHTML);

    //++ 1-2-2-011 21/04/2022
    //*-
    //textHTML = textHTML.replace(urlRegex, '\\r\\n');
    //*+
    textHTML = textHTML.replace(urlRegex, '\r\n');
    //-- 1-2-2-011 21/04/2022

    return textHTML;
}

function getBrToNewLines_Text(textHTML) {
    //Конвертируем переносы создаваемые автоматически br в div
    let urlRegex = /<br>/g;

    textHTML = deleteLastBr(textHTML);

    //++ 1-2-2-011 21/04/2022
    //*-
    //textHTML = textHTML.replace(urlRegex, '\\r\\n');
    //*+
    textHTML = textHTML.replace(urlRegex, '\r\n');
    //-- 1-2-2-011 21/04/2022

    return textHTML;
}

function getNewLinesToBr(element) {
    //Конвертируем переносы создаваемые автоматически div в br
    //++ 1-2-2-011 21/04/2022
    //*-
    //let urlRegex = /(?:\r\n|\r|\n)/g;
    //*+
    let urlRegex = getRegRN();
    //-- 1-2-2-011 21/04/2022
    let textHTML = element.innerText;

    textHTML = textHTML.replace(urlRegex, '<br>');

    textHTML = deleteLastBr(textHTML);

    return textHTML;
}

function getNewLinesToBr_Text(textHTML) {
    //Конвертируем переносы создаваемые автоматически div в br
    //++ 1-2-2-011 21/04/2022
    //*-
    //let urlRegex = /(?:\r\n|\r|\n)/g;
    //*+
    let urlRegex = getRegRN();
    //-- 1-2-2-011 21/04/2022

    textHTML = textHTML.replace(urlRegex, '<br>');

    textHTML = deleteLastBr(textHTML);

    return textHTML;
}

function deleteLastBr(textHTML) {
    //Удалить послдений br
    while (textHTML.slice(-4) == '<br>') {
        textHTML = textHTML.substring(0, textHTML.length - 4);
    }

    return textHTML;
}

//++ 1-2-2-010 19/04/2022
function rebuildURL() {
    let ahrefs = document.getElementsByClassName('elem_href');

    let arrHrefs = Array.from(ahrefs);
    arrHrefs.forEach(function(item, i, arr) {
        if (item.getAttribute('href') != '') {
            item.innerHTML = item.getAttribute('href');
        }
    });
}

function deleteFirstBr(textHTML) {
    //Удалить послдений br
    while (textHTML.slice(4) == '<br>') {
        textHTML = textHTML.substring(4, textHTML.length);
    }

    return textHTML;
}
//-- 1-2-2-010 19/04/2022

//++ 1-2-2-011 21/04/2022
function cutTegs(textHTML) {
    let regex = /<\/?(span|div|img|p)\b[^<>]*>/g;
    textHTML = textHTML.replace(regex, '');

    return textHTML;
}

function getRegRN() {
    return /(?:\r\n|\r|\n)/g;
}
//-- 1-2-2-011 21/04/2022

//++ 1-2-2-012 24/04/2022
function getRegURL() {
    return /(((http|https|ftp|ftps):\/\/){1}([A-Za-zА-Яа-я0-9_-]+(\.)*(\/)*(\@)*(\#)*(\:)*(\?)*(\=)*(\&)*)+)/g;
}
//-- 1-2-2-012 24/04/2022