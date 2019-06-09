//"use strict"

var _API = 'c5443df78b8138e61879f7e80a6abef4';
var _geo={/* скрипт скачан с htmlweb.ru/geo/ */
    city_name:"",
    city_id:0,
    timer:0,
    x:-1,
    y:0,
    o_city:null,   /* input, по умолчанию id='city' */
    o_select:null,
    o_info:null, /* div для вывода результата информации о городе, по умолчанию id='info'*/
    f_Choice:null, /* функция, которая вызывается после выбора, по умолчанию 'CityChoce'*/
    json:false,

    //g=getObj('city').onkeyup=PressKey; // альтернативный вариант назначения обработчика

    init:function(t){ /* t='id input-а' */
        if(!_geo.o_info)_geo.o_info=getObj('info');
        var e;
        if(typeof(t)=='object')e=_geo.o_city=t;
        else e=_geo.o_city=getObj((typeof(t)=='string'?t:'city'));
        //console.log(e.id);
        addEvent(e, 'keyup',_geo.PressKey);
        e.setAttribute('autocomplete','off');
        //t=t+'_select';
        if(!_geo.o_select){
            /* создаем селект
            <select id="info_sel" size=5 style='visibility:hidden;position:absolute;z-index:999;'
                onChange="getObj('city').value=city_name=this.options[this.selectedIndex].text;city_id=this.options[this.selectedIndex].value;"
                onkeyup='PressKey2(event)' ondblclick='return CityChoce()'>
            </select>*/
            _geo.o_select=e=document.createElement('select');
            //e.setAttribute('id',t);
            e.setAttribute('size',5);
            e.setAttribute('style','visibility:hidden;position:absolute;z-index:999;');
            addEvent(e, 'change',function(){_geo.o_city.value=_geo.city_name=this.options[this.selectedIndex].text;_geo.city_id=this.options[this.selectedIndex].value;});
            addEvent(e, 'keyup',_geo.PressKey2);
            addEvent(_geo.o_select, 'dblclick', _geo.Choice);
            document.body.appendChild(e);
        }

        //console.log(_geo.f_Choce);

        _geo.x=-1; _geo.y=0; _geo.city_name="";

    },

    Choice:function(e){
        if(!_geo.f_Choice)_geo.f_Choice=CityChoice;
        _geo.f_Choice(e);
        _geo.o_city.focus();
        _geo.o_city.select();
        _geo.o_select.style.visibility = 'hidden'; // спрячем select
    },

    PressKey2:function(e){ // вызывается при нажатии клавиши в select
        e=e||window.event;
        t=(window.event) ? window.event.srcElement : e.currentTarget; // объект для которого вызвано
        if(e.keyCode==13){ // Enter
            //_geo.o_city.form.onsubmit();
            _geo.o_city.value=_geo.city_name=t.options[t.selectedIndex].text;_geo.city_id=t.options[t.selectedIndex].value;
            _geo.Choice();
            return;}
        if(e.keyCode==38&&t.selectedIndex==0){ // Up
            _geo.o_city.focus();
            _geo.o_select.style.visibility = 'hidden'; // спрячем select
        }
    },

    // Определение координаты элемента
    pageX:function(elem) {
        return elem.offsetParent ?
            elem.offsetLeft + _geo.pageX( elem.offsetParent ) :
            elem.offsetLeft;
    },

    pageY:function(elem) {
        return elem.offsetParent ?
            elem.offsetTop + _geo.pageY( elem.offsetParent ) :
            elem.offsetTop;
    },

    PressKey:function(e){ /* вызывается при отпускании кнопки в input */
        e=e||window.event;
        t=(window.event) ? window.event.srcElement : e.currentTarget; // объект для которого вызвано
        g=_geo.o_select;
        if(_geo.x==-1&&_geo.y==0){// при первом обращении просчитываю координаты
            _geo.x=_geo.pageX(t); _geo.y=_geo.pageY(t);
            g.style.top = _geo.y + t.clientHeight+1 + "px";
            g.style.left = _geo.x + "px";
        }
        if(e.keyCode==40){g.focus();g.selectedIndex=0;return;}
        if(_geo.city_name==t.value)return; // если ничего не изменилось не "замучить" сервер
        _geo.city_name=t.value;
        if(_geo.timer){clearTimeout(_geo.timer);_geo.timer=0;}
        if(_geo.city_name.length<3){
            g.style.visibility = 'hidden'; // спрячем select
            return;}
        _geo.timer=window.setTimeout('_geo.Load()',1000);  // загружаю через 1 секунду после последнего нажатия клавиши
    },

    Load:function(){
        _geo.timer=0;
        _geo.o_select.options.length=0;
        ajaxLoad(_geo.o_select, '//htmlweb.ru/geo/api.php?city_name='+_geo.city_name+'&api_key='+_API);
        _geo.o_select.style.visibility='visible';
    },


    getGeo:function(p,f){ /* p=0 - по IP, p=1 по данным браузера с запросом пользователя, p=2 - точно по данным браузера с запросом пользователя и использованием GPS
        f=html или f=json */
        if(!p || !navigator.geolocation){

            ajaxLoad(_geo.o_info, '/geo/api.php?ip'+(f?'&'+f:''),'Загрузка...','',
                function (obj, ajaxObj, callback){
                    if(ajaxObj.readyState==4) updateObj(obj, ajaxObj.responseText);
                    hide('wait');
                    return false;
                });

        }else
        if(navigator.geolocation) {
            _geo.timer = setTimeout("_geo.geolocFail()", 10000);

            navigator.geolocation.getCurrentPosition(function(position) {
                    clearTimeout(_geo.timer); _geo.timer=0;
                    //updateObj(_geo.o_info, "latitude (широта): <b>" + position.coords.latitude + "</b> , longitude (долгота): <b>" + position.coords.longitude + "</b>");
                    ajaxLoad(_geo.o_info, '/geo/api.php?latitude='+position.coords.latitude+'&longitude='+position.coords.longitude+'&city_coming&limit=1'+(f?'&'+f:''),'Загрузка...','',
                        function (obj, ajaxObj, callback){
                            if(ajaxObj.readyState==4) updateObj(obj, ajaxObj.responseText);
                            hide('wait');
                            return false;
                        }
                    );

                }, function(error) {
                    clearTimeout(_geo.timer); _geo.timer=0;
                    _geo.geolocFail(error);
                },
                {
                    maximumAge:100000,
                    timeout:10000,
                    enableHighAccuracy:!!(p==2)
                });
        } else {
            _geo.geolocFail();
        }
    },

    geolocFail:function(e){
        if(typeof(e)=='object'){
            switch (e.code) {
                case e.PERMISSION_DENIED:
                    updateObj(_geo.o_info, "Посетитель не дал доступ к сведениям о местоположении", true);
                    break;
                case e.POSITION_UNAVAILABLE:
                    updateObj(_geo.o_info, "Невозможно получить сведения о местоположении", true);
                    break;
                case e.TIMEOUT:
                    updateObj(_geo.o_info, "Истёк таймаут, в течение которого должны быть получены данные о местоположении", true);
                    break;
                default:
                    updateObj(_geo.o_info, "Возникла ошибка '" + e.message + "' с кодом " + e.code, true);
            }
        }else{
            updateObj(_geo.o_info, "Ваш браузер не поддерживает гео-локацию", true);
        }
    }

};

/* дальше идут общие функции */
if(0){
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
        ajaxObj.setRequestHeader("Referer", window.location.href);
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
                        eval(ajaxObj.responseText.replace(/\n/g,";").replace(/\r/g,""));
                    }else if(ajaxObj.getResponseHeader("Content-Type").indexOf('json')>=0){
                        ajaxJson(obj,ajaxObj.responseText);
                    }else updateObj(obj, ajaxObj.responseText);
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


}
