let divParamID = document.getElementById('paramID');
let divParamNum = document.getElementById('paramNum');
let divParamTitle = document.getElementById('paramTitle');
let divParamSphereId = document.getElementById('paramSphereID');
let divParamSphereName = document.getElementById('paramSphereName');
let divParamParentID = document.getElementById('paramParentID');
let divParamPath = document.getElementById('paramPath');
let divParamNumTab = document.getElementById('paramNumTab');
let divFisrtString = document.getElementById('fisrtString');
let divResultString = document.getElementById('resultString');

let valueSphere = document.getElementById('valueSphere');
let valueGroup = document.getElementById('valueGroup');
let valueVariant = document.getElementById('valueVariant');
let valueAnswer = document.getElementById('valueAnswer');
let valueQuestion = document.getElementById('valueQuestion');

let btnCancel = document.getElementById('button-cancel');
let btnClearSphere = document.getElementById('ClearSphere');
let btnClearCard = document.getElementById('ClearCard');
let btnClearVariant = document.getElementById('ClearVariant');
let btnPractise = document.getElementById('button-practise');
let btnStop = document.getElementById('button-stop');

let list_card = document.getElementById('list-cards');
let list_groups = document.getElementById('list_groups');
let list_variants = document.getElementById('list_variants');

let sectionMain = document.getElementById('content-main');
let sectionTrainer = document.getElementById('content-trainer');

let questionText = document.getElementById('questionText');
let questionNum = document.getElementById('questionNum');
let answerList = document.getElementById('answer_list');
let btnTestNext = document.getElementById('TestNext');

let thisData = {
    'id' : 0,
    'parent' : 0,
    'title' : '',
    'num' : 0,
    'sphere_id' : 0,
    'sphere_name' : '',
    'variant': 1,
    'answers': 0,
    'questions': 0
};

let thisCards = [];
let thisVariants = [];

let variantCards = [];
let testCards = []; //id, answers = [id], right, rightValue, userAnswer, QuestionText, QuestionImage

let numQuestion = 0;
let QuestionType = 0;
let AnswerType = 0;
let AnswerDirection = 1;

$(document).ready( function() {
    if(divParamID.innerText !== '') {
        thisData['id'] = Number(divParamID.innerText);
        thisData['sphere_id'] = Number(divParamSphereId.innerText);
        thisData['title'] = divParamTitle.innerText;
        thisData['num'] = Number(divParamNum.innerText);
        thisData['sphere_name'] = divParamSphereName.innerText;
        thisData['parent'] = Number(divParamParentID.innerText);
    }

    VisibleSections();
    resize();

    loadThisCards();
})

//Events ---------------------------------------------------------------------------------------------------------------

btnCancel.onclick = function(e) {

    closeForm()

};

valueSphere.onchange = function(event){
    let curSphere = this.value.trim();
    let idSphere = 0;

    let children = list_sphere.childNodes;
    for(child in children){
        if(children[child].nodeName === 'OPTION' && children[child].innerText === curSphere) {
            idSphere = children[child].getAttribute('data-id');
            thisData['sphere_id'] = Number(idSphere);
            break;
        }
    }

    if (idSphere === 0) {
        this.value = '';
        thisData['sphere_id'] = 0;
    }

    runAjax('/edu/card-get-groups', thisData);
};

btnClearSphere.onclick = function(e) {
    valueSphere.value = '';

    thisData['sphere_id'] = 0;

    runAjax('/edu/card-get-groups', thisData);
};

valueGroup.onchange = function(event){
    let curGroup = this.value.trim();
    let idGroup = 0;

    let children = list_groups.childNodes;
    for(child in children){
        if(children[child].nodeName === 'OPTION' && children[child].innerText === curGroup) {
            idGroup = children[child].getAttribute('data-id');
            thisData['id'] = Number(idGroup);
            break;
        }
    }

    if (idGroup === 0) {
        this.value = '';
        thisData['id'] = 0;
    }

    runAjax('/edu/card-get-cards', thisData);
};

btnClearCard.onclick = function(e) {
    valueGroup.value = '';

    thisData['id'] = 0;

    runAjax('/edu/card-get-cards', thisData);
};

valueVariant.onchange = function(event){
    let curVariant = this.value.trim();
    let idVariant = 0;

    let children = list_variants.childNodes;
    for(child in children){
        if(children[child].nodeName === 'OPTION' && children[child].innerText === curVariant) {
            idVariant = children[child].getAttribute('data-id');
            thisData['variant'] = Number(idVariant);
            break;
        }
    }

    if (idVariant === 0) {
        this.value = '';
        thisData['variant'] = 0;
    }
};

btnClearVariant.onclick = function(e) {
    valueVariant.value = '';

    thisData['variant'] = 0;
};

valueAnswer.onchange = function(event){
    this.value = Number(this.value);

    if (this.value < this.getAttribute('min')) {
        this.value = this.getAttribute('min');
    } else if(this.value > this.getAttribute('max')) {
        this.value = this.getAttribute('max');
    }

    thisData['answers'] = this.value;
};

valueQuestion.onchange = function(event){
    this.value = Number(this.value);

    if (this.value < this.getAttribute('min')) {
        this.value = this.getAttribute('min');
    } else if(this.value > this.getAttribute('max')) {
        this.value = this.getAttribute('max');
    }

    thisData['questions'] = this.value;
};

btnPractise.onclick = function(e) {
    divParamNumTab.innerText = 2;

    VisibleSections();
};

btnStop.onclick = function(e) {
    divParamNumTab.innerText = 1;

    VisibleSections();
};

//Helpers --------------------------------------------------------------------------------------------------------------

function loadThisCards() {
    thisCards = [];
    let thisString = [];
    let isFound = false;

    children = list_card.childNodes;
    let i = 0;

    for (child in children) {
        divRow = children[child].childNodes;


        try {
            children[child].hasAttribute('id');
        } catch (err) {
            continue;
        }
        if (children[child].hasAttribute('id') == false) {
            continue;
        }
        if (Number(children[child].id) == NaN || children[child].id == 'info1') {
            continue;
        }

        thisCards[i] = [];
        thisCards[i]['id'] = Number(children[child].id);

        for (column in divRow) {
            if (divRow[column].nodeName === 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameValue1') > -1) {

                divRow2 = divRow[column].childNodes;
                for (column2 in divRow2) {
                    if (divRow2[column2].nodeName === 'DIV' & (' ' + divRow2[column2].className + ' ').indexOf('wrapper-line') > -1) {

                        divRow3 = divRow2[column2].childNodes;
                        for (column3 in divRow3) {
                            if (divRow3[column3].nodeName === 'DIV') {

                                thisCards[i]['value1'] = divRow3[column3].innerText;
                            }
                        }
                    }
                }
            }
            if (divRow[column].nodeName === 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameValue2') > -1) {

                divRow2 = divRow[column].childNodes;
                for (column2 in divRow2) {
                    if (divRow2[column2].nodeName === 'DIV' & (' ' + divRow2[column2].className + ' ').indexOf('wrapper-line') > -1) {

                        divRow3 = divRow2[column2].childNodes;
                        for (column3 in divRow3) {
                            if (divRow3[column3].nodeName === 'DIV') {

                                thisCards[i]['value2'] = divRow3[column3].innerText;
                            }
                        }
                    }
                }
            }
            if (divRow[column].nodeName === 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameImage1') > -1) {

                divRow2 = divRow[column].childNodes;
                for (column2 in divRow2) {
                    if (divRow2[column2].nodeName === 'DIV' & (' ' + divRow2[column2].className + ' ').indexOf('foto-input-string') > -1) {

                        divRow3 = divRow2[column2].childNodes;
                        for (column3 in divRow3) {
                            if (divRow3[column3].nodeName === 'DIV' & (' ' + divRow3[column3].className + ' ').indexOf('title') > -1) {

                                thisCards[i]['image1'] = 0;
                                thisCards[i]['image1_src'] = '';
                            } else if (divRow3[column3].nodeName === 'IMG') {

                                thisCards[i]['image1'] = Number(divRow3[column3].getAttribute('data-id'));
                                thisCards[i]['image1_src'] = divRow3[column3].getAttribute('src');
                            }
                        }
                    }
                }
            }
            if (divRow[column].nodeName === 'DIV' & (' ' + divRow[column].className + ' ').indexOf('colNameImage2') > -1) {

                divRow2 = divRow[column].childNodes;
                for (column2 in divRow2) {
                    if (divRow2[column2].nodeName === 'DIV' & (' ' + divRow2[column2].className + ' ').indexOf('foto-input-string') > -1) {

                        divRow3 = divRow2[column2].childNodes;
                        for (column3 in divRow3) {
                            if (divRow3[column3].nodeName === 'DIV' & (' ' + divRow3[column3].className + ' ').indexOf('title') > -1) {

                                thisCards[i]['image2'] = 0;
                                thisCards[i]['image2_src'] = '';
                            } else if (divRow3[column3].nodeName === 'IMG') {

                                thisCards[i]['image2'] = Number(divRow3[column3].getAttribute('data-id'));
                                thisCards[i]['image2_src'] = divRow3[column3].getAttribute('src');
                            }
                        }
                    }
                }
            }
        }

        i = i + 1;

    }

    //console.log(thisCards)
    /*for (str in thisCards) {
        console.log('value1 = ' + thisCards[str]['value1'])
    }*/

    fillVariants();
    setAnswers();
}

function closeForm() {
    if(thisData['parent'] > 0) {
        groupNum = '/'+ thisData['parent']
    } else {
        groupNum = ''
    }

    window.location.href = '/edu/cards' + groupNum;
}

function render(data, Parameters) {
    if(Parameters['url'] === '/edu/card-get-groups') {
        fillGroups(data);
    } else if (Parameters['url'] === '/edu/card-get-cards') {
        fillCards(data);
    }
}

function fillGroups(data) {
    list_groups.innerHTML = '';

    for (group in data['groups']) {
        let opt = document.createElement('option');
        opt.setAttribute('data-id', data['groups'][group]['id']);
        opt.innerText = data['groups'][group]['title'] + ' (' + data['groups'][group]['num'] + ')';

        list_groups.append(opt);
    }
}

function fillCards(data) {
    list_card.innerHTML = '';

    number = 0;
    for (numCard in data['cards']['cards']) {
        card = data['cards']['cards'][numCard];
        number = number + 1;
        //console.log(card);

        let divRow = document.createElement('div');
        divRow.className = 'fin-acc-row interactive-only reg_'+number;
        divRow.id = card['id'];

        //Номер
        let divNumCol = document.createElement('div');
        divNumCol.className = 'column-5 border-1px-bottom col-back-nul colNameNum colResize';

        let divNumWrap = document.createElement('div');
        divNumWrap.className = 'message-wrapper-title';

        let divNum = document.createElement('div');
        divNum.className = 'message-text-line text-center';

        let divNumContent = document.createElement('div');
        divNumContent.innerText = number;

        //Значение1
        let divVal1Col = document.createElement('div');
        divVal1Col.className = 'column-30 border-1px-bottom col-back-nul colNameValue1 colResize';

        let divVal1Wrap = document.createElement('div');
        divVal1Wrap.className = 'wrapper-line';

        let divVal1 = document.createElement('div');
        divVal1.className = 'message-text-line';
        divVal1.id = 'value1_'+number;
        divVal1.innerText = card['value1'];

        //Фото1
        let divFoto1Col = document.createElement('div');
        divFoto1Col.className = 'column-15 border-1px-all col-back-nul colNameImage1 colResize';

        let divFoto1Wrap = document.createElement('div');
        divFoto1Wrap.className = 'foto-input-string';
        divFoto1Wrap.id = 'foto-wrap1-'+number;

        let divFoto1;

        if (card['image1_id'] == '0') {
            divFoto1 = document.createElement('div');
            divFoto1.className = 'title m-l-30 m-t-3';
            divFoto1.innerText = 'Без фото';
        } else  {
            divFoto1 = document.createElement('img');
            divFoto1.className = 'div-center';
            divFoto1.id = 'image1_'+number;
            divFoto1.setAttribute('src', divParamPath.innerText+card['image1_src']);
            divFoto1.setAttribute('data-id', card['image1_id']);
        }

        //Разделитель
        let divSep = document.createElement('div');
        divSep.className = 'column-5 border-1px-bottom col-back-nul colNameSep colResize';

        //Значение2
        let divVal2Col = document.createElement('div');
        divVal2Col.className = 'column-30 border-1px-bottom col-back-nul colNameValue2 colResize';

        let divVal2Wrap = document.createElement('div');
        divVal2Wrap.className = 'wrapper-line';

        let divVal2 = document.createElement('div');
        divVal2.className = 'message-text-line';
        divVal2.id = 'value2_'+number;
        divVal2.innerText = card['value2'];

        //Фото2
        let divFoto2Col = document.createElement('div');
        divFoto2Col.className = 'column-15 border-1px-all col-back-nul colNameImage2 colResize';

        let divFoto2Wrap = document.createElement('div');
        divFoto2Wrap.className = 'foto-input-string';
        divFoto2Wrap.id = 'foto-wrap2-'+number;

        let divFoto2;

        if (card['image2_id'] == '0') {
            divFoto2 = document.createElement('div');
            divFoto2.className = 'title m-l-30 m-t-3';
            divFoto2.innerText = 'Без фото';
        } else  {
            divFoto2 = document.createElement('img');
            divFoto2.className = 'div-center';
            divFoto2.id = 'image2_'+number;
            divFoto2.setAttribute('src', divParamPath.innerText+card['image2_src']);
            divFoto2.setAttribute('data-id', card['image2_id']);
        }

        //Заполнение
        //Номер
        divNum.append(divNumContent);
        divNumWrap.append(divNum);
        divNumCol.append(divNumWrap);
        divRow.append(divNumCol);
        //Значение1
        divVal1Wrap.append(divVal1);
        divVal1Col.append(divVal1Wrap);
        divRow.append(divVal1Col);
        //Фото1
        divFoto1Wrap.append(divFoto1);
        divFoto1Col.append(divFoto1Wrap);
        divRow.append(divFoto1Col);
        //Разделитель
        divRow.append(divSep);
        //Значение2
        divVal2Wrap.append(divVal2);
        divVal2Col.append(divVal2Wrap);
        divRow.append(divVal2Col);
        //Фото2
        divFoto2Wrap.append(divFoto2);
        divFoto2Col.append(divFoto2Wrap);
        divRow.append(divFoto2Col);

        list_card.append(divRow);
    }

    resize();
    loadThisCards();
}

function fillVariants() {
    //thisCards
    thisVariants = [];

    let Val2FromVal1 = 0;
    let Val2FromImg1 = 0;
    let Img2FromVal1 = 0;
    let Img2FromImg1 = 0;
    let Val1FromVal2 = 0;
    let Val1FromImg2 = 0;
    let Img1FromVal2 = 0;
    let Img1FromImg2 = 0;

    for (numCard in thisCards) {
        card = thisCards[numCard];

        if((Val2FromVal1 == 0 || Val1FromVal2 == 0) && card['value2'] !== "" && card['value1'] !== "") {
            Val2FromVal1 = 1;
            Val1FromVal2 = 1;
        }

        if((Val2FromImg1 == 0 || Img1FromVal2 == 0) && card['value2'] !== "" && card['image1'] !== 0) {
            Val2FromImg1 = 1;
            Img1FromVal2 = 1;
        }

        if((Img2FromVal1 == 0 || Val1FromImg2 == 0) && card['value1'] !== "" && card['image2'] !== 0) {
            Img2FromVal1 = 1;
            Val1FromImg2 = 1;
        }

        if((Img2FromImg1 == 0 || Img1FromImg2 == 0) && card['image2'] !== 0 && card['image1'] !== 0) {
            Img2FromImg1 = 1;
            Img1FromImg2 = 1;
        }

        if (Val2FromVal1 == 1 && Val1FromVal2 ==1 && Val2FromImg1 == 1 && Val1FromImg2 == 1 &&
            Img2FromVal1 == 1 && Img1FromVal2 ==1 && Img2FromImg1 == 1 && Img1FromImg2 == 1) {
            break;
        }
    }

    let i = 0;
    if(Val2FromVal1 == 1) {
        thisVariants[i] = [];
        thisVariants[i]['id'] = 1;
        thisVariants[i]['name'] = 'Учить Значение 2 по Значению 1';
        i = i +1;
    }
    if(Val1FromVal2 == 1) {
        thisVariants[i] = [];
        thisVariants[i]['id'] = 2;
        thisVariants[i]['name'] = 'Учить Значение 1 по Значению 2';
        i = i +1;
    }
    if(Val2FromImg1 == 1) {
        thisVariants[i] = [];
        thisVariants[i]['id'] = 3;
        thisVariants[i]['name'] = 'Учить Значение 2 по Изображению 1';
        i = i +1;
    }
    if(Val1FromImg2 == 1) {
        thisVariants[i] = [];
        thisVariants[i]['id'] = 4;
        thisVariants[i]['name'] = 'Учить Значение 1 по Изображению 2';
        i = i +1;
    }
    if(Img2FromVal1 == 1) {
        thisVariants[i] = [];
        thisVariants[i]['id'] = 5;
        thisVariants[i]['name'] = 'Учить Изображение 2 по Значению 1';
        i = i +1;
    }
    if(Img1FromVal2 == 1) {
        thisVariants[i] = [];
        thisVariants[i]['id'] = 6;
        thisVariants[i]['name'] = 'Учить Изображение 1 по Значению 2';
        i = i +1;
    }
    if(Img2FromImg1 == 1) {
        thisVariants[i] = [];
        thisVariants[i]['id'] = 7;
        thisVariants[i]['name'] = 'Учить Изображение 2 по Изображению 1';
        i = i +1;
    }
    if(Img1FromImg2 == 1) {
        thisVariants[i] = [];
        thisVariants[i]['id'] = 8;
        thisVariants[i]['name'] = 'Учить Изображение 1 по Изображению 2';
        i = i +1;
    }

    list_variants.innerHTML = '';

    for (numVar in thisVariants) {
        let elOption = document.createElement('option');
        elOption.setAttribute('data-id', thisVariants[numVar]['id']);
        elOption.innerText = thisVariants[numVar]['name'];

        list_variants.append(elOption);
    }

    if(thisVariants.length > 0) {
        valueVariant.value = thisVariants[0]['name'];
        thisData['variant'] = thisVariants[0]['id'];
    } else {
        valueVariant.value = '';
        thisData['variant'] = 0;
    }

}

function setAnswers() {
    let keyAns = Math.trunc(thisCards.length/2);

    if (thisCards.length < 2) {
        valueAnswer.value = thisCards.length;
        thisData['answers'] = thisCards.length;
        valueQuestion.value = thisCards.length;
        thisData['questions'] = thisCards.length;
    } else if (keyAns < 2) {
        valueAnswer.value = 2;
        thisData['answers'] = 2;
        valueQuestion.value = thisCards.length;
        thisData['questions'] = thisCards.length;

    } else if (keyAns < 8) {
        valueAnswer.value = keyAns;
        thisData['answers'] = keyAns;
        valueQuestion.value = thisCards.length;
        thisData['questions'] = thisCards.length;
    } else {
        valueAnswer.value = 7;
        thisData['answers'] = 7;
        valueQuestion.value = 10;
        thisData['questions'] = 10;
    }

    valueAnswer.setAttribute('min', 0);
    valueAnswer.setAttribute('max', thisCards.length);
    valueQuestion.setAttribute('min', 0);
    valueQuestion.setAttribute('max', thisCards.length);

}

function VisibleSections() {
    if(divParamNumTab.innerText == 1){
        sectionMain.hidden = false;
        sectionMain.classList.add('container-foto-wrap');

        sectionTrainer.hidden = true;
        sectionTrainer.classList.remove('container-foto-wrap');
    }
    if(divParamNumTab.innerText == 2){
        sectionMain.hidden = true;
        sectionMain.classList.remove('container-foto-wrap');

        sectionTrainer.hidden = false;
        sectionTrainer.classList.add('container-foto-wrap');

        initialTest();
    }
}

function initialTest() {
    //console.log(thisCards);

    if(thisData['variant'] === 0) {
        divParamNumTab.innerText = 1;
        VisibleSections();
    }

    if(thisData['variant'] === 1 || thisData['variant'] === 2 || thisData['variant'] === 1 || thisData['variant'] === 6) {
        QuestionType = 0; //value
    } else {
        QuestionType = 1; //image
    }

    if(thisData['variant'] === 1 || thisData['variant'] === 2 || thisData['variant'] === 3 || thisData['variant'] === 4) {
        AnswerType = 0; //value
        if(thisData['variant'] === 2 || thisData['variant'] === 4) {
            AnswerDirection = 1; //учим value1
        } else {
            AnswerDirection = 2; //учим value2
        }
    } else {
        AnswerType = 1; //image
    }

    variantCards = [];
    i = 0;
    for (numCard in thisCards) {
        if(thisData['variant'] === 1 || thisData['variant'] === 2) { //Знач2 и Знач1
            if(thisCards[numCard]['value2'] !== '' && thisCards[numCard]['value1'] !== '') {
                variantCards[i] = thisCards[numCard]['id'];
                i = i + 1;
                continue;
            }
        }
        if(thisData['variant'] === 3 || thisData['variant'] === 6) { //Знач2 и Изоб1
            if(thisCards[numCard]['value2'] !== '' && thisCards[numCard]['image1'] !== 0) {
                variantCards[i] = thisCards[numCard]['id'];
                i = i + 1;
                continue;
            }
        }
        if(thisData['variant'] === 4 || thisData['variant'] === 5) { //Знач1 и Изоб2
            if(thisCards[numCard]['value1'] !== '' && thisCards[numCard]['image2'] !== 0) {
                variantCards[i] = thisCards[numCard]['id'];
                i = i + 1;
                continue;
            }
        }
        if(thisData['variant'] === 7 || thisData['variant'] === 8) { //Изоб1 и Изоб2
            if(thisCards[numCard]['image1'] !== 0 && thisCards[numCard]['image2'] !== 0) {
                variantCards[i] = thisCards[numCard]['id'];
                i = i + 1;
                continue;
            }
        }
    }

    if(variantCards.length > thisData['questions']) {
        questions = thisData['questions'];
    } else {
        questions = variantCards.length
    }
    //console.log(questions);

    testCards = [];
    for (let i = 0 ; (i < questions) && (i < variantCards.length) ; i++) {
        let r = Math.floor(Math.random() * (variantCards.length - i)) + i;
        let questionID = variantCards[r];
        variantCards[r] = variantCards[i];
        variantCards[i] = questionID;
        testCards[i] = [];
        testCards[i]['id'] = questionID;
    }

    for (numCard in testCards) {
        testCard = testCards[numCard];

        rightAnswer = 0;
        rightId = 0;
        for (numCardFull in thisCards) {
            CardInfo = thisCards[numCardFull];
            if(CardInfo['id'] === testCard['id']) {
                if(thisData['variant'] === 2 || thisData['variant'] === 4) {
                    rightAnswer = CardInfo['value1']
                }
                if(thisData['variant'] === 1 || thisData['variant'] === 3) {
                    rightAnswer = CardInfo['value2']
                }
                if(thisData['variant'] === 6 || thisData['variant'] === 8) {
                    rightAnswer = CardInfo['image1']
                }
                if(thisData['variant'] === 5 || thisData['variant'] === 7) {
                    rightAnswer = CardInfo['image2']
                }
                rightId = CardInfo['id'];

                if(QuestionType === 0) {
                    if(thisData['variant'] === 1 || thisData['variant'] === 5) {
                        testCard['QuestionText'] = CardInfo['value1'];
                    } else {
                        testCard['QuestionText'] = CardInfo['value2'];
                    }
                } else {
                    if(thisData['variant'] === 3 || thisData['variant'] === 7) {
                        testCard['QuestionText'] = CardInfo['image1'];
                    } else {
                        testCard['QuestionText'] = CardInfo['image2'];
                    }
                }
                //console.log(rightId)

                break;
            }
        }

        numAnswers = 0;
        if(variantCards.length > thisData['answers']) {
            numAnswers = thisData['answers'];
        } else {
            numAnswers = variantCards.length
        }

        answers = [];
        for (numCardVar in variantCards) {
            if(variantCards[numCardVar] === rightId) {
                let questionID = variantCards[numCardVar];
                variantCards[numCardVar] = variantCards[0];
                variantCards[0] = questionID;
                answers[0] = questionID;
            }
        }
        for (let i = 1 ; (i < numAnswers) && (i < variantCards.length) ; i++) {
            let r = Math.floor(Math.random() * (variantCards.length - i)) + i;
            let answerID = variantCards[r];
            variantCards[r] = variantCards[i];
            variantCards[i] = answerID;
            answers[i] = answerID;
        }
        for (let i = 0 ; i < answers.length ; i++) {
            let r = Math.floor(Math.random() * (answers.length - i)) + i;
            let answerID = answers[r];
            answers[r] = answers[i];
            answers[i] = answerID;
        }
        testCard['answers'] = answers;
        testCard['right'] = rightId;
        testCard['rightValue'] = rightAnswer;
    }

    numQuestion = 0;
    generateQuestion();

    console.log(testCards);
}

function generateQuestion() {
    if (divFisrtString.classList.contains('visible-not') === true) {
        divFisrtString.classList.remove('visible-not');
    }
    if (divResultString.classList.contains('visible-not') === false) {
        divResultString.classList.add('visible-not');
    }

    questionText.innerText = testCards[numQuestion]['QuestionText']
    questionNum.innerText = (numQuestion + 1);

    answerList.innerHTML = '';
    number = 1;
    for (numCardTest in testCards[numQuestion]['answers']) {
        if(AnswerType === 0) {
            let divWap = document.createElement('div');

            let inputEl = document.createElement('input');
            inputEl.className = 'custom-checkbox';
            inputEl.id = 'setVar'+number;
            inputEl.setAttribute('type', 'checkbox');
            inputEl.setAttribute('data-id', testCards[numQuestion]['answers'][numCardTest]);

            let labelEl = document.createElement('label');
            labelEl.className = 'interactive-only';
            labelEl.id = 'AnsVar'+number;
            labelEl.setAttribute('for', 'setVar'+number);
            for (numCardFull in thisCards) {
                cardInfo = thisCards[numCardFull];
                if(testCards[numQuestion]['answers'][numCardTest] === cardInfo['id']) {
                    if(AnswerDirection === 1) {
                        labelEl.innerText = cardInfo['value1'];
                    } else {
                        labelEl.innerText = cardInfo['value2'];
                    }

                    break;
                }
            }

            divWap.append(inputEl);
            divWap.append(labelEl);
            answerList.append(divWap);
        } else {

        }
        number = number + 1;
    }

    btnTestNext.innerText = 'Ответить';
}

btnTestNext.onclick = function(e) {
    if (btnTestNext.innerText === 'Ответить') {
        let UserAnswers = [];
        let i = 0;

        let children = answerList.childNodes;
        for(child in children){
            if(children[child].nodeName === 'DIV') {
                controls = children[child].childNodes;
                for(child2 in controls) {
                    if(controls[child2].nodeName === 'INPUT') {

                        let elFlag = document.getElementById(controls[child2].id);
                        if(elFlag.checked) {
                            UserAnswers[i] = elFlag.getAttribute('data-id');
                            i = i + 1;
                        }
                        break;
                    }
                }
            }
        }

        testCards[numQuestion]['userAnswer'] = UserAnswers;

        rightAns = testCards[numQuestion]['right'];
        if(Array.isArray(rightAns) === false) {
            arRightsAns = [];
            arRightsAns[0] = rightAns;
        } else {
            arRightsAns = rightAns;
        }

        let arrAnswerColor = []; //id-data : -2 (red но зеленая галка), -1 (red); 0; 1 (green)
        i = 0;
        for (numRight in arRightsAns) {
            isRight = false;
            for (numUser in UserAnswers) {
                if(arRightsAns[numRight] == UserAnswers[numUser]) {
                    arrAnswerColor[i] = [arRightsAns[numRight], 1];
                    isRight = true;
                    i = i + 1
                    break
                }
            }
            if(isRight === false) {
                arrAnswerColor[i] = [arRightsAns[numRight], -2];
                i = i + 1;
            }
        }
        for (numUser in UserAnswers) {
            isExists = false;
            for (numAnsCol in arrAnswerColor) {
                if(arrAnswerColor[numAnsCol][0] == UserAnswers[numUser]){
                    isExists = true;
                    break;
                }
            }
            if (isExists == false) {
                isRight = false;
                for (numRight in arRightsAns) {
                    if(arRightsAns[numRight] == UserAnswers[numUser]) {
                        isRight = true;
                        break
                    }
                }
                if (isRight == false) {
                    arrAnswerColor[i] = [Number(UserAnswers[numUser]), -1];
                    i = i + 1;
                }
            }
        }

        testCards[numQuestion]['arrAnswerColor'] = arrAnswerColor;

        for (let i = 0 ; i < arrAnswerColor.length ; i++) {
            details = arrAnswerColor[i];
            if(details[1] == -1) {
                let children = answerList.childNodes;
                for(child in children){
                    if(children[child].nodeName === 'DIV') {
                        controls = children[child].childNodes;
                        for(child2 in controls) {
                            if(controls[child2].nodeName === 'INPUT') {

                                let elFlag = document.getElementById(controls[child2].id);
                                if(elFlag.getAttribute('data-id') == details[0]) {
                                    UserAnswers[i] = elFlag.getAttribute('data-id');

                                    numID = String(controls[child2].id).replace('setVar', '');
                                    let elLabel = document.getElementById('AnsVar'+numID);
                                    elLabel.classList.add('text-color-red');

                                    break;
                                }

                            }
                        }
                    }
                }
            }
            if(details[1] == -2) { //действительно правильный ответ, который не выбрал пользователь
                let children = answerList.childNodes;
                for(child in children){
                    if(children[child].nodeName === 'DIV') {
                        controls = children[child].childNodes;
                        for(child2 in controls) {
                            if(controls[child2].nodeName === 'INPUT') {

                                let elFlag = document.getElementById(controls[child2].id);
                                if(elFlag.getAttribute('data-id') == details[0]) {
                                    elFlag.checked = true;
                                    //elFlag.classList.remove('custom-checkbox');
                                    elFlag.classList.add('custom-checkbox-indeed-right');

                                    numID = String(controls[child2].id).replace('setVar', '');
                                    let elLabel = document.getElementById('AnsVar'+numID);
                                    elLabel.classList.add('text-color-red');

                                    break;
                                }

                            }
                        }
                    }
                }
            }
            if(details[1] == 1) {
                let children = answerList.childNodes;
                for(child in children){
                    if(children[child].nodeName === 'DIV') {
                        controls = children[child].childNodes;
                        for(child2 in controls) {
                            if(controls[child2].nodeName === 'INPUT') {

                                let elFlag = document.getElementById(controls[child2].id);
                                if(elFlag.getAttribute('data-id') == details[0]) {
                                    //elFlag.classList.remove('custom-checkbox');
                                    elFlag.classList.add('custom-checkbox-right');

                                    UserAnswers[i] = elFlag.getAttribute('data-id');

                                    break;
                                }

                            }
                        }
                    }
                }
            }
        }

        btnTestNext.innerText = 'Продолжить';
    } else if (btnTestNext.innerText === 'Продолжить') {
        numQuestion = numQuestion + 1;
        if (numQuestion < testCards.length) {
            generateQuestion();

            btnTestNext.innerText = 'Ответить';
        } else {
            generateResults();
            btnTestNext.innerText = 'Завершить';
        }
    } else {
        divParamNumTab.innerText = 1;

        VisibleSections();
    }
};

function generateResults() {
    if (divFisrtString.classList.contains('visible-not') === false) {
        divFisrtString.classList.add('visible-not');
    }
    if (divResultString.classList.contains('visible-not') === true) {
        divResultString.classList.remove('visible-not');
    }

    divResultString.innerHTML = '';

    let divHeader = document.createElement('div');
    divHeader.className = 'text-full-center text-bold';
    divHeader.innerText = 'Результаты тестирования:';

    let divClearfix;

    divResultString.append(divHeader);
    //divResultString.append(divClearfix);

    let divQuestion;
    let divAnsList;

    for(let numQuestion = 0 ; numQuestion < testCards.length ; numQuestion++) {

        divQuestion = document.createElement('div');
        divQuestion.className = 'text-full-center m-t-20px';
        divQuestion.innerText = String(numQuestion+1) + '. Укажите корректное соответствие для '
            + testCards[numQuestion]['QuestionText'] + ':';

        divAnsList = document.createElement('div');
        divAnsList.className = 'column-33 m-l-33 m-t-20px';

        let arrAnswerColor = testCards[numQuestion]['arrAnswerColor'];

        number = 1;
        for (numCardTest in testCards[numQuestion]['answers']) {
            if(AnswerType === 0) {

                let colorAns = 0;
                for (let i = 0 ; i < arrAnswerColor.length ; i++) {
                    details = arrAnswerColor[i];
                    if(details[0] == testCards[numQuestion]['answers'][numCardTest]) {
                        colorAns = details[1];
                        break;
                    }
                }

                let divWap = document.createElement('div');

                let inputEl = document.createElement('input');
                inputEl.className = 'custom-checkbox';
                if(colorAns == -2) {
                    inputEl.className = inputEl.className + ' custom-checkbox-indeed-right';
                    inputEl.setAttribute('checked', 'true');
                }
                if(colorAns == 1) {
                    inputEl.className = inputEl.className + ' custom-checkbox-right';
                    inputEl.setAttribute('checked', 'true');
                }
                if(colorAns == -1) {
                    inputEl.setAttribute('checked', 'true');
                }
                inputEl.id = 'setVar'+numQuestion+'-'+number;
                inputEl.setAttribute('type', 'checkbox');
                inputEl.setAttribute('data-id', testCards[numQuestion]['answers'][numCardTest]);

                let labelEl = document.createElement('label');
                labelEl.className = 'interactive-only';
                if(colorAns < 0){
                    labelEl.className = labelEl.className + ' text-color-red';
                }
                labelEl.id = 'AnsVar'+numQuestion+'-'+number;
                labelEl.setAttribute('for', 'setVar'+numQuestion+'-'+number);
                for (numCardFull in thisCards) {
                    cardInfo = thisCards[numCardFull];
                    if(testCards[numQuestion]['answers'][numCardTest] === cardInfo['id']) {
                        if(AnswerDirection === 1) {
                            labelEl.innerText = cardInfo['value1'];
                        } else {
                            labelEl.innerText = cardInfo['value2'];
                        }

                        break;
                    }
                }

                divWap.append(inputEl);
                divWap.append(labelEl);
                divAnsList.append(divWap);
            } else {

            }
            number = number + 1;
        }

        divClearfix = document.createElement('div');
        divClearfix.className = 'clearfix';

        divResultString.append(divClearfix);
        divResultString.append(divQuestion);
        divResultString.append(divAnsList);
    }

}
