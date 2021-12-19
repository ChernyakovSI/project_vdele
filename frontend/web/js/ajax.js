
function runAjax(url, value, floatingCirclesGMain = undefined, typeReq = 'post'){
    if(floatingCirclesGMain != undefined) {
        floatingCirclesGMain.hidden = false;
    }

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

        if(floatingCirclesGMain != undefined) {
            floatingCirclesGMain.hidden = true;
        }
    }).fail(function() {
        //console.log(value);
        if(floatingCirclesGMain != undefined) {
            floatingCirclesGMain.hidden = true;
        }
    });
}