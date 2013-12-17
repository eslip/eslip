<?php

function getSystemLang(){
	return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
}


function getDefaultLang(){
	return "es";
}

function getServiceUrl(){
	return "php/servicios.php";
}

?>