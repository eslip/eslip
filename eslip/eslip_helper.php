<?php

/**
* Funciones auxiliares que utiliza el plugin
*
* @author Nicolás Burghi [nicoburghi@gmail.com]
* @author Martín Estigarribia [martinestiga@gmail.com]
* @license http://opensource.org/licenses/mit-license.php The MIT License (MIT)
* @package Eslip
*/

/**
* Codifica una cadena en base64 reemplazando ciertos caracteres para poder usar el resultado 
* en una URL
*
* @access public
* @param string $plainText Cadena a ser codificada
* @return string Cadena codificada en base64
*/

function base64url_encode($plainText)
{   
    $base64 = base64_encode($plainText);
    $base64url = strtr($base64, '+/=', '-_.');
    return $base64url;  
}

/**
* Decodifica una cadena previamente codificada en base64 con la funcion base64url_encode
* por lo tanto antes de realizar la decodificación se reemplazan ciertos caracteres para 
* poder realiazar la decodificación
*
* @access public
* @param string $base64url Cadena a ser decodificada
* @return string Cadena resultante de la decodificación
*/

function base64url_decode($base64url)
{   
    $base64 = strtr($base64url, '-_.', '+/=');
    $plainText = base64_decode($base64);
    return $plainText;  
}

/**
* Devuelve el contenido de la cabecera Accept-Language: de la petición actual, el cual es
* utilizado como lenguage default del administrador.
*
* @access public
* @return string Cadena que identifica un lenguaje
*/

function getSystemLang()
{
	return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
}

/**
* Calcula y retorna el hash sha1 de un string que se recibe como parametro
*
* @access public
* @param string $str Cadena de entrada
* @return string Devuelve el hash sha1 como un string.
*/

function getEncrypted($str)
{
	return sha1($str);
}

/**
* Funcion para otener de manera segura el valor de una clave de un objeto. Primer se verifica
* que exista la clave dentro del objeto y si existe se retorna el valor.
*
* @access public
* @param string $object Objeto en el que se va verificar la existencia de la clave
* @param string $key Clave del objeto
* @return mixed Si existe la clave en el objeto retorna el valor de dicha clave sino un string vacio
*/

function safeValue($object, $key)
{
	return (IsSet($object->$key)) ? $object->$key : '';
}

/**
* Funcion para otener la URL completa de la página actual
*
* @access public
* @return string La URL de la página actual
*/

function currentPageUrl(){
    $page_url   = 'http';
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
        $page_url .= 's';
    }
    return $page_url.'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
}