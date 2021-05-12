let btnClearTag = document.getElementById('ClearTag');
let btnFind = document.getElementById('btnFind');
let btnReset = document.getElementById('btnReset');
let valueTag = document.getElementById('valueTag');
let listTags = document.getElementById('list_tags');
let tagsWraps = document.getElementsByClassName('wrapper-tag');

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

    btnClearTag.onclick = function(e) {
        valueTag.value = '';
    };

    btnReset.onclick = function(e) {
        window.location.href = '/users';
    };

    btnFind.onclick = function(e) {
        let params = '';
        let sep = '';

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

        if (params !== '') {
            params = '?' + params;
        }

        window.location.href = '/users' + params;
    };
})