let btnSave = document.getElementById('button-save');

let valueTitle = document.getElementById('valueTitle');
let valueText = document.getElementById('valueText');

let thisData = {
    'title' : '',
    'text' : '',
};

//Events

valueTitle.onchange = function(event){
    thisData['title'] = valueTitle.value;
};

btnSave.onclick = function(e) {
    thisData['text'] = valueText.innerHTML.trim();
    runAjax('/sender-panel-post', thisData);
};

//Helpers

//++ 1-2-4-002 10/10/2022
//*-
//function render(data){
//*+
function render(data, Parameters) {
//-- 1-2-4-002 10/10/2022
    console.log(data)
    alert( "Письмо принято в очередь на отправку" );
    valueTitle.value = '';
    valueText.innerHTML = '';
}