
// http://stackoverflow.com/questions/10805912/how-to-include-jquery-in-an-external-js-file

// http://css-tricks.com/snippets/jquery/load-jquery-only-if-not-present/

function getPluginURL(){
    var scripts = document.getElementsByTagName('script');
    var index = scripts.length - 1;
    var myScript = scripts[index].src;
    var auxPluginURL = myScript.substr(0, myScript.lastIndexOf( '/' ));
    auxPluginURL = auxPluginURL.substr(0, auxPluginURL.lastIndexOf( '/' )+1);
    return auxPluginURL; 
}

var pluginURL = getPluginURL();

window.onload = function(){
    createWidget();
}

function createHTTPObject(){
    var xmlhttp;
    if (window.ActiveXObject){
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }else{
        if (window.XMLHttpRequest){
            xmlhttp = new XMLHttpRequest();
        }
    }
    return xmlhttp;
}

function createWidget(){
    var ajax = createHTTPObject();
    ajax.open("GET", pluginURL+"frontend/widget.php", true);
    ajax.onreadystatechange = function(){ 
        if (ajax.readyState == 4)
        {
            widgetHTML = ajax.responseText;
            document.getElementById("ESLIP_Plugin").innerHTML = widgetHTML;   
        }
    }
    ajax.send();
}

function clickLogin(server){
    var referer = window.location.href;
    var url = pluginURL+"eslip_process.php?server="+server+"&referer="+encodeURIComponent(referer);
    openLoginWindow(url);
}

function openLoginWindow(url){
	var newwindow;
    var  screenX    = typeof window.screenX != 'undefined' ? window.screenX : window.screenLeft,
         screenY    = typeof window.screenY != 'undefined' ? window.screenY : window.screenTop,
         outerWidth = typeof window.outerWidth != 'undefined' ? window.outerWidth : document.body.clientWidth,
         outerHeight = typeof window.outerHeight != 'undefined' ? window.outerHeight : (document.body.clientHeight - 22),
         width    = 500,
         height   = 270,
         left     = parseInt(screenX + ((outerWidth - width) / 2), 10),
         top      = parseInt(screenY + ((outerHeight - height) / 2.5), 10),
         features = (
            'width=' + width +
            ',height=' + height +
            ',left=' + left +
            ',top=' + top
          );
    newwindow = window.open(url,'ESLIP',features);
    if (window.focus){
        newwindow.focus();
    }
    return false;
}