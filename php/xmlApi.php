<?php
class xmlApi {
	var $xmlFile = "";
	
	function setXmlFile($file){
		$this->xmlFile = $file;
	}
	
	function getXml(){
		$xml = simplexml_load_file($this->xmlFile);
		return $xml;
	}
	
	function saveXml($xml){
		$xml->asXml($this->xmlFile);
	}
	
	function getQuery($element, $parent=null){
		$query = "/";
		if ($parent != null && $parent != ""){
			$query .= "/".$parent;
		}
		$query .= "/".$element;
		return $query;
	}

	function getElementValue($element, $parent=null){
		$xml = $this->getXml();
		$query = $this->getQuery($element, $parent);
		$result = $xml->xpath($query);
		return (!empty($result)) ? $result[0] : false ;
	}

	function setElementValue($element, $value, $parent=null){
		$xml = $this->getXml();
		$query = $this->getQuery($element, $parent);
		$result = $xml->xpath($query);
		$result[0][0] = $value;
		$this->saveXml($xml);
	}

	function getElementList($element, $parent=null){
		$xml = $this->getXml();
		$query = $this->getQuery($element, $parent);
		$result = $xml->xpath($query);
		return $result;
	}

	function getElementById($element, $elementId){
		$element = $element."[@id='".$elementId."']";
		return $this->getElementValue($element);
	}

	function updateElementById($fields, $values, $element, $elementId){
		$parent = $element."[@id='".$elementId."']";
		for ($i=0; $i < count($fields); $i++){
			$this->setElementValue($fields[$i], $values[$i], $parent);
		}
	}
	
	function updateElement($fields, $values, $element){
		$xml = $this->getXml();
		$query = $this->getQuery($element, $parent);
		$result = $xml->xpath($query);
		foreach( $result as $node ){
			for ($i=0; $i < count($fields); $i++){
				$n = $node->$fields[$i];
				$n[0][0] = $values[$i];
			}
		}
		$this->saveXml($xml);
	}

	function getElementListByFieldValue($field, $value, $element, $parent=null){
		$xml = $this->getXml();
		$query = $this->getQuery($element, $parent)."[".$field."='".$value."']";
		$result = $xml->xpath($query);
		return $result;
	}
	
	function setElementListByFieldValue($field, $value, $element, $parent=null,$fieldToSet,$valueToSet){
		$xml = $this->getXml();
		$query = $this->getQuery($element, $parent)."[".$field."='".$value."']";
		$result = $xml->xpath($query);
		var_dump($result[0]);
		$result = $result[0]->$fieldToSet;
		var_dump($result);
		$result[0][0] = $valueToSet;
		$this->saveXml($xml);
	}

	function getElementAttributesListById($element, $elementId){
		$element = $element."[@id='".$elementId."']";
		return $this->getElementValue($element)->attributes();
	}

	function getElementAttributeById($attribute, $element, $elementId){
		$result = $this->getElementAttributesListById($element, $elementId);
		return $result->$attribute;
	}

	function setElementAttributeById($attribute, $value, $element, $elementId){
		$element = $element."[@id='".$elementId."']";
		$xml = $this->getXml();
		$query = $this->getQuery($element);
		$result = $xml->xpath($query);
		$attributes = $result[0]->attributes()->$attribute = $value;
		$this->saveXml($xml);
	}

	function setElementAttributesById($attributes, $values, $element, $elementId){
		for ($i=0; $i < count($attributes); $i++){
			$this->setElementAttributeById($attributes[$i], $values[$i], $element, $elementId);
		}
	}
}
?>