<?php

include("funciones.php");
include("xmlApi.php");
//include("../i18n/en.php");


$action = $_REQUEST["action"];
$step_number = $_REQUEST["step_number"]; 
switch($action){
	case "wizard":
		?>
		
		<?php
		switch($step_number){
			case 1:
			?>	
				<h2 class="StepTitle"><?php echo ucwords(ConfigGenerales); ?></h2>
				<form id="bdform">
				<div class="contenedorCentral borderBox">
					<div class="reng">
						<label for="siteUrl"><?php echo SiteUrl; ?>:</label>
						<input type="text" id="siteUrl" name="siteUrl" value="" />
					</div>
					<div class="reng">
						<label for="callbackUrl"><?php echo CallbackUrl; ?>:</label>
						<input type="text" id="callbackUrl" name="callbackUrl" value="" />
					</div>
					<div class="reng">
						<label for="pluginUrl"><?php echo PluginUrl; ?>:</label>
						<input type="text" id="pluginUrl" name="pluginUrl" value="" />
					</div>
				</div>
				</form>
			<?php
			break;
			
			case 2:
			?>
				<h2 class="StepTitle"><?php echo ucwords(CrearAppsTitulo); ?></h2>
				<div class="contenedorCentral borderBox">
					<div class="reng">
						<span>Cree las apps ...</span>
					</div>
				</div>
			<?php
			break;
		}
	break;                     
	
	case "service":
		
		switch($step_number){
			case 1:
			
				var_dump($_POST);
			
				$xmlAapi = new xmlApi();
				$xmlAapi->setXmlFile("../config.xml");
				
				//update language
				$xmlAapi->updateElement(array("selected"), array("0"), "language");
				$xmlAapi->setElementListByFieldValue("code", $_POST["language"], "language",null,"selected","1");
				//$lang = $xmlAapi->getElementListByFieldValue("code", $_POST["language"], "language");
				//$xmlAapi->setElementValue("selected","1",$lang[0]);
	
				//update configuration
				$xmlAapi->setElementValue("siteUrl", $_POST["siteUrl"], "configuration");
				$xmlAapi->setElementValue("callbackUrl", $_POST["callbackUrl"], "configuration");
				$xmlAapi->setElementValue("pluginUrl", $_POST["pluginUrl"], "configuration");
				
				//update idprovider data
				for($i=0;$i<count($_POST["idProviderId"]);$i++){
					$xmlAapi->updateElementById(
						array("active","clientId","clientSecret","scope"),
						array($_POST["active"][$i],$_POST["clientId"][$i],$_POST["clientSecret"][$i],$_POST["scope"][$i]),
						"identityProvider",
						$_POST["idProviderId"][$i]
					);
				}
				
		
			break;
		}
		
	break;
	
	default:
	break;
}

?>