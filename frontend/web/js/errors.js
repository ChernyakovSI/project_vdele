let divRedComment = document.getElementById('red-comment');

function showError(data) {
    if(data.error == true) {
        if(data.message.length > 0) {
            divRedComment.hidden = false;
            divRedComment.innerHTML = '';
            data.message.forEach(function(item, i, arr) {
                //console.log(item);
                if (i == 0) {
                    divRedComment.innerHTML = item;
                } else {
                    divRedComment.innerHTML = divRedComment.innerHTML + '<br>' + item;
                }

            });
        }

        data.element.forEach(function(item, i, arr) {
            divWrapError = document.getElementById('value'+item+'Wrap');
            divWrapError.classList.add('redBorder');
        });



    } else {
        HideAllError();
    }

}

function HideError(data) {
    divRedComment.hidden = true;
    divRedComment.innerHTML = data.error;

    if(data['element'] !== null && data['element'] !== undefined) {
        let divWrap = document.getElementById('value'+data['element']+'Wrap');
        divWrap.classList.remove('redBorder');
    }
    else if(divWrapError !== null) {
        divWrapError.classList.remove('redBorder');
        divWrapError = null;
    }
}

function HideAllError() {
    divRedComment.hidden = true;
}