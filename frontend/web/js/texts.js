
function generatorURLs() {
    let ahrefs = document.getElementsByClassName('elem_href');
    //console.log(ahrefs);

    let arrHrefs = Array.from(ahrefs);
    arrHrefs.forEach(function(item, i, arr) {
        //console.log(item);
        if (item.getAttribute('href') != '') {
            item.onclick = function() {
                window.open(item.getAttribute('href'), "_blank");
            }
        }
    });
}

function DetectURLs(element) {
    /*let url_regex = /(\b(https?|ftp|file):\/\/[\-A-Z0-9+&@#\/%?=~_|!:,.;]*[\-A-Z0-9+&@#\/%=~_|])/ig;
    element.innerHTML = element.innerHTML.replace(url_regex, '<a href="$1">$1</a>');
*/
    let textHTML = element.innerText;

    //Сохраняем переносы в форме HTML
    let urlRegex = /(?:\r\n|\r|\n)/g;
    textHTML = textHTML.replace(urlRegex, ' <br>');

    textHTML = deleteLastBr(textHTML);

    urlRegex = /(https?:\/\/[^\s]+)/g;
    textHTML = textHTML.replace(urlRegex, '<a href="$1" class="elem_href">$1</a>')

    element.innerHTML = textHTML;
}

function convertNewLinesToBr(element) {
    //Конвертируем переносы создаваемые автоматически div в br
    let urlRegex = /<div><br><\/div>/g;
    let textHTML = element.innerHTML;
    textHTML = textHTML.replace(urlRegex, '<br>');

    //Первый div заменить переносом
    urlRegex = /<div>/;
    textHTML = textHTML.replace(urlRegex, '<br>');

    urlRegex = /<div>/g;
    textHTML = textHTML.replace(urlRegex, '');

    urlRegex = /<\/div>/g;
    textHTML = textHTML.replace(urlRegex, '<br>');

    urlRegex = /(?:\r\n|\r|\n)/g;
    textHTML = textHTML.replace(urlRegex, '<br>');

    //Удалить послдений br
    textHTML = deleteLastBr(textHTML);

    element.innerHTML = textHTML;
}

function getBrToNewLines(element) {
    //Конвертируем переносы создаваемые автоматически br в div
    let urlRegex = /<br>/g;
    let textHTML = element.innerText;

    textHTML = deleteLastBr(textHTML);

    textHTML = textHTML.replace(urlRegex, '\\r\\n');

    return textHTML;
}

function getNewLinesToBr(element) {
    //Конвертируем переносы создаваемые автоматически div в br
    let urlRegex = /(?:\r\n|\r|\n)/g;
    let textHTML = element.innerText;

    textHTML = textHTML.replace(urlRegex, '<br>');

    textHTML = deleteLastBr(textHTML);

    return textHTML;
}

function deleteLastBr(textHTML) {
    //Удалить послдений br
    while (textHTML.slice(-4) == '<br>') {
        console.log(textHTML);
        textHTML = textHTML.substring(0, textHTML.length - 4);
        console.log(textHTML);
    }

    return textHTML;
}