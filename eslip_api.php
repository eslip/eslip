<?php
include_once("eslip_helper.php");
include_once("eslip_xml_api.php");

/*** Get XML Data ***/

$xmlApi = new xmlApi("config.xml");

// Language
$selectedLang = $xmlApi->getElementListByFieldValue("selected", "1", "language");
$selectedLang = (empty($selectedLang) || empty($selectedLang[0]->code )) ? getSystemLang() : (String)$selectedLang[0]->code;
include_once("i18n/".$selectedLang.".php");

?>