<?php

function base64url_encode($plainText) {
   
    $base64 = base64_encode($plainText);
    $base64url = strtr($base64, '+/=', '-_.');
    return $base64url;  
}

function base64url_decode($plainText) {
   
    $base64url = strtr($plainText, '-_.', '+/=');
    $base64 = base64_decode($base64url);
    return $base64;  
}

function getSystemLang(){
	return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
}


function getDefaultLang(){
	return "es";
}

function getServiceUrl(){
	return "eslip_service.php";
}

function getAdminServiceUrl(){
	return "eslip_admin_service.php";
}

function getAdminUrl(){
	return "admin.php";
}

function getEncrypted($str){
	return sha1($str);
}

function safeValue($obect, $value){
	return (IsSet($obect->$value)) ? $obect->$value : '';
}