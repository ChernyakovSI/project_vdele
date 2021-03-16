let divExpense = document.getElementById('btn-expense');
let divProfit = document.getElementById('btn-profit');
let divReplacement = document.getElementById('btn-replacement');

let isExpense = +(divExpense.classList.contains('btn-active-expense'));
let isProfit = +(divProfit.classList.contains('btn-active-profit'));
let isReplacement = +(divReplacement.classList.contains('btn-active-replacement'));

let divParamPeriodFrom = document.getElementById('paramPeriodFrom');
let divParamPeriodTo = document.getElementById('paramPeriodTo');

let divRedComment = document.getElementById('red-comment');
let divWrapError = null;

let floatingCirclesGMain = document.getElementById('floatingCirclesGMain');
let floatingCirclesG = document.getElementById('floatingCirclesG');

let valuePeriodFrom = document.getElementById('valuePeriodFrom');
let valuePeriodTo = document.getElementById('valuePeriodTo');
let selValueAcc = document.getElementById('selValueAcc');
let selValueCat = document.getElementById('selValueCat');
let selValueSub = document.getElementById('selValueSub');

let selClearAcc = document.getElementById('selClearAcc');
let selClearCat = document.getElementById('selClearCat');
let selClearSub = document.getElementById('selClearSub');

let wrapSelCats = document.getElementById('wrapSelCats');

let textAcc = document.getElementById('textAcc');
let textAccTo = document.getElementById('textAccTo');
let textCat = document.getElementById('textCat');
let valueCat = document.getElementById('valueCat');

let nowServer = new Date();
let currentTimeZoneOffset = nowServer.getTimezoneOffset()/60;
nowServer.setHours(nowServer.getHours() - currentTimeZoneOffset);

let fromCaption =  document.getElementById('form-caption');
let valIsExpense = document.getElementById('isExpense');
let valIsProfit = document.getElementById('isProfit');
let valIsReplacement = document.getElementById('isReplacement');

let buttonAdd = document.getElementById('button-add');
let buttonDel = document.getElementById('button-del');

let curDateFrom = new Date(valuePeriodFrom.value);
let curDateTo = new Date(valuePeriodTo.value);
let thisData = {
    'id' : 0,
    'date' : 0,
    'type' : 0,
    'AccId' : 0,
    'AccName' : '',
    'CatId' : 0,
    'CatName' : '',
    'SubId' : 0,
    'SubName' : '',
    'Amount' : 0,
    'Com' : '',
    'AccToId' : 0,
    'AccToName' : '',
    'isExpense' : isExpense,
    'isProfit' : isProfit,
    'isReplacement' : isReplacement,
    'selPeriodFrom' : String(curDateFrom.getTime()).substr(0, 10),
    'selPeriodTo' : String(curDateTo.getTime()).substr(0, 10),
    'selAccId' : 0,
    'selAccName' : '',
};

$(document).ready( function() {
    let strDate = convertTimeStamp(divParamPeriodFrom.innerHTML);
    let curDate = new Date(strDate);
    curDate.setHours(curDate.getHours() - currentTimeZoneOffset);
    valuePeriodFrom.value = curDate.toISOString().substring(0, 10);

    strDate = convertTimeStamp(divParamPeriodTo.innerHTML);
    curDate = new Date(strDate);
    curDate.setHours(curDate.getHours() - currentTimeZoneOffset);
    valuePeriodTo.value = curDate.toISOString().substring(0, 10);

    resize();

    //Context Menu

    ///////////////////////////////////////
    ///////////////////////////////////////
    //
    // H E L P E R    F U N C T I O N S
    // ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ
    function clickInsideElement( e, className ) {
        let el = e.srcElement || e.target;

        if ( el.classList.contains(className) ) {
            return el;
        } else {
            while ( el = el.parentNode ) {
                if ( el.classList && el.classList.contains(className) ) {
                    return el;
                }
            }
        }

        return false;
    }

    function getPosition(e) {
        let posx = 0;
        let posy = 0;

        if (!e) {
            e = window.event;
        }

        if (e.pageX || e.pageY) {
            posx = e.pageX;
            posy = e.pageY;
        } else if (e.clientX || e.clientY) {
            posx = e.clientX + document.body.scrollLeft +
                document.documentElement.scrollLeft;
            posy = e.clientY + document.body.scrollTop +
                document.documentElement.scrollTop;
        }

        return {
            x: posx,
            y: posy
        }
    }

    ///////////////////////////////////////
    ///////////////////////////////////////
    //
    // C O R E    F U N C T I O N S
    // ФУНКЦИИ ЯДРА
    //
    ///////////////////////////////////////
    ///////////////////////////////////////

    /**
     * Variables.
     * Переменные.
     */
    let contextMenuClassName = 'context-menu';
    let contextMenuItemClassName = 'context-menu__item';
    let contextMenuLinkClassName  = 'context-menu__link';
    let activeClassName = "context-menu--active";

    let regItemClassName = 'fin-acc-row';
    let regItemInContext;

    let clickCoords;
    let clickCoordsX;
    let clickCoordsY;

    let menu = document.querySelector("#context-menu");
    let menuItems = menu.querySelectorAll(".context-menu__item");
    let menuState = 0;
    //Размеры меню
    let menuWidth;
    let menuHeight;
    let menuPosition;
    let menuPositionX;
    let menuPositionY;

    //Размеры окна
    let windowWidth;
    let windowHeight;



    /**
     * Initialise our application's code.
     * Инициализация кода нашего приложения.
     */
    function init() {
        contextListener();
        clickListener();
        keyupListener();

        resizeListener();
    }

    function resizeListener() {
        window.onresize = function(e) {
            toggleMenuOff();
        };
    }

    /**
     * Listens for contextmenu events.
     * Обработка события contextmenu.
     */
    function contextListener() {
        document.addEventListener( "contextmenu", function(e) {
            let regItemInContext1 = clickInsideElement( e, regItemClassName );

            if ( regItemInContext1 ) {
                e.preventDefault();
                toggleMenuOff();
                regItemInContext = regItemInContext1;
                toggleMenuOn();
                positionMenu(e);
            }
            else {
                toggleMenuOff();
            }
        });
    }

    /**
     * Listens for click events.
     * Обработка события click.
     */
    function clickListener() {
        document.addEventListener( "click", function(e) {

            let clickElIsLink = clickInsideElement( e, contextMenuLinkClassName );

            if( clickElIsLink ) {
                e.preventDefault();
                menuItemListener( clickElIsLink );
            }
            else {
                let button = e.which || e.button;
                if ( button === 1 ) {
                    toggleMenuOff();
                }
            }
        });
    }

    /**
     * Listens for keyup events.
     * Обработка события keyup.
     */
    function keyupListener() {
        window.onkeyup = function(e) {
            if ( e.keyCode === 27 ) {
                toggleMenuOff();
                }
            }

    }

    /**
     * Turns the custom context menu on.
     * Отображение контекстного меню.
     */
    function toggleMenuOn() {
        if ( menuState !== 1 ) {
            menuState = 1;
            menu.classList.add(activeClassName);

            //console.log(regItemInContext.getAttribute('id'));
            menu.setAttribute('data-id', regItemInContext.getAttribute('id'))

            regItemInContext.classList.add('underlined-back');
        }
    }

    function toggleMenuOff() {
        if ( menuState !== 0 ) {
            menuState = 0;
            menu.classList.remove(activeClassName);
            console.log('111')
        }
        if ( regItemInContext) {
            regItemInContext.classList.remove('underlined-back');
            regItemInContext = null;
            console.log('222')
        }
    }

    function positionMenu(e) {
        clickCoords = getPosition(e);
        clickCoordsX = clickCoords.x;
        clickCoordsY = clickCoords.y;

        menuWidth = menu.offsetWidth + 4;
        menuHeight = menu.offsetHeight + 4;

        windowWidth = window.innerWidth;
        windowHeight = window.innerHeight;

        if ( (windowWidth - clickCoordsX) < menuWidth ) {
            menu.style.left = windowWidth - menuWidth + "px";
        } else {
            menu.style.left = clickCoordsX + "px";
        }

        // if ( (windowHeight - clickCoordsY) < menuHeight ) {
        //     menu.style.top = windowHeight - menuHeight + "px";
        // } else {
            menu.style.top = clickCoordsY + "px";
        // }


    }

    function menuItemListener( link ) {
        let id = regItemInContext.getAttribute("id")

        console.log( "Reg ID - " +
            id +
            ", reg action - " + link.getAttribute("data-action"));
        toggleMenuOff();

        if(link.getAttribute("data-action") === 'Copy'){
            copyReg(id);
        }

    }

    /**
     * Run the app.
     * Запуск приложения.
     */
    init();

    // let regItems = document.querySelectorAll(".fin-acc-row");
    //
    // for ( let i = 0, len = regItems.length; i < len; i++ ) {
    //     let regItem = regItems[i];
    //     contextMenuListener(regItem);
    // }
    //
    // function contextMenuListener(el) {
    //     el.addEventListener( "contextmenu", function(e) {
    //         e.preventDefault();
    //         toggleMenuOn();
    //     });
    // }

});

valuePeriodFrom.onchange = function(event){
    readTable();
}

valuePeriodTo.onchange = function(event){
    readTable();
}

selValueAcc.onchange = function(event) {
    readTable();
};

selClearAcc.onclick = function(e) {
    selValueAcc.value = '';

    readTable();
};

selValueCat.onchange = function(event) {
    readTable();
};

selClearCat.onclick = function(e) {
    selValueCat.value = '';

    readTable();
};

selValueSub.onchange = function(event) {
    readTable();
};

selClearSub.onclick = function(e) {
    selValueSub.value = '';

    readTable();
};

function resize() {
    let divListRegs = document.getElementById('list-register');
    let children = divListRegs.childNodes;
    let divRow;

    let colDate, colAcc, colCat, colSub, colSum, colCom;

    for(child in children){
        divRow = children[child].childNodes;
        let maxHeight = 0;
        for(column in divRow){
            if (divRow.length > 0){
                if(divRow[column].nodeName === 'DIV' & (' ' + divRow[column].className + ' ').indexOf('fin-reg-date') > -1) {
                    colDate = divRow[column];
                    if (colDate.clientHeight > maxHeight){
                        maxHeight = colDate.clientHeight;
                    }
                }
                if(divRow[column].nodeName === 'DIV' & (' ' + divRow[column].className + ' ').indexOf('fin-reg-acc') > -1) {
                    colAcc = divRow[column];
                    if (colAcc.clientHeight > maxHeight){
                        maxHeight = colAcc.clientHeight;
                    }
                }
                if(divRow[column].nodeName === 'DIV' & (' ' + divRow[column].className + ' ').indexOf('fin-reg-cat') > -1) {
                    colCat = divRow[column];
                    if (colCat.clientHeight > maxHeight){
                        maxHeight = colCat.clientHeight;
                    }
                }
                if(divRow[column].nodeName === 'DIV' & (' ' + divRow[column].className + ' ').indexOf('fin-reg-sub') > -1) {
                    colSub = divRow[column];
                    if (colSub.clientHeight > maxHeight){
                        maxHeight = colSub.clientHeight;
                    }
                }
                if(divRow[column].nodeName === 'DIV' & (' ' + divRow[column].className + ' ').indexOf('fin-reg-amount') > -1) {
                    colSum = divRow[column];
                    if (colSum.clientHeight > maxHeight){
                        maxHeight = colSum.clientHeight;
                    }
                }
                if(divRow[column].nodeName === 'DIV' & (' ' + divRow[column].className + ' ').indexOf('fin-reg-com') > -1) {
                    colCom = divRow[column];
                    if (colCom.clientHeight > maxHeight){
                        maxHeight = colCom.clientHeight;
                    }
                }
            }

        }

        if(colDate !== undefined) {
            colDate.style.height = maxHeight + 'px';
        }
        if(colAcc !== undefined) {
            colAcc.style.height = maxHeight + 'px';
        }
        if(colCat !== undefined) {
            colCat.style.height = maxHeight + 'px';
        }
        if(colSub !== undefined) {
            colSub.style.height = maxHeight + 'px';
        }
        if(colSum !== undefined) {
            colSum.style.height = maxHeight + 'px';
        }
        if(colCom !== undefined) {
            colCom.style.height = maxHeight + 'px';
        }

        colDate = undefined;
        colAcc = undefined;
        colCat = undefined;
        colSub = undefined;
        colSum = undefined;
        colCom = undefined;
    }
}

function showError(data) {
    divRedComment.hidden = false;
    divRedComment.innerHTML = data.error;

    if(data['element'] !== null || data['element'] !== undefined) {

        divWrapError = document.getElementById('value'+data['element']+'Wrap');
        divWrapError.classList.add('redBorder');
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

divExpense.onclick = function(e){
     if(isExpense === 0){
        isExpense = 1;
        divExpense.classList.add('btn-active-expense');
    }
    else
    {
        isExpense = 0;
        divExpense.classList.remove('btn-active-expense');
    }

    visiblePanels();
    readTable();
};

divProfit.onclick = function(e){
    let divProfit = document.getElementById('btn-profit');

    if(isProfit === 0){
        isProfit = 1;
        divProfit.classList.add('btn-active-profit');
    }
    else
    {
        isProfit = 0;
        divProfit.classList.remove('btn-active-profit');
    }

    visiblePanels();
    readTable();
};

divReplacement.onclick = function(e){
    let divReplacement = document.getElementById('btn-replacement');

    if(isReplacement === 0){
        isReplacement = 1;
        divReplacement.classList.add('btn-active-replacement');
    }
    else
    {
        isReplacement = 0;
        divReplacement.classList.remove('btn-active-replacement');
    }

    visiblePanels();
    readTable();
};

function visiblePanels(){
    if(isExpense === 1 || isProfit === 1){
        wrapSelCats.hidden = false;
    }
    else
    {
        wrapSelCats.hidden = true;
    }
}

function readTable(){

    curDateFrom = new Date(valuePeriodFrom.value);
    curDateTo = new Date(valuePeriodTo.value);
    curDateTo.setHours(23,59,59,999);

    let curSelAccName = selValueAcc.value;
    let curSelValueCat = selValueCat.value;
    let curSelValueSub = selValueSub.value;

    value = {
        'isExpense' : isExpense,
        'isProfit' : isProfit,
        'isReplacement' : isReplacement,
        'selPeriodFrom' : String(curDateFrom.getTime()).substr(0, 10),
        'selPeriodTo' : String(curDateTo.getTime()).substr(0, 10),
        'selAccId' : 0,
        'selAccName' : curSelAccName,
        'selCatId' : 0,
        'selCatName' : curSelValueCat,
        'selSubId' : 0,
        'selSubName' : curSelValueSub,
    };

    runAjax('/fin/register', value, 0)
}

function runAjax(url, value, mode = 1, loader = 0, typeReq = 'post'){
    if(loader === 0){
        floatingCirclesGMain.hidden = false;
    }
    else{
        floatingCirclesG.hidden = true;
    }

    $.ajax({
        type : typeReq,
        url : url,
        data : value
    }).done(function(data) {
        if (data.error === null || data.error === undefined) {
            if(mode === 1){
                deleteForm();
            }
            if(mode === 0 || mode === 1){
                rerender(data);
            }
            if(mode === 2){
                rerenderListCats(data.categories);
                rerenderListSubs(data.subs);
                fullData(data);
            }
            if(mode === 3){
                textAcc.innerHTML = '(' + data.data.sum + ')';
            }
            if(mode === 4){
                textAccTo.innerHTML = '(' + data.data.sum + ')';
            }
            if(mode === 5){
                textCat.innerHTML = '';
                rerenderListSubs(data.subs);
            }
            if(mode === 6){
                textCat.innerHTML = '';
                valueCat.value = '';
                rerenderListCats(data.categories);
            }

        } else {
            if (data.error !== '' || data.error !== null || data.error !== undefined){
                if(mode === 1 || mode === 2 || mode === 3 || mode === 4 || mode === 5 || mode === 6){
                    showError(data);
                }
            }
        }

        if(loader === 0) {
            floatingCirclesGMain.hidden = true;
        }else{
            floatingCirclesG.hidden = true;
        }
    }).fail(function() {
        if(loader === 0) {
            floatingCirclesGMain.hidden = true;
        }else{
            floatingCirclesG.hidden = true;
        }
    });
}

function minType() {
    if(isExpense === 1){
        return 0;
    }
    if(isProfit === 1){
        return 1;
    }
    if(isReplacement === 1){
        return 2;
    }
}

function addReg() {
    showFormNew(0, minType(), function(value) {
        if (value !== null) {
            runAjax('/fin/reg-add', value, 1)
        }
    });
}

function editReg(id) {
    showFormNew(id, 0, function(value) {
        if (value !== null) {
            runAjax('/fin/reg-edit', value, 1)
        }
    });
}

function copyReg(id) {
    showFormNew(id, 4, function(value) {
        if (value !== null) {
            runAjax('/fin/reg-add', value, 1)
        }
    });
}

function deleteReg(thisData) {
    runAjax('/fin/reg-delete', thisData, 1)
}

function showCover() {
    let coverDiv = document.createElement('div');
    coverDiv.id = 'cover-div';

    coverDiv.classList.remove('form-on');

    let container = document.getElementById('prompt-form-container');
    container.style.display = 'block';

    // убираем возможность прокрутки страницы во время показа модального окна с формой
    document.body.style.overflowY = 'hidden';

    document.body.append(coverDiv);
}

function hideCover() {
    let coverDiv = document.getElementById('cover-div')

    //coverDiv.classList.remove('form-on');
    //coverDiv.classList.add('form-off');

    coverDiv.remove();
    document.body.style.overflowY = '';
}

function deleteForm(){
    let container = document.getElementById('prompt-form-container');

    hideCover();
    container.style.display = 'none';
    document.onkeydown = null;
}

function showFormNew(id, type, callback) {
    showCover();
    let form = document.getElementById('prompt-form');

    form.classList.add('form-on');

    let container = document.getElementById('prompt-form-container');
    let btnClose = document.getElementById('btnClose');
    let valueDate = document.getElementById('valueDate');
    let valueAmo = document.getElementById('valueAmo');
    let valueAcc = document.getElementById('valueAcc');
    let valueAccTo = document.getElementById('valueAccTo');
    let valueSub = document.getElementById('valueSub');
    let valueCom = document.getElementById('valueCom');

    let ClearAcc = document.getElementById('ClearAcc');
    let ClearAccTo = document.getElementById('ClearAccTo');
    let ClearCat = document.getElementById('ClearCat');
    let textSub = document.getElementById('textSub');
    let ClearSub = document.getElementById('ClearSub');

    let fieldAccTo = document.getElementById('fieldAccTo');
    let fieldCat = document.getElementById('fieldCat');
    let fieldSub = document.getElementById('fieldSub');
    let fieldAcc = document.getElementById('fieldAcc');

    let isCopy = type === 4;

    divRedComment = document.getElementById('red-comment');
    divRedComment.hidden = true;

    thisData = {
        'id' : 0,
        'date' : 0,
        'type' : 0,
        'AccId' : 0,
        'AccName' : '',
        'CatId' : 0,
        'CatName' : '',
        'SubId' : 0,
        'SubName' : '',
        'Amount' : 0,
        'Com' : '',
        'AccToId' : 0,
        'AccToName' : '',
        'isExpense' : isExpense,
        'isProfit' : isProfit,
        'isReplacement' : isReplacement,
        'selPeriodFrom' : String(curDateFrom.getTime()).substr(0, 10),
        'selPeriodTo' : String(curDateTo.getTime()).substr(0, 10),
        'selAccId' : 0,
        'selAccName' : '',
    };

    valueAcc.value = '';
    valueAccTo.value = '';
    valueCat.value = '';
    valueSub.value = '';
    valueAmo.value = '';
    valueCom.innerHTML = '';

    textAcc.innerHTML = '';
    textAccTo.innerHTML = '';
    textCat.innerHTML = '';
    textSub.innerHTML = '';

    if(id === 0){
        if(type === 0){
            fromCaption.innerHTML = 'Новый расход';
            valIsExpense.checked = true;
            thisData['type'] = 0;

            fieldAccTo.hidden = true;
            fieldCat.hidden = false;
            fieldSub.hidden = false;

            fieldAcc.innerHTML = 'Счет';

            value = {
                'isProfit' : thisData.isProfit,
                'id_category' : thisData.CatId,
            };

            runAjax('/fin/categories', value, 6, 1);
        }
        else if(type === 1){
            fromCaption.innerHTML = 'Новый доход';
            valIsProfit.checked = true;
            thisData['type'] = 1;

            fieldAccTo.hidden = true;
            fieldCat.hidden = false;
            fieldSub.hidden = false;

            fieldAcc.innerHTML = 'Счет';

            value = {
                'isProfit' : thisData.isProfit,
                'id_category' : thisData.CatId,
            };

            runAjax('/fin/categories', value, 6, 1);
        }
        else
        {
            fromCaption.innerHTML = 'Новое перемещение';
            valIsReplacement.checked = true;
            thisData['type'] = 2;

            fieldAccTo.hidden = false;
            fieldCat.hidden = true;
            fieldSub.hidden = true;

            fieldAcc.innerHTML = 'Со счета';
        }

        valueDate.value = nowServer.toISOString().substring(0, 16);
        let curDate = new Date(valueDate.value);
        thisData['date'] = String(curDate.getTime()).substr(0, 10);

        floatingCirclesG.hidden = true;
        buttonDel.hidden = true;

        buttonAdd.onclick = function(e) {
            initBtnConfirm();
        };
    }
    else
    {
        buttonDel.hidden = false;

        floatingCirclesG.hidden = false;
        fromCaption.innerHTML = 'Редактирование движения';

        buttonAdd.onclick = null;
        buttonDel.onclick = null;

        thisData['id'] = id;

        runAjaxForm('/fin/reg-get', {id : id}, 2, 1)
    }

    function initBtnConfirm() {
        curDateFrom = new Date(valuePeriodFrom.value);
        curDateTo = new Date(valuePeriodTo.value);
        curDateTo.setHours(23,59,59,999);

        let curSelAccName = selValueAcc.value;

        thisData['selPeriodFrom'] = String(curDateFrom.getTime()).substr(0, 10);
        thisData['selPeriodTo'] = String(curDateTo.getTime()).substr(0, 10);
        thisData['selAccId'] = 0;
        thisData['selAccName'] = curSelAccName;

        complete(thisData);
    }

    function complete(value) {
        callback(value);
    }

    document.onkeydown = function(e) {
        if (e.key == 'Escape') {
            complete(null);
            deleteForm();
        }
    };

    function runAjaxForm(url, value, mode = 2, loader = 0, typeReq = 'post'){
        if(loader === 0){
            floatingCirclesGMain.hidden = false;
        }
        else{
            floatingCirclesG.hidden = true;
        }

        $.ajax({
            type : typeReq,
            url : url,
            data : value
        }).done(function(data) {
            if (data.error === null || data.error === undefined) {
                if(mode === 2){
                    rerenderListCats(data.categories);
                    rerenderListSubs(data.subs);
                    fullData(data);
                }

            } else {
                if (data.error !== '' || data.error !== null || data.error !== undefined){
                    if(mode === 2){
                        showError(data);
                    }
                }
            }

            if(loader === 0) {
                floatingCirclesGMain.hidden = true;
            }else{
                floatingCirclesG.hidden = true;
            }
        }).fail(function() {
            if(loader === 0) {
                floatingCirclesGMain.hidden = true;
            }else{
                floatingCirclesG.hidden = true;
            }
        });
    }


    valueAmo.onchange = function(event) {
        this.value = Number(this.value).toFixed(2);

        data = {
            'element': 'Amo',
        };
        HideError(data);

        thisData['Amount'] = Number(this.value);
    }

    btnClose.onclick = function(e) {
        closeFrom();
    };

    container.ondblclick = function(e) {
        if (event.defaultPrevented) return;

        closeFrom();
    };

    form.ondblclick = function(e) {
        e.preventDefault();
        return false;
    };

    function closeFrom() {
        complete(null);
        deleteForm();
    }

    valueAcc.onchange = function(event) {
        data = {
            'element': 'Acc',
        };
        HideError(data);

        let nameAcc = this.value.trim();
        let idAcc = 0;

        let idList = this.getAttribute('list');
        let divList = document.getElementById(idList);

        let children = divList.childNodes;

        for(column in children){
            if(children[column].nodeName === 'OPTION' && children[column].innerText === nameAcc) {
                idAcc = children[column].getAttribute('data-id');
                break;
            }
        }

        let value = {
            'name' : nameAcc,
            'id' : idAcc
        };
        if((value['id'] === 0) && (value['name'] !== '')){
            textAcc.innerHTML = 'Будет создан новый счет!';
        }
        else if (value['id'] > 0) {
            runAjax('/fin/accounts-get', value, 3, 1);
        }
        else
        {
            textAcc.innerHTML = '';
        }

        thisData['AccId'] = value['id'];
        thisData['AccName'] = value['name'];
    };

    ClearAcc.onclick = function(e) {
        data = {
            'element': 'Acc',
        };
        HideError(data);

        valueAcc.value = '';
        textAcc.innerHTML = '';

        thisData['AccId'] = 0;
        thisData['AccName'] = '';
    };

    valueAccTo.onchange = function(event) {
        data = {
            'element': 'AccTo',
        };
        HideError(data);

        let nameAcc = this.value.trim();
        let idAcc = 0;

        let idList = this.getAttribute('list');
        let divList = document.getElementById(idList);

        let children = divList.childNodes;

        for(column in children){
            if(children[column].nodeName === 'OPTION' && children[column].innerText === nameAcc) {
                idAcc = children[column].getAttribute('data-id');
                break;
            }
        }

        let value = {
            'name' : nameAcc,
            'id' : idAcc
        };
        if((value['id'] === 0) && (value['name'] !== '')){
            textAccTo.innerHTML = 'Будет создан новый счет!';
        }
        else if (value['id'] > 0) {
            runAjax('/fin/accounts-get', value, 4, 1);
        }
        else
        {
            textAccTo.innerHTML = '';
        }

        thisData['AccToId'] = value['id'];
        thisData['AccToName'] = value['name'];
    };

    ClearAccTo.onclick = function(e) {
        data = {
            'element': 'AccTo',
        };
        HideError(data);

        valueAccTo.value = '';
        textAccTo.innerHTML = '';

        thisData['AccToId'] = 0;
        thisData['AccToName'] = '';
    };

    valueCat.onchange = function(event) {
        data = {
            'element': 'Cat',
        };
        HideError(data);

        let nameCat = this.value.trim();
        let idCat = 0;

        let idList = this.getAttribute('list');
        let divList = document.getElementById(idList);

        let children = divList.childNodes;

        for(column in children){
            if(children[column].nodeName === 'OPTION' && children[column].innerText === nameCat) {
                idCat = children[column].getAttribute('data-id');
                break;
            }
        }

        let value = {
            'name' : nameCat,
            'id' : idCat
        };
        if((value['id'] === 0) && (value['name'] !== '')){
            textCat.innerHTML = 'Будет создана новая категория!';
            rerenderListSubs([]);
            textSub.innerHTML = '';
            valueSub.value = '';
        }
        else if (value['id'] > 0) {
            runAjax('/fin/cat-get', value, 5, 1);
        }
        else
        {
            textCat.innerHTML = '';
            rerenderListSubs([]);
            textSub.innerHTML = '';
            valueSub.value = '';
        }

        thisData['CatId'] = value['id'];
        thisData['CatName'] = value['name'];
        thisData['SubId'] = 0;
        thisData['SubName'] = '';
    };

    ClearCat.onclick = function(e) {
        data = {
            'element': 'Cat',
        };
        HideError(data);

        valueCat.value = '';
        textCat.innerHTML = '';

        valueSub.value = '';
        textSub.innerHTML = '';

        thisData['CatId'] = 0;
        thisData['CatName'] = '';
        thisData['SubId'] = 0;
        thisData['SubName'] = '';

        rerenderListSubs([]);
    };

    valueSub.onchange = function(event) {
        data = {
            'element': 'Sub',
        };
        HideError(data);

        let nameSub = this.value.trim();
        let idSub = 0;

        let idList = this.getAttribute('list');
        let divList = document.getElementById(idList);

        let children = divList.childNodes;

        for(column in children){
            if(children[column].nodeName === 'OPTION' && children[column].innerText === nameSub) {
                idSub = children[column].getAttribute('data-id');
                break;
            }
        }

        if((idSub === 0) && (nameSub !=='')){
            textSub.innerHTML = 'Будет создана новая подкатегория!';
        }
        else
        {
            textCat.innerHTML = '';
        }

        thisData['SubId'] = idSub;
        thisData['SubName'] = nameSub;
    };

    ClearSub.onclick = function(e) {
        data = {
            'element': 'Sub',
        };
        HideError(data);

        valueSub.value = '';
        textSub.innerHTML = '';

        thisData['SubId'] = 0;
        thisData['SubName'] = '';
    };

    valIsExpense.onchange = function(event) {
        data = {};
        HideError(data);

        if(this.checked === true) {
            value = {
                'isProfit' : 0,
                'id_category' : 0,
            };

            runAjax('/fin/categories', value, 6, 1);

            rerenderListSubs([]);
            textSub.innerHTML = '';
            valueSub.value = '';

            thisData['type'] = 0;
            if(thisData['id'] === 0) {
                fromCaption.innerHTML = 'Новый расход';
            }
            else
            {
                fromCaption.innerHTML = 'Редактирование расход';
            }

            fieldAccTo.hidden = true;
            fieldCat.hidden = false;
            fieldSub.hidden = false;

            fieldAcc.innerHTML = 'Счет';
        }
    };

    valIsProfit.onchange = function(event) {
        data = {};
        HideError(data);
        if(this.checked === true) {
            value = {
                'isProfit' : 1,
                'id_category' : 0,
            };

            runAjax('/fin/categories', value, 6, 1);

            rerenderListSubs([]);
            textSub.innerHTML = '';
            valueSub.value = '';

            thisData['type'] = 1;
            if(thisData['id'] === 0) {
                fromCaption.innerHTML = 'Новый доход';
            }
            else
            {
                fromCaption.innerHTML = 'Редактирование дохода';
            }

            fieldAccTo.hidden = true;
            fieldCat.hidden = false;
            fieldSub.hidden = false;

            fieldAcc.innerHTML = 'Счет';
        }
    };

    valIsReplacement.onchange = function(event) {
        data = {};
        HideError(data);
        if(this.checked == true) {
            thisData['type'] = 2;
            if(thisData['id'] === 0) {
                fromCaption.innerHTML = 'Новое перемещение';
            }
            else
            {
                fromCaption.innerHTML = 'Редактирование перемещения';
            }

            fieldAccTo.hidden = false;
            fieldCat.hidden = true;
            fieldSub.hidden = true;

            fieldAcc.innerHTML = 'Со счета';
        }
    };

    valueDate.onchange = function(event){
        let curDate = new Date(this.value);
        thisData['date'] = String(curDate.getTime()).substr(0, 10);
    };

    valueCom.onblur = function(event){
        thisData['Com'] = this.innerHTML;
    };

    function fullData(data) {

        if(isCopy === false) {
            let strDate = convertTimeStampWithTime(data.data.date);
            let curDate = new Date(strDate);
            curDate.setHours(curDate.getHours() - currentTimeZoneOffset);
            valueDate.value = curDate.toISOString().substring(0, 16);
            thisData['date'] = data.data.date;
        } else {
            valueDate.value = nowServer.toISOString().substring(0, 16);
            let curDate = new Date(valueDate.value);
            thisData['date'] = String(curDate.getTime()).substr(0, 10);
        }

        valueAcc.value = data.data.AccName;
        thisData['AccId'] = data.data.AccId;
        thisData['AccName'] = data.data.AccName;

        console.log(data.data.type);

        if (data.data.type !== 2) {
            if (data.data.type === 0) {
                if(isCopy === false) {
                    fromCaption.innerHTML = 'Редактирование расхода';
                }
                else {
                    fromCaption.innerHTML = 'Создание расхода';
                }

                valIsExpense.checked = true;
                thisData['type'] = 0;
            }
            else {
                if(isCopy === false) {
                    fromCaption.innerHTML = 'Редактирование дохода';
                }
                else {
                    fromCaption.innerHTML = 'Создание дохода';
                }
                valIsProfit.checked = true;
                thisData['type'] = 1;
            }

            valueCat.value = data.data.CatName;
            thisData['CatId'] = data.data.CatId;
            thisData['CatName'] = data.data.CatName;

            valueSub.value = data.data.SubName;
            thisData['SubId'] = data.data.SubId;
            thisData['SubName'] = data.data.SubName;

            fieldAccTo.hidden = true;
            fieldCat.hidden = false;
            fieldSub.hidden = false;

            fieldAcc.innerHTML = 'Счет';
        }
        else
        {
            if(isCopy === false) {
                fromCaption.innerHTML = 'Редактирование перемещения';
            }
            else {
                fromCaption.innerHTML = 'Создание перемещения';
            }
            valIsReplacement.checked = true;
            thisData['type'] = 2;

            fieldAccTo.hidden = false;
            fieldCat.hidden = true;
            fieldSub.hidden = true;

            fieldAcc.innerHTML = 'Со счета';

            valueAccTo.value = data.data.AccToName;
            thisData['AccToId'] = data.data.AccToId;
            thisData['AccToName'] = data.data.AccToName;
        };

        valueAmo.value = data.data.Amount;
        thisData['Amount'] = data.data.Amount;

        valueCom.innerHTML = data.data.Com;
        thisData['Com'] = data.data.Com;

        buttonAdd.onclick = function(e) {
            initBtnConfirm();
        };

        if( isCopy === false ) {
            buttonDel.onclick = function(e) {
                let ans = confirm('Удалить движение?');

                if(ans === true) {
                    curDateFrom = new Date(valuePeriodFrom.value);
                    curDateTo = new Date(valuePeriodTo.value);
                    curDateTo.setHours(23,59,59,999);

                    let curSelAccName = selValueAcc.value;

                    thisData['selPeriodFrom'] = String(curDateFrom.getTime()).substr(0, 10);
                    thisData['selPeriodTo'] = String(curDateTo.getTime()).substr(0, 10);
                    thisData['selAccId'] = 0;
                    thisData['selAccName'] = curSelAccName;

                    deleteReg(thisData);
                }

                return 1;
            };
        } else {
            buttonDel.hidden = true;
        }

    }

    //container.style.display = 'block';
}


function rerenderListSubs(dataSet) {
    let listSubs = document.getElementById('list_subcategories');
    listSubs.innerHTML = '';

    if(dataSet.length > 0){
        dataSet.forEach(function(data, i, arrData){
            let divOpt = document.createElement('option');
            divOpt.setAttribute('data-id', data['id']);
            divOpt.innerHTML = data['name'];

            listSubs.append(divOpt);
        });
    }
}

function rerenderListCats(dataSet) {
    let listSubs = document.getElementById('list_categories');
    listSubs.innerHTML = '';

    if(dataSet.length > 0){
        dataSet.forEach(function(data, i, arrData){
            let divOpt = document.createElement('option');
            divOpt.setAttribute('data-id', data['id']);
            divOpt.innerHTML = data['name'];

            listSubs.append(divOpt);
        });
    }
}

function rerender(dataSet) {
    let listReg = document.getElementById('list-register');
    listReg.innerHTML = '';

    let listCatsSel = document.getElementById('list_cats_sel');
    listCatsSel.innerHTML = '';

    let listSubsSel = document.getElementById('list_subs_sel');
    listSubsSel.innerHTML = '';

    let SumFormat = dataSet.SumFormat;

    if(dataSet.cats !== null && dataSet.cats !== undefined && dataSet.cats.length > 0){
        dataSet.cats.forEach(function(data, i, arrData){
            let divOpt = document.createElement('option');
            divOpt.setAttribute('data-id', data['id']);
            divOpt.innerHTML = data['name'];

            listCatsSel.append(divOpt);
        });
    }

    if(dataSet.subs !== null && dataSet.subs !== undefined && dataSet.subs.length > 0){
        dataSet.subs.forEach(function(data, i, arrData){
            let divOpt = document.createElement('option');
            divOpt.setAttribute('data-id', data['id']);
            divOpt.innerHTML = data['name'];

            listSubsSel.append(divOpt);
        });
    }

    if(dataSet.data.length > 0){
        dataSet.data.forEach(function(data, i, arrData){
            let divRow = document.createElement('div');
            if (data['id_type'] === '0') {
                divRow.className = 'fin-acc-row expense-back interactive-only';
            } else if(data['id_type'] === '1') {
                divRow.className = 'fin-acc-row profit-back interactive-only';
            } else {
                divRow.className = 'fin-acc-row movement-back interactive-only';
            }
            divRow.setAttribute('id', data['id']);
            divRow.addEventListener('dblclick', function() {
                editReg(data['id']);
            }, false);

            let divMainDate = document.createElement('div');
            divMainDate.className = 'fin-reg-date table-text';

            let divWrapDate = document.createElement('div');
            divWrapDate.className = 'message-wrapper-title';

            let divTextDate = document.createElement('div');
            divTextDate.className = 'message-text-line';

            divTextDate.innerHTML = convertTimeStampVisible(data['date']);

            divWrapDate.append(divTextDate);
            divMainDate.append(divWrapDate);
            divRow.append(divMainDate);


            let divMainAcc = document.createElement('div');
            divMainAcc.className = 'fin-reg-acc table-text';

            let divWrapAcc = document.createElement('div');
            divWrapAcc.className = 'message-wrapper-title';

            let divTextAcc = document.createElement('div');
            divTextAcc.className = 'message-text-line';
            divTextAcc.innerHTML = data['AccName'];

            divWrapAcc.append(divTextAcc);
            divMainAcc.append(divWrapAcc);
            divRow.append(divMainAcc);

            let divMainCat = document.createElement('div');
            divMainCat.className = 'fin-reg-cat table-text';

            let divWrapCat = document.createElement('div');
            divWrapCat.className = 'message-wrapper-title';

            let divTextCat = document.createElement('div');
            divTextCat.className = 'message-text-line';

            if (data['id_type'] !== '2') {
                divTextCat.innerHTML = data['CatName'];
            }
            else
            {
                divTextCat.innerHTML = data['AccToName'];
            }

            divWrapCat.append(divTextCat);
            divMainCat.append(divWrapCat);
            divRow.append(divMainCat);


            let divMainSub = document.createElement('div');
            divMainSub.className = 'fin-reg-sub table-text';

            let divWrapSub = document.createElement('div');
            divWrapSub.className = 'message-wrapper-title';

            let divTextSub = document.createElement('div');
            divTextSub.className = 'message-text-line';

            if (data['id_type'] !== '2') {
                divTextSub.innerHTML = data['SubName'];
            }
            else
            {
                divTextSub.innerHTML = '';
            }

            divWrapSub.append(divTextSub);
            divMainSub.append(divWrapSub);
            divRow.append(divMainSub);


            let divMainAmount = document.createElement('div');
            divMainAmount.className = 'fin-reg-amount table-text';

            let divWrapAmount = document.createElement('div');
            divWrapAmount.className = 'message-wrapper-title';

            let divTextAmount = document.createElement('div');
            divTextAmount.className = 'message-text-line right-text';
            divTextAmount.innerHTML = SumFormat[data['id']];

            divWrapAmount.append(divTextAmount);
            divMainAmount.append(divWrapAmount);
            divRow.append(divMainAmount);

            let divMainComment = document.createElement('div');
            divMainComment.className = 'fin-reg-com table-text';

            let divWrapComment = document.createElement('div');
            divWrapComment.className = 'message-wrapper-title';

            let divTextComment = document.createElement('div');
            divTextComment.className = 'message-text-line';
            divTextComment.innerHTML = data['comment'];

            divWrapComment.append(divTextComment);
            divMainComment.append(divWrapComment);
            divRow.append(divMainComment);

            let hrLine = document.createElement('hr');
            hrLine.className = 'line';

            let divClear = document.createElement('div');
            divClear.className = 'clearfix';

            divClear.append(hrLine);
            divRow.append(divClear);

            listReg.append(divRow);
        });

        let divtotal = document.getElementById('total');
        divtotal.innerHTML = dataSet.total;

        resize();
    }
    else
    {
        let divInfo = document.createElement('div');
        divInfo.className = 'text-font text-center margin-v20';
        divInfo.setAttribute('id', 'info');
        divInfo.innerHTML = 'Нет движений';

        listReg.append(divInfo);

        let divtotal = document.getElementById('total');
        divtotal.innerHTML = dataSet.total;
    }
}

function convertTimeStamp(timestamp) {
    let condate = new Date(timestamp*1000);

    return [
        condate.getFullYear(),           // Get day and pad it with zeroes
        ('0' + (condate.getMonth()+1)).slice(-2),      // Get month and pad it with zeroes
        ('0' + condate.getDate()).slice(-2)                          // Get full year
    ].join('.');                                  // Glue the pieces together
}

function convertTimeStampVisible(timestamp) {
    let condate = new Date(timestamp*1000);

    return [
        ('0' + condate.getDate()).slice(-2),
        ('0' + (condate.getMonth()+1)).slice(-2),
        condate.getFullYear()
    ].join('.');
}

function convertTimeStampWithTime(timestamp) {
    let condate = new Date(timestamp*1000);

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