let divParamText = document.getElementById('paramText');

let valueText = document.getElementById('valueText');

$(document).ready( function() {

    valueText.innerHTML = getNewLinesToBr(divParamText);

    convertNewLinesToBr(valueText);
    DetectURLs(valueText);
    generatorURLs();

})

//Events

//Helpers