<?php

/**
* Aqui se crea la variable $xmlApi, la cual incluye las configuraciones de todos los proveedores de
* identidad y algunos parametros necesarios para el funcionamiento del plugin. 
* Tambien se incluye el archivo de lenguaje correspondiente al idioma configurado en el administrador del 
* plugin el cual define las constantes correspondientes para todos los textos que se utilizan en el 
* plugin.
*
* @author Nicolás Burghi [nicoburghi@gmail.com]
* @author Martín Estigarribia [martinestiga@gmail.com]
* @license http://opensource.org/licenses/mit-license.php The MIT License (MIT)
* @package Eslip
*/

include_once("eslip_helper.php");
include_once("eslip_xml_api.php");

// Se crea la variable $xmlApi
$xmlApi = new xmlApi(dirname(__FILE__) . DIRECTORY_SEPARATOR . "config.xml");

// Se incluye el lenguaje correspondiente
if (isset($_POST["lang"]))
{
	$selectedLang = (empty($_POST["lang"])) ? getSystemLang() : $_POST["lang"];
}
else
{
	$selectedLang = $xmlApi->getElementListByFieldValue("selected", "1", "language");
	$selectedLang = (empty($selectedLang) || empty($selectedLang[0]->code )) ? getSystemLang() : (String)$selectedLang[0]->code;	
}

include_once("i18n/".$selectedLang.".php");