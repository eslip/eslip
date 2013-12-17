<?php

include("funciones.php");
//include("xml_api.php");
include("../eslip_xml_api.php");


	/****** XML API *****/
	
	//INIT
	$xmlAapi = new xmlApi();
	$xmlAapi->setXmlFile("../config.xml");
	
	/*$eslip_langs = $xmlAapi->getElementList("language");
	foreach( $eslip_langs as $lang ){
		var_dump($lang->name);
		$xmlAapi->setElementValue("selected","111","language");
	}*/
	
	
	//$lang = $xmlAapi->getElementListByFieldValue("code", "es", "language");
	//$xmlAapi->setElementValue("selected","1",$lang[0]);
	
	/*
	$lang = $xmlAapi->getElementListByFieldValue("code", "es", "language");
	var_dump($lang);
	*/
	
	
	/*$langs = $xmlAapi->getElementList("language");
	var_dump ($langs);
	foreach( $langs as $lang ){
		echo "<h4>Lang: ".($lang->name)."</h4>";
	}
	*/
	
	// SET ELEMENT VALUE
	/*
	$xmlAapi->setElementValue("appRoot", "lalalala", "configuration");
	*/
	// -------------------- //
	
	// GET ELEMENT VALUE
	/*
	$result = (string) $xmlAapi->getElementValue("appRoot", "configuration");
	var_dump($result);
	*/
	// -------------------- //
	
	// GET ELEMENT LIST
	/*
	$identityProviders = $xmlAapi->getElementList("identityProvider");
	foreach( $identityProviders as $sn ){
		echo "<h4>Social Network Name: ".($sn->name)."</h4>";
	}
	*/
	// -------------------- //
	
	// GET ELEMENT BY ID
	/*
	$identityProvider = $xmlAapi->getElementById("identityProvider","facebook");
	var_dump($identityProvider);
	echo "Name: ".$identityProvider->name."<br/>";
	echo "oauth: ".$identityProvider->oauth."<br/>";
	*/
	// -------------------- //
	
	// UPDATE ELEMENT BY ID
	/*
	$xmlAapi->updateElementById(array("requestTokenUrl","oauth"),array("---","2.0"),"identityProvider","facebook");
	*/
	// -------------------- //

	// UPDATE ELEMENT
	/*
	$xmlAapi->updateElement(array("selected"), array("0"), "language");
	*/
	// -------------------- //
	
	// ADD ELEMENT
	/*
	$xmlAapi->addElement(null,array("name","code","selected"), array("nuevo","nu","0"), "language", "languages");
	$xmlAapi->addElement("redloca", array("name","label","active"), array("RedLoca","Red Loca","0"), "identityProvider", "identityProviders");
	*/
	// -------------------- //
	
	// REMOVE ELEMENT BY ID
	
	$xmlAapi->removeElementById("identityProvider","redloca");
	
	// -------------------- //
	
	// GET ELEMENT ATTRIBUTES LIST BY ID
	/*
	echo "<br/>Attributes: <br/>";
	$attr = $xmlAapi->getElementAttributesListById("identityProvider","facebook");
	foreach($attr as $a => $b) {
		echo $a,'="',$b,"\"\n";
	}
	*/
	// -------------------- //
	
	// GET ELEMENT ATTRIBUTE BY ID
	/*	
	$attr = $xmlAapi->getElementAttributeById("la","identityProvider","facebook");
	echo "<br/>Attribute: ".$attr."<br/>";
	*/
	// -------------------- //
	
	// SET ELEMENT ATTRIBUTES BY ID
	//$xmlAapi->setElementAttributeById("la","aaaaa","identityProvider","facebook");
	// -------------------- //
	
	// SET ELEMENT ATTRIBUTES BY ID
	//$xmlAapi->setElementAttributesById(array("la","id"),array("aaaaaX","facebookX"),"identityProvider","facebook");
	// -------------------- //
	
	
	// GET ELEMENT LIST BY FIELD VALUE
	//$result = $xmlAapi->getElementListByFieldValue("active", "1", "identityProvider");
	//var_dump($result);
	// -------------------- //
	
	// SET ELEMENT LIST BY FIELD VALUE
	//$xmlAapi->setElementListByFieldValue("code", "en", "language",null,"selected","1");
	// -------------------- //
	

	/****** FIN XML API *****/
	

	
	
	
	
	/*
	$att = 'attribueName';
	// You can access an element's attribute just like this :
	$attribute = $element->attributes()->$att;

	// This will save the value of the attribute, and not the objet
	$attribute = (string)$element->attributes()->$att;

	// You also can edit it this way :
	$element->attributes()->$att = 'New value of the attribute';
	*/
		
	
	/*
	$project = $xml_object->xpath("//photo"); while(list( , $node) = each($project)) { var_dump($node); } would iterate over the entire loop.
	//photo[filename='$image_id']/preceding-sibling::photo[1] for the previous, 
	//photo[filename='$image_id']/following-sibling::photo[1] for the one after
	*/

	/*
	$xml = simplexml_load_file('../config.xml');
	$h = (string) $xml->{"dataBase"}->{"host"}->{0};
	var_dump($h);
	$xml->{"dataBase"}->{"host"}->{0} = $dbInfo["host"];
	$xml->asXml('../config.xml');
	*/
	

	/*
	$doc = new DOMDocument();
	$doc->load( '../config.xml' );
	$dataBase = $doc->getElementsByTagName( "dataBase" );

	foreach( $dataBase as $bd ){

		$host = $bd->getElementsByTagName( "host" )->item(0);
		
		$nuevoHost = $doc->createElement( "host" );
		$nuevoHost->appendChild(
			$doc->createTextNode( $dbInfo["host"] )
		);
		$host = $host->parentNode->replaceChild($nuevoHost, $host);

		// $dbInfo = array(
			// 'host' => $nuevoHost->nodeValue,
			// 'user' => $bd->getElementsByTagName( "user" )->item(0)->nodeValue,
			// 'pass' => $bd->getElementsByTagName( "pass" )->item(0)->nodeValue,
			// 'dbname' => $bd->getElementsByTagName( "dbname" )->item(0)->nodeValue
		// );

	}
	$doc->save('../config.xml');
	var_dump($dbInfo);
	//http://www.hcosta.info/wp/2011/10/crear-y-leer-xml-utilizando-arrays-y-objetos-en-php/

	*/
		
?>