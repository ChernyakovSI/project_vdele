
let btnSpheres = document.getElementsByClassName('fin-acc-row');

let floatingCirclesGMain = document.getElementById('floatingCirclesGMain');

let dInfo = document.getElementById('info');
let dSet = document.getElementById('set-panel');
let vName = document.getElementById('valueName');
let dName = document.getElementById('valueNameWrap');

let btnSave = document.getElementById('button-save');

let listSpheres = document.getElementById('list-spheres');

$(document).ready( function() {
    arrSpheres = Array.from(btnSpheres);
    arrSpheres.forEach(function(item, i, arr) {
        item.onmousedown = function(e) {

            let thisData = {
                'id' : item.getAttribute('id'),
            };

            runAjax('/goal/spheres-get', thisData);
        }
    })

    btnSave.onclick = function(e) {
        let thisData = {
            'id' : vName.getAttribute('data-id'),
            'name' : vName.value,
        };

        runAjax('/goal/spheres-save', thisData);
    }
})


function runAjax(url, value, typeReq = 'post'){
    floatingCirclesGMain.hidden = false;

    $.ajax({
        type : typeReq,
        url : url,
        data : value
    }).done(function(data) {
        if (data.error === null || data.error === undefined) {
            //console.log(data);

        } else {
            if (data.error !== '' || data.error !== null || data.error !== undefined){
                //showError(data);
                //console.log(data);
                render(data);
            }
        }

        floatingCirclesGMain.hidden = true;
    }).fail(function() {
        //console.log(value);
        floatingCirclesGMain.hidden = true;
    });
}

function render(dataSet) {


    if(dataSet.data.length > 0){
        if (dInfo.classList.contains('visible-not') === false) {
            dInfo.classList.add('visible-not')
        }

        if (dSet.classList.contains('visible-not') === true) {
            dSet.classList.remove('visible-not')
        }
        vName.value = dataSet['data'][0]['name'];
        vName.setAttribute('data-id', dataSet['data'][0]['id']);

        dName.classList.forEach(function(item, i, arr) {
            if(item.indexOf('col-back-') + 1) {
                dName.classList.remove(item);
            }
        });

        dName.classList.add(dataSet['color']);
    }

    if(dataSet.AllSpheres.length > 0){
        dataSet.AllSpheres.forEach(function(item, i, arr) {

            let curElem = $('#'+item['id']);
            //console.log(curElem.find('.message-text-line'));
            curElem.find('.message-text-line')[0].innerText = item['name'];
        });
    }
}