<?php

/*
**	Acá el desarrollador va hacer el login correspondiente en su sitio con los datos que le enviamos por POST o tambien lo puede obtener de la SESSION
*/

session_start();

var_dump($_POST);

if($_POST['state'] == 'success'){
	var_dump(json_decode($_POST['user']));	
}else{
	var_dump(json_decode($_POST['error']));	
}

var_dump($_SESSION);