
function runAjax(url, value, floatingCirclesGMain = undefined, typeReq = 'post'){
    if(floatingCirclesGMain != undefined) {
        floatingCirclesGMain.hidden = false;
    }

    //++ 1-2-4-001 31/08/2022
    if(value.hasOwnProperty('files') == true) {
        delete value.files;
        restartAjax();
        return false;
    }
    //-- 1-2-4-001 31/08/2022

    //console.log(value)

    $.ajax({
        type : typeReq,
        url : url,
        data : value
    }).done(function(data) {
        //console.log(data);
        if (data.error === null || data.error === undefined || data.error === '') {

            //++ 1-2-4-002 10/10/2022
            //*-
            //render(data);
            //*+
            addParameters = {};
            addParameters.url = url;

            render(data, addParameters);
            //-- 1-2-4-002 10/10/2022

        } else {
            if (data.error !== '' || data.error !== null || data.error !== undefined){

                if(data.data['error'] !== null) {
                    showError(data.data);
                } else {

                    //++ 1-2-4-002 10/10/2022
                    //*-
                    //render(data);
                    //*+
                    addParameters = {};
                    addParameters.url = url;

                    render(data, addParameters);
                    //-- 1-2-4-002 10/10/2022
                }
            }
        }

        if(floatingCirclesGMain != undefined) {
            floatingCirclesGMain.hidden = true;
        }
    }).fail(function() {
        console.log('JError');
        console.log(data);
        if(floatingCirclesGMain != undefined) {
            floatingCirclesGMain.hidden = true;
        }
    });
}