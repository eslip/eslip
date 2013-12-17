<?php
class xmlApi {

	private $xmlFile = "";

	public function __construct($file = "")
    {
        if (empty($file))
        {
            throw new Exception('ESLIP ERROR: No XML file setted in XML API.');
        }

        $this->setXmlFile($file);
    }
	
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
	
	function saveFormattedXml($xml){
		$xml->asXml($this->xmlFile);
		$dom = new DOMDocument("1.0");
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->load($this->xmlFile);
		$dom->save($this->xmlFile);
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

	function addElement($id, $fields, $values, $element, $parent){
		$xml = $this->getXml();
		$query = $this->getQuery($parent, null);
		$result = $xml->xpath($query);
		$newElem = $result[0]->addChild($element);
		if (!empty($id)){
			$newElem->addAttribute("id", $id);
		}
		for ($i=0; $i < count($fields); $i++){
			$newElem->addChild($fields[$i], $values[$i]);
		}
		$this->saveFormattedXml($xml);
	}
	
	function removeElementById($element, $elementId){
		$element = $element."[@id='".$elementId."']";
		$xml = $this->getXml();
		$query = $this->getQuery($element);
		$result = $xml->xpath($query);
		if (!empty($result)){
			unset($result[0][0]);
			$this->saveFormattedXml($xml);
		}
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
		$result = $result[0]->$fieldToSet;
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