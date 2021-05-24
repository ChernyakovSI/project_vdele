let btnClearTag = document.getElementById('ClearTag');
let btnFind = document.getElementById('btnFind');
let btnReset = document.getElementById('btnReset');
let valueTag = document.getElementById('valueTag');
let listTags = document.getElementById('list_tags');
let tagsWraps = document.getElementsByClassName('wrapper-tag');

let btnClearFIO = document.getElementById('ClearFIO');
let valueFIO = document.getElementById('valueFIO');

let btnClearAge = document.getElementById('ClearAge');
let valueAgeFrom = document.getElementById('valueAgeFrom');
let valueAgeTo = document.getElementById('valueAgeTo');

let btnClearGender = document.getElementById('ClearGender');
let valueGender = document.getElementById('valueGender');

let btnClearCity = document.getElementById('ClearCity');
let valueCity = document.getElementById('valueCity');
let listCities = document.getElementById('list_cities');

$(document).ready( function() {

    arrTagWraps = Array.from(tagsWraps);
    arrTagWraps.forEach(function(item, i, arr) {
        item.onmousedown = function(e) {
            let children = item.childNodes;

            for(child in children){
                if((children[child].nodeName === 'DIV') && (children[child].classList.contains('tagname') === true)) {
                    window.location.href = '/users?tag=' + children[child].innerText;
                    break;
                }
            }
        }
    })

    btnClearFIO.onclick = function(e) {
        valueFIO.value = '';
    };

    btnClearGender.onclick = function(e) {
        valueGender.value = '';
    };

    btnClearTag.onclick = function(e) {
        valueTag.value = '';
    };

    btnClearCity.onclick = function(e) {
        valueCity.value = '';
    };

    btnClearAge.onclick = function(e) {
        valueAgeFrom.value = 0;
        valueAgeTo.value = 0;
    };

    valueAgeFrom.onfocus = function(e) {
        if(valueAgeFrom.value === '0') {
            valueAgeFrom.value = '';
        }
    };

    valueAgeTo.onfocus = function(e) {
        if(valueAgeTo.value === '0') {
            valueAgeTo.value = '';
        }
    };

    btnReset.onclick = function(e) {
        window.location.href = '/users';
    };

    btnFind.onclick = function(e) {
        let params = '';
        let sep = '?';

        let tagNameStr = valueTag.value.trim();
        if (tagNameStr !== '') {
            let children = listTags.childNodes;

            let wasFound = false;
            let curId = '';
            let curName = '';
            for(child in children){
                if((children[child].nodeName === 'OPTION') && (children[child].innerText === tagNameStr)) {
                    wasFound = true;
                    curId = children[child].getAttribute('data-id');
                    curName = children[child].getAttribute('data-name');
                    break;
                }
            }

            if(wasFound === false) {
                curId = '0';
                curName = tagNameStr.replace(/(^|\s)\S/g, function(a) {return a.toUpperCase()}); //Преобразовать в тег
                curName = curName.replace(/[^а-яА-Яa-zA-Z0-9 -]/, "").replace(/\s/g, "");
            }

            params = params + sep + 'tag=' + curName;
            sep = '&';
        }

        let FIOStr = valueFIO.value.trim();
        if (FIOStr !== '') {
            curFIO = FIOStr.replaceAll(" ", ",");
            params = params + sep + 'fio=' + curFIO;
            sep = '&';
        }

        let AgeFrom = valueAgeFrom.value;
        if (AgeFrom !== '' && AgeFrom !== '0') {
            params = params + sep + 'af=' + AgeFrom;
            sep = '&';
        }

        let AgeTo = valueAgeTo.value;
        if (AgeTo !== '' && AgeTo !== '0') {
            params = params + sep + 'at=' + AgeTo;
            sep = '&';
        }

        let GenderStr = valueGender.value.trim();
        if (GenderStr == 'Мужской') {
            curGender = 1;
            params = params + sep + 'gen=' + curGender;
            sep = '&';
        }
        else if (GenderStr == 'Женский') {
            curGender = 2;
            params = params + sep + 'gen=' + curGender;
            sep = '&';
        }
        else
        {
            valueGender.value = '';
        }

        let cityNameStr = valueCity.value;
        if (cityNameStr !== '') {
            let children = listCities.childNodes;

            let wasFound = false;
            let curId = '';
            let curName = '';
            for(child in children){
                if((children[child].nodeName === 'OPTION') && (children[child].innerText === cityNameStr)) {
                    wasFound = true;
                    curId = children[child].getAttribute('data-id');
                    curName = children[child].getAttribute('data-name');
                    break;
                }
            }

            if(wasFound === false) {
                curId = '0';
                curName = cityNameStr;
            }

            params = params + sep + 'city=' + curName;
            sep = '&';
        }

        window.location.href = '/users' + params;
    };
})