<?php

function xmlapi_getXml(){
	$xml = simplexml_load_file("config.xml");
	return $xml;
}

function xmlapi_getQuery($element, $parent=null){
	$query = "/";
	if ($parent != null && $parent != ""){
		$query .= "/".$parent;
	}
	$query .= "/".$element;
	return $query;
}

function xmlapi_getElementValue($element, $parent=null){
	$xml = xmlapi_getXml();
	$query = xmlapi_getQuery($element, $parent);
	$result = $xml->xpath($query);
	return (!empty($result)) ? $result[0] : false ;
}

function xmlapi_setElementValue($element, $value, $parent=null){
	$xml = xmlapi_getXml();
	$query = xmlapi_getQuery($element, $parent);
	$result = $xml->xpath($query);
	$result[0][0] = $value;
	$xml->asXml('config.xml');
}

function xmlapi_getElementList($element, $parent=null){
	$xml = xmlapi_getXml();
	$query = xmlapi_getQuery($element, $parent);
	$result = $xml->xpath($query);
	return $result;
}

function xmlapi_getElementById($element, $elementId){
	$element = $element."[@id='".$elementId."']";
	return xmlapi_getElementValue($element);
}

function xmlapi_updateElementById($fields, $values, $element, $elementId){
	$parent = $element."[@id='".$elementId."']";
	for ($i=0; $i < count($fields); $i++){
		xmlapi_setElementValue($fields[$i], $values[$i], $parent);
	}
}

function xmlapi_getElementListByFieldValue($field, $value, $element){
	$xml = xmlapi_getXml();
	$query = "//".$element."[".$field."='".$value."']";
	$result = $xml->xpath($query);
	return $result;
}

function xmlapi_getElementAttributesListById($element, $elementId){
	$element = $element."[@id='".$elementId."']";
	return xmlapi_getElementValue($element)->attributes();
}

function xmlapi_getElementAttributeById($attribute, $element, $elementId){
	$result = xmlapi_getElementAttributesListById($element, $elementId);
	return $result->$attribute;
}

function xmlapi_setElementAttributeById($attribute, $value, $element, $elementId){
	$element = $element."[@id='".$elementId."']";
	$xml = xmlapi_getXml();
	$query = xmlapi_getQuery($element);
	$result = $xml->xpath($query);
	$attributes = $result[0]->attributes()->$attribute = $value;
	$xml->asXml('config.xml');
}

function xmlapi_setElementAttributesById($attributes, $values, $element, $elementId){
	for ($i=0; $i < count($attributes); $i++){
		xmlapi_setElementAttributeById($attributes[$i], $values[$i], $element, $elementId);
	}
}

?>