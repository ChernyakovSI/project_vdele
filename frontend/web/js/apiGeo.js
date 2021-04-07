
    var _API = 'c5443df78b8138e61879f7e80a6abef4';

    /* функция обработки выбора, в момент её вызова в _geo.city_id будет код города,
       в _geo.city_name название */
    function CityChoice(e){
        //alert('Выбран '+_geo.city_name+', код:'+_geo.city_id);
        //ajaxLoad(_geo.o_info, '//htmlweb.ru/geo/api.php?city='+_geo.city_id+'&api_key='+_API); // загрузить информацию о городе
        //ajaxLoad(_geo.o_info, '//htmlweb.ru/geo/api.php?weahter='+_geo.city_id+'&api_key='+_API); // загрузить информацию о погоде в городе
        //updateObj(_geo.o_info, 'Выбран <b>'+_geo.city_name+'</b>, код: <b>'+_geo.city_id+'</b>');

        //console.log(_geo.city_name);
        //ajaxToBD('/site/acAddCity', {'id_city':_geo.city_id, 'name_city':_geo.city_name});
        ajaxToBD('/site/ac-add-city', {id_city:_geo.city_id, name_city:_geo.city_name});

        return false;

//-->
    }

    function getObj(objID){
        if (document.getElementById) {return document.getElementById(objID);}
        else if (document.all) {return document.all[objID];}
        else if (document.layers) {return document.layers[objID];}
        return'';
    }

    function ajaxLoad(obj,url,defMessage,post,callback){
        var ajaxObj;
        if(typeof(obj)!="object")obj=document.getElementById(obj);
        if(defMessage&&obj)obj.innerHTML=defMessage;
        if(window.XMLHttpRequest){
            ajaxObj = new XMLHttpRequest();
        } else if(window.ActiveXObject){
            ajaxObj = new ActiveXObject("Microsoft.XMLHTTP");
        } else {
            return false;
        }
        ajaxObj.open ((post?'POST':'GET'), url);
        if(post&&ajaxObj.setRequestHeader){
            if(post=='chat'){ajaxObj.chat=true;post='';}
            else ajaxObj.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8;");
        }
        //ajaxObj.setRequestHeader("Referer", window.location.href);
        ajaxObj.onreadystatechange = ajaxCallBack(obj,ajaxObj,(callback?callback:null));
        ajaxObj.send(post);
        return false;
    }

    if (!window.getComputedStyle) { // борьба с IE
        window.getComputedStyle = function(el, pseudo) {
            this.el = el;
            this.getPropertyValue = function (prop) {
                var re = /(\-([a-z]){1})/g;
                if (prop == "float") prop = "styleFloat";
                if (re.test(prop)) {
                    prop = prop.replace(re, function () {
                        return arguments[2].toUpperCase();
                    });
                }
                return el.currentStyle[prop] ? el.currentStyle[prop] : null;
            };
            return this;
        }
    }

    function updateObj(obj, data, bold, blink){
        if(bold)data=data.bold();
        if(blink)data=data.blink();
        if(typeof(obj)!="object")obj=document.getElementById(obj);
        o=obj;
        do{	if(o.style){c=window.getComputedStyle(o, null);
            if(c.display!="block"&&c.display!="inline"&&c.display.substr(0,5)!="table"){o.style.display=(o.tagName=="DIV"?"block":"inline");}
        }
            o=o.parentNode;
        }while(o);
        ajaxEval(obj, data);
    }

    function ajaxEval(obj, data){
        if(obj.tagName=='INPUT'||obj.tagName=='TEXTAREA'){
            if(obj.value!=data){
                obj.value=data;
                if(obj.onchange!=null)obj.onchange(obj);
            }
        }else if(obj.tagName=='SELECT'){
            if(typeof(data)=='number' || data.indexOf('<')<0){ // это value
                for(i=0;i<obj.options.length;i++)
                    if(obj.options[i].value==data){obj.options[i].selected=true;break;}
            }else{
                obj.options.length = 0;
                var re=new RegExp ("<option[^<]+</option>","img");
                data=data.match(re);
                if(data){
                    for(i=0;i<data.length;i++){
                        var re0 = new RegExp ("value=[\'\"]([^\'\"]+)[\'\"]?","i"); value=re0.exec(data[i]); value= value==null? '' : value[1];
                        if(!value){var re0 = new RegExp ("value=([^<>]+)","i"); value=re0.exec(data[i]); value= value==null? '' : value[1];}
                        var re1=new RegExp ("<option[^>]+>([^<]+)</option>","i"); text=re1.exec(data[i]); text= text==null? null : text[1];
                        var re4 = new RegExp ("class=[\'\"]([^\'\"]+)[\'\"]","i"); defclass=re4.exec(data[i]);
                        j=obj.options.length;
                        if (text !=null){
                            var re2 = /selected/i; defSelected=re2.test(data[i]);
                            obj.options[j] = new Option(text, value,defSelected,defSelected);
                            var re3 = /disabled/i; if(re3.test(data[i]))obj.options[j].disabled=true;
                            if(defclass!=null) obj.options[j].className=defclass[1];
                        }else obj.options[j] = new Option('ОШИБКА!', '' );
                    }
                    if(obj.options.length > 0) {
                        obj.classList.remove('hideSelectList');
                    }
                    else {
                        obj.classList.add('hideSelectList');
                    }
                }
                else {
                    obj.classList.add('hideSelectList');
                }}
        }else if(typeof(data)=='object' && obj.tagName=='A'){
            //console.log('data=',data, ', obj=',obj);
            for(k in data){
                //console.log(obj,'[',k, '] =',data[k]);
                if(k=='innerHTML')obj.innerHTML=data[k];
                else obj.setAttribute(k, data[k]);
            }
        }else obj.innerHTML = data;
    }

    function ajaxJson(obj, data){
        if(!data)return;
        ajaxObj=eval("(" + data + ")");
        if(obj.tagName!="FORM"&&obj.form)obj=obj.form;
        if(obj.tagName!="FORM"){
            ajaxEval(obj, ajaxObj);
            return;
        }
        for(key in ajaxObj){
            o=obj[key];
            if(typeof(ajaxObj[key])=='object'){
                if(typeof(o)=='object' && o.tagName=='SELECT'){
                    o.options.length = 0; j=0;
                    s=ajaxObj[key];
                    for(k in s){
                        m=s[k];
                        if(typeof(m)=='object'){
                            if(typeof(m['selected'])=='undefined')m['selected']=false;
                            if(typeof(m['value'])=='undefined')m['value']=k;
                            o.options[j++] = new Option(m['text'], m['value'] ,m['selected'],m['selected']);
                            for(var k1 in m)if(k1!='text'&&k1!='value'&&k1!='selected')o.options[j-1].setAttribute(k1,m[k1]);
                        }else{
                            o.options[j++] = new Option(m, k,false,false);
                        }
                    }
                }else{
                    s=(typeof(o)=='undefined'?document.createElement("input"):o);
                    s.setAttribute('name', key);
                    s.setAttribute('type', 'hidden');/*по умолчанию скрытый*/
                    if(typeof(o)=='undefined')obj.appendChild(s);
                    o=ajaxObj[key];
                    for(k in o)s.setAttribute(k, o[k]);
                }
            }else if(typeof(o)!='undefined'&&typeof(o)!='null'){
                ajaxEval(o, ajaxObj[key]);
            }else if((pos=key.indexOf('.'))>0){ // имя.атрибут=значение
                o=key.substr(0,pos);
                o=obj[o];
                if(typeof(o)!='undefined'&&typeof(o)!='null'){
                    s=key.substr(pos+1);
                    if(s=='disabled')o.disabled=ajaxObj[key];
                    else if(s=='value'&&o.tagName=='SELECT'){
                        o.options.length = 0;
                        t=ajaxObj[key]; s='';
                        if(o.name.substr(o.name.length-3,3)=='_cs'){
                            s=eval('o.form.'+o.name.substr(0,o.name.length-3));
                            if(s)t=s.value;
                        }
                        o.options[0] = new Option(t, ajaxObj[key],true,true);
                        if(s)if(o1=s.getAttribute('after'))eval(o1);
                    }else o.setAttribute(s, ajaxObj[key]);
                }
            }else if(o=getObj(key))ajaxEval(o,ajaxObj[key]);
        }
        if(obj.style)obj.style.display='block';
    }

    function ajaxCallBack(obj, ajaxObj, callback){
        return function(){
            if(ajaxObj.readyState==4){
                if(callback) if(!callback(obj,ajaxObj))return;
                if (ajaxObj.status==200){
                    //console.log(ajaxObj.responseText);
                    if(ajaxObj.getResponseHeader("Content-Type").indexOf("application/x-javascript")>=0){
                        //console.log('1');
                        eval(ajaxObj.responseText.replace(/\n/g,";").replace(/\r/g,""));
                    }else if(ajaxObj.getResponseHeader("Content-Type").indexOf('json')>=0){
                        //console.log('2');
                        ajaxJson(obj,ajaxObj.responseText);
                    }else {
                        //console.log('3');
                        updateObj(obj, ajaxObj.responseText)
                    };
                }
                else updateObj(obj, ajaxObj.status+' '+ajaxObj.statusText,1,1);
            }
            else if(ajaxObj.readyState==3&&ajaxObj.chat)obj.innerHTML=ajaxObj.responseText;
        }
    }

    var addEvent = (function(){
        if (document.addEventListener){
            return function(obj, type, fn, useCapture){
                if(!obj)console.error(obj, type, fn, useCapture);
                if(typeof(obj)!="object")obj=document.getElementById(obj);
                //console.log("addEvent:",obj,fn);
                if(obj)obj.addEventListener(type, fn, useCapture);
            }
        } else if (document.attachEvent){ // для Internet Explorer
            return function(obj, type, fn, useCapture){
                if(typeof(obj)!="object")obj=document.getElementById(obj);
                obj.attachEvent("on"+type, fn);
            }
        } else {
            return function(obj, type, fn, useCapture){
                if(typeof(obj)!="object")obj=document.getElementById(obj);
                obj["on"+type] = fn;
            }
        }
    })();

    function removeEvent(obj, eventType, handler)
    {    if(obj&&typeof(obj)!="object")obj=document.getElementById(obj);
        return (obj.detachEvent ? obj.detachEvent("on" + eventType, handler) : ((obj.removeEventListener) ? obj.removeEventListener(eventType, handler, false) : null));
    }

    function getEventTarget(e) {
        e = e || window.event;
        var target=e.target || e.srcElement;
        if(typeof target == "undefined")return e; // передали this, а не event
        if (target.nodeType==3) target=target.parentNode;// боремся с Safari
        return target;
    }

    function ajaxToBD(url, params){
        if(window.XMLHttpRequest){
            ajaxObj = new XMLHttpRequest();
        } else if(window.ActiveXObject){
            ajaxObj = new ActiveXObject("Microsoft.XMLHTTP");
        } else {
            return false;
        }
        ajaxObj.open('POST', url, true);

        ajaxObj.onreadystatechange = ajaxCallBackFromBD(ajaxObj);
        ajaxObj.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        post_params = 'id_city=' + encodeURIComponent(params.id_city)+'&name_city='+encodeURIComponent(params.name_city);
        //post_params.id_city = params.id_city;
        //console.log('Перед вызовом id = '+post_params.id_city);
        ajaxObj.send(post_params);
        return false;
    }

    function ajaxCallBackFromBD(ajaxObj){
        return function(){
            if(ajaxObj.readyState==4){
                if (ajaxObj.status==200){
                    elemIdCity = document.getElementById('id_city');
                    console.log(ajaxObj.responseText);
                    elemIdCity.value = ajaxObj.responseText;
                    //console.log(ajaxObj);
                    //updateObj(obj, ajaxObj.responseText)
                }
                else
                {
                    //console.log(ajaxObj.responseText);
                }
                //else updateObj(obj, ajaxObj.status+' '+ajaxObj.statusText,1,1);
            }
            else {
                //console.log(ajaxObj.responseText);
            }
        }
    }