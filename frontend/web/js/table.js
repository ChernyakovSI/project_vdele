
//У списка строк таблицы должен быть класс tableResize, у каждой строки colResize
//Пример у edu/card
function resize() {
    let arrListRegs = document.getElementsByClassName('tableResize');
    let children
    let divRow
    let arrCols
    let maxHeight = 0;
    let i;

    for(divListRegs in arrListRegs) {
        if (arrListRegs[divListRegs].nodeName !== 'DIV') {
            continue;
        }

        children = arrListRegs[divListRegs].childNodes;

        arrCols = [];
        maxHeight = 0;
        i = 0;

        for (child in children) {
            divRow = children[child].childNodes;

            for (column in divRow) {
                if (divRow[column].nodeName === 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colResize') > -1) {
                    arrCols[i] = divRow[column];
                    i = i + 1;
                    if (divRow[column].clientHeight > maxHeight) {
                        maxHeight = divRow[column].clientHeight;
                    }
                }
            }

            for (column in arrCols) {
                arrCols[column].style.height = maxHeight + 'px';
            }
        }
    }
}