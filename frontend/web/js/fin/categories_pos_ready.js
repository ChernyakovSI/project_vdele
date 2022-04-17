let id_category = Number(document.getElementById('paramIdCategory').innerText);
let isProfit = Number(document.getElementById('paramIsProfit').innerText)

let divRedComment = document.getElementById('red-comment');
let divWrapError = null;

$(document).ready( function() {
    //++ 1-2-2-009 15/04/2022
    fullSubCategories(0);
    //-- 1-2-2-009 15/04/2022
});

function expense(){
    isProfit = 0;
    fullSubCategories(0);
};

function profit(){
    isProfit = 1;
    fullSubCategories(0);
};

function showError(data) {
    divRedComment.hidden = false;
    divRedComment.innerHTML = data.error;

    if(data['element'] != null) {
        divWrapError = document.getElementById('value'+data['element']+'Wrap');
        divWrapError.classList.add('redBorder');
    }
};

function HideError(data) {
    divRedComment.hidden = true;
    divRedComment.innerHTML = data.error;

    if(data['element'] != null) {
        let divWrap = document.getElementById('value'+data['element']+'Wrap');
        divWrap.classList.remove('redBorder');
    }
    else if(divWrapError != null) {
        divWrapError.classList.remove('redBorder');
        divWrapError = null;
    }
};

function addCategory() {
    showFormNew(0, 0, function(value) {
        if (value != null) {
            $.ajax({
                // Метод отправки данных (тип запроса)
                type : 'post',
                // URL для отправки запроса
                url : '/fin/cat-add',
                // Данные формы
                data : value
            }).done(function(data) {
                if (data.error == null) {
                    deleteForm();
                    confirmDataCat(data)
                } else {
                    // Если при обработке данных на сервере произошла ошибка
                    // console.log(data);
                    if (data.error != ''){
                        showError(data);
                    }
                }
            }).fail(function() {
                // Если произошла ошибка при отправке запроса
                //console.log(data.error);
            });
        }
    });
};

function editCategory(id) {
    showFormNew(id, 0, function(value) {
        if (value != null) {
            $.ajax({
                // Метод отправки данных (тип запроса)
                type : 'post',
                // URL для отправки запроса
                url : '/fin/cat-edit',
                // Данные формы
                data : value
            }).done(function(data) {
                if (data.error == null) {
                    deleteForm();
                    confirmDataCat(data);
                } else {
                    // Если при обработке данных на сервере произошла ошибка
                    //console.log(data);
                    if (data.error != ''){
                        showError(data);
                    }
                }
            }).fail(function() {
                // Если произошла ошибка при отправке запроса
                //console.log(data.error);
            });
        }
    });
}

function deleteCat(id) {
    let value = {
        'id': id,
        'isProfit': isProfit
    };
    $.ajax({
        // Метод отправки данных (тип запроса)
        type : 'post',
        // URL для отправки запроса
        url : '/fin/cat-delete',
        // Данные формы
        data : value
    }).done(function(data) {
        if (data.error == null) {
            deleteForm();
            confirmData(data);
        } else {
            showError(data);
        }
    }).fail(function() {
        // Если произошла ошибка при отправке запроса
        //console.log(data.error);
    });

}

function fullSubCategories(id){
    let value = {
        'id_category': id,
        'isProfit': isProfit,
    };
    id_category = id;

    $.ajax({
        // Метод отправки данных (тип запроса)
        type : 'post',
        // URL для отправки запроса
        url : '/fin/categories',
        // Данные формы
        data : value
    }).done(function(data) {
        if (data.error == null) {
            confirmData(data)
        } else {
            // Если при обработке данных на сервере произошла ошибка
            //console.log(data);
            if (data.error != ''){
                showError(data);
            }
        }
    }).fail(function() {
        // Если произошла ошибка при отправке запроса
        //console.log(data.error);
    });
};

function addSub() {
    showFormNew(0, id_category, function(value) {
        if (value != null) {
            $.ajax({
                // Метод отправки данных (тип запроса)
                type : 'post',
                // URL для отправки запроса
                url : '/fin/sub-add',
                // Данные формы
                data : value
            }).done(function(data) {
                if (data.error == null) {
                    deleteForm();
                    confirmData(data)
                } else {
                    // Если при обработке данных на сервере произошла ошибка
                    //console.log(data);
                    if (data.error != ''){
                        showError(data);
                    }
                }
            }).fail(function() {
                // Если произошла ошибка при отправке запроса
                //console.log(data.error);
            });
        }
    });
};

function editSub(id) {
    showFormNew(id, id_category, function(value) {
        if (value != null) {
            $.ajax({
                // Метод отправки данных (тип запроса)
                type : 'post',
                // URL для отправки запроса
                url : '/fin/sub-edit',
                // Данные формы
                data : value
            }).done(function(data) {
                if (data.error == null) {
                    deleteForm();
                    confirmData(data);
                } else {
                    // Если при обработке данных на сервере произошла ошибка
                    //console.log(data);
                    if (data.error != ''){
                        showError(data);
                    }
                }
            }).fail(function() {
                // Если произошла ошибка при отправке запроса
                //console.log(data.error);
            });
        }
    });
}

confirmDelete = function(id) {
    let ans = confirm('Удалить подкатегорию?');

    if(ans == true) {
        deleteSub(id);
    }

    return 1;
}

function deleteSub(id) {
    let value = {
        'id': id,
        'id_category': id_category,
        'isProfit': isProfit
    };
    $.ajax({
        // Метод отправки данных (тип запроса)
        type : 'post',
        // URL для отправки запроса
        url : '/fin/sub-delete',
        // Данные формы
        data : value
    }).done(function(data) {
        if (data.error == null) {
            confirmData(data);
        } else {
            showError(data);
        }
    }).fail(function() {
        // Если произошла ошибка при отправке запроса
        //console.log(data.error);
    });

}

function showCover() {
    let coverDiv = document.createElement('div');
    coverDiv.id = 'cover-div';

    // убираем возможность прокрутки страницы во время показа модального окна с формой
    document.body.style.overflowY = 'hidden';

    document.body.append(coverDiv);
}

function hideCover() {
    document.getElementById('cover-div').remove();
    document.body.style.overflowY = '';
}

function deleteForm(){
    let container = document.getElementById('prompt-form-container');

    hideCover();
    container.style.display = 'none';
    document.onkeydown = null;
}

function showFormNew(id, id_category, callback) {
    showCover();
    let form = document.getElementById('prompt-form');

    form.classList.remove('form-off');
    form.classList.add('form-on');

    let container = document.getElementById('prompt-form-container');
    let btnClose = document.getElementById('btnClose');
    let valueName = document.getElementById('valueName');
    let buttonAdd = document.getElementById('button-add');
    let buttonDel = document.getElementById('button-del');
    let floatingCirclesG = document.getElementById('floatingCirclesG');

    let valueColor = document.getElementById('valueColor');
    let valueIsColored = document.getElementById('setColorOn');

    let fromCaption =  document.getElementById('form-caption');

    divRedComment = document.getElementById('red-comment');
    divRedComment.hidden = true;

    if(id == 0){
        if (id_category > 0){
            fromCaption.innerHTML = 'Новая подкатегория';
        }
        else
        {
            fromCaption.innerHTML = 'Новая категория';
        }

        buttonDel.hidden = true;

        valueName.innerHTML = '';

        floatingCirclesG.hidden = true;

        buttonAdd.onclick = function(e) {
            initBtnConfirm(id_category);
        };
    }
    else
    {
        floatingCirclesG.hidden = false;
        if (id_category > 0){
            fromCaption.innerHTML = 'Редактирование подкатегории';
            buttonDel.hidden = true;
        }
        else
        {
            fromCaption.innerHTML = 'Редактирование категории';
            buttonDel.hidden = false;
        }
        buttonAdd.onclick = null;
        buttonDel.onclick = null;

        $.ajax({
            // Метод отправки данных (тип запроса)
            type : 'post',
            // URL для отправки запроса
            url : '/fin/cat-get',
            // Данные формы
            data : {id : id}
        }).done(function(data) {
            if (data.error == null) {
                fullData(data, id_category);
                floatingCirclesG.hidden = true;
            } else {
                showError(data);
            }
        }).fail(function() {
            // Если произошла ошибка при отправке запроса
            //console.log(data.error);
        });
    }

    valueColor.value = '#ffffff';
    valueIsColored.checked = false;

    function complete(value) {
        callback(value);
    }

    document.onkeydown = function(e) {
        if (e.key == 'Escape') {
            complete(null);
            deleteForm();
        }
    };

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

    valueName.onblur = function(e){
        data = {
            'element': 'Name',
        };
        HideError(data);
    };

    function closeFrom() {
        complete(null);
        deleteForm();
    };

    function fullData(data, id_category) {

        valueName.innerHTML = data.data.name;

        if(data.data.color === '') {
            valueColor.value = '#ffffff';
        } else {
            valueColor.value = data.data.color;
        }
        if(data.data.isColored === 0) {
            valueIsColored.checked = false;
        } else {
            valueIsColored.checked = true;
        }

        buttonAdd.onclick = function(e) {
            initBtnConfirm(id_category);
        };

        buttonDel.onclick = function(e) {
            let ans = confirm('Удалить категорию и все подкатегории в ней?');

            if(ans == true) {
                deleteCat(data.data.id);
            }

            return 1;
        };
    };

    function initBtnConfirm(id_category) {

        /*if(valueName.innerHTML.trim() == '') {
            valueNameWrap.classList.add('redBorder');
            return 0;
        }*/

        let newCat;
        newCat = {
            'id' : id,
            'name' : valueName.innerHTML,
            'id_category': id_category,
            'isProfit': isProfit,
            'color': valueColor.value,
            'isColored': valueIsColored.checked,
        };

        complete(newCat);
    };

    container.style.display = 'block';
    //form.elements.text.focus();
}

function confirmData(data) {
    rerenderTable(data);

    if(data.categories != null) {
        let mData = {
            'data' : data.categories
        };
        rerenderTableCat(mData);
    }

    rerenderMenu();
}

function confirmDataCat(data) {
    rerenderTableCat(data);
    rerenderMenu();
}

function rerenderTable(dataSet) {
    let listSubs = document.getElementById('list-subcategories');
    listSubs.innerHTML = '';

    let btnAdd = document.getElementById('new-sub');
    btnAdd.hidden = false;

    //console.dir(dataSet);

    if(dataSet.data.length > 0){
        let hrLine = document.createElement('hr');
        hrLine.className = 'line';

        let divClear = document.createElement('div');
        divClear.className = 'clearfix';

        divClear.append(hrLine);

        listSubs.append(divClear);

        dataSet.data.forEach(function(data, i, arrData){
            let divRow = document.createElement('div');
            divRow.className = 'fin-acc-row white-back interactive-only';
            divRow.setAttribute('id', data['id']);

            let divMainName = document.createElement('div');
            divMainName.className = 'url-col-name table-text';
            //++ 1-2-2-009 15/04/2022
            if(data['isColored'] === 1 && data['color'] !== '') {
                divMainName.style.backgroundColor = data['color'];
            }
            //-- 1-2-2-009 15/04/2022

            let divWrapName = document.createElement('div');
            divWrapName.className = 'message-wrapper-title';

            let divTextName = document.createElement('div');
            divTextName.className = 'message-text-line';
            divTextName.innerHTML = data['name'];

            divWrapName.append(divTextName);
            divMainName.append(divWrapName);
            divRow.append(divMainName);

            let divPanel = document.createElement('div');
            divPanel.className = 'url-col-panel';
            divRow.addEventListener('dblclick', function() {
                editSub(data['id']);
            }, false);

            let divWrapPanel = document.createElement('div');
            divWrapPanel.className = 'message-wrapper-title';

            let divTextPanel = document.createElement('div');
            divTextPanel.className = 'message-text-line-half unactive';

            let spanEdit = document.createElement('span');
            spanEdit.className = 'glyphicon glyphicon-pencil symbol_style interactive text-center';
            spanEdit.addEventListener('click', function() {
                editSub(data['id']);
            }, false);

            divTextPanel.append(spanEdit);
            divWrapPanel.append(divTextPanel);

            divTextPanel = document.createElement('div');
            divTextPanel.className = 'message-text-line-half unactive';

            let spanDel = document.createElement('span');
            spanDel.className = 'glyphicon glyphicon-remove symbol_style interactive text-center';
            spanDel.addEventListener('click', function() {
                confirmDelete(data['id']);
            }, false);

            divTextPanel.append(spanDel);
            divWrapPanel.append(divTextPanel);

            let divClear = document.createElement('div');
            divClear.className = 'clearfix';

            divWrapPanel.append(divClear);
            divPanel.append(divWrapPanel);
            divRow.append(divPanel);

            let hrLine = document.createElement('hr');
            hrLine.className = 'line';

            divClear = document.createElement('div');
            divClear.className = 'clearfix';

            divClear.append(hrLine);
            divRow.append(divClear);

            listSubs.append(divRow);
        });
    }
    else
    {
        let divInfo = document.createElement('div');
        divInfo.className = 'text-font text-center margin-v20';
        divInfo.setAttribute('id', 'info');

        let buttonNewSub = document.getElementById('new-sub');

        if(dataSet.categories.length == 0){
            divInfo.innerHTML = 'У вас пока нет ни одной категории.<br>Добавляйте слева категории и для каждой категории добавляйте подкатегории здесь.';
            buttonNewSub.hidden = true;
        }
        else
        {
            if(id_category == 0){
                divInfo.innerHTML = 'Выберите категорию слева для отображения всех ее подкатегорий';
                buttonNewSub.hidden = true;
            }
            else{
                divInfo.innerHTML = 'У вас пока нет ни одной подкатегории.<br>Добавьте первую:';
                buttonNewSub.hidden = false;
            }
        }

        listSubs.append(divInfo);
    };
}

function rerenderTableCat(dataSet) {
    let listCats = document.getElementById('list-categories');
    listCats.innerHTML = '';

    if(dataSet.data.length > 0){
        dataSet.data.forEach(function(data, i, arrData){
            let divRow = document.createElement('div');
            if (id_category == data['id']){
                divRow.className = 'fin-acc-row active-back interactive-only';
                divRow.addEventListener('click', function() {
                    editCategory(data['id']);
                }, false);
                isDefault = false;
            }
            else {
                divRow.className = 'fin-acc-row white-back interactive-only';
                divRow.addEventListener('click', function() {
                    fullSubCategories(data['id']);
                }, false);
            };
            divRow.setAttribute('id', data['id']);

            let divCol = document.createElement('div');
            divCol.className = 'url-col-category table-text';
            //++ 1-2-2-009 15/04/2022
            if(data['isColored'] === 1 && data['color'] !== '') {
                divCol.style.backgroundColor = data['color'];
            }
            //-- 1-2-2-009 15/04/2022

            let divWrapName = document.createElement('div');
            divWrapName.className = 'message-wrapper-title';

            let divTextName = document.createElement('div');
            divTextName.className = 'message-text-line';
            divTextName.innerHTML = data['name'];

            divWrapName.append(divTextName);
            divCol.append(divWrapName);
            divRow.append(divCol);

            let hrLine = document.createElement('hr');
            hrLine.className = 'line';

            divClear = document.createElement('div');
            divClear.className = 'clearfix';

            divClear.append(hrLine);
            divRow.append(divClear);

            listCats.append(divRow);
        });
    }
}

function rerenderMenu(){

    let btnExpense = document.getElementById('btn-expense');
    let btnProfit = document.getElementById('btn-profit');

    let divCaption = document.getElementById('caption');

    if(isProfit == 1){
        btnExpense.className = 'btn-submenu btn-submenu-interactive';
        btnProfit.className = 'btn-submenu btn-active btn-submenu-interactive';

        divCaption.innerHTML = 'Категории доходов';
    }
    else
    {
        btnExpense.className = 'btn-submenu btn-active btn-submenu-interactive';
        btnProfit.className = 'btn-submenu btn-submenu-interactive';

        divCaption.innerHTML = 'Категории расходов';
    }
}