
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
                //++ 1-3-1-003 21/02/2023
                else if(divRow[column].nodeName === 'A') {

                    children2 = divRow[column].childNodes;
                    for (child2 in children2) {
                        divRow2 = children2[child2].childNodes;
                        for (column2 in divRow2) {
                            if (divRow2[column2].nodeName === 'DIV' & (' ' + divRow2[column2].className + ' ').indexOf('colResize') > -1) {
                                arrCols[i] = divRow2[column2];
                                i = i + 1;
                                if (divRow2[column2].clientHeight > maxHeight) {
                                    maxHeight = divRow2[column2].clientHeight;
                                }
                            }
                        }
                    }
                }
                //-- 1-3-1-003 21/02/2023
            }

            for (column in arrCols) {
                arrCols[column].style.height = maxHeight + 'px';
            }
        }
    }
}