<?php

	/*** Seleccion del lenguaje ***/
	
	$lang = $_POST["lang"];
	
	if ($_POST["lang"] == ""){
		$lang = getSystemLang();
	}
	
	$exists = file_exists("i18n/".$lang.".php");

	if ( !$exists ){
		$lang = getDefaultLang();
	}

	include("i18n/".$lang.".php");

	/*** Get XML Data ***/
	
	$xmlAapi = new xmlApi();
	$xmlAapi->setXmlFile("../config.xml");
	
	// Configuration
	$eslip_settings = $xmlAapi->getElementValue("configuration");
	$siteUrl = (string)$eslip_settings->siteUrl;
	$callbackUrl = (string)$eslip_settings->callbackUrl;
	$pluginUrl = (string)$eslip_settings->pluginUrl;
	
	// Identity Provider
	$identityProviders = $xmlAapi->getElementList("identityProvider");

?>

<script type="text/javascript">

	function getFormData($form){
		var unindexed_array = $form.serializeArray();
		var indexed_array = {};

		$.map(unindexed_array, function(n, i){
			indexed_array[n['name']] = n['value'];
		});

		return indexed_array;
	}


    $(function(){
		
		var serviceUrl = "<?php echo getServiceUrl(); ?>";
		var $wizard = $('#wizard');
		
		$wizard.jWizard({
			buttons: {
				next: {
					text: "<?php echo next; ?>",
					type: "button"
				},
				prev: {
					text: "<?php echo previous; ?>",
					type: "button"
				},
				cancel: {
					"class": "ui-priority-secondary",
					text: "<?php echo cancel; ?>",
					type: "button"
				},
				finish: {
					"class": "ui-priority-primary ui-state-highlight",
					text: "<?php echo finish; ?>",
					type: "button"
				
				}
			
			}
			
		});
		
		$wizard.on("wizardfinish",{},function(event){
			$defaultData = "&action=service&step_number=1"
			$formData = $("#setupForm").serialize();
			$.ajax(serviceUrl,{
				data: $formData+$defaultData,
				dataType: 'html',
				type: 'POST',
				async: true
			}).done(function(data){
				//$wizard.find(".jw-button-prev").click();
				//$wizard.find("#step-1").html(data);
			});
			return false;
		});
		
		//switch
		$(".cb-enable, .cb-disable").bind( "onoff-switch" , {}, function(event, params){
			var $idProvider = $(event.target).parents(".idProvider");
			if (params.value == "on"){
				$idProvider.find(".idProviderData").show();
			}else{
				$idProvider.find(".idProviderData").hide();
			}
			
		});
		
	});
</script>

	<form id="setupForm" action="" method="POST">
		
		<!-- PASO 0 - Seleccion del Lenguaje -->
		<input type="hidden" id="language" name="language" value="<?php echo $lang; ?>" />
			
		<!-- Wizard -->
  		<div id="wizard" class="wizard">	
			
			<!-- PASO 1 - Configuracion General -->
  			<div id="step-1" class="stepContainer" title="<?php echo ConfigGenerales; ?>">
			
				<h3 class="stepTitle"><?php echo ucwords(ConfigGenerales); ?></h3>
				<!--form id="configForm"-->
					<div class="contenedorCentral borderBox">
						<div class="reng">
							<label for="siteUrl"><?php echo SiteUrl; ?>:</label>
							<input type="text" id="siteUrl" name="siteUrl" value="<?php echo $siteUrl; ?>" />
						</div>
						<div class="reng">
							<label for="callbackUrl"><?php echo CallbackUrl; ?>:</label>
							<input type="text" id="callbackUrl" name="callbackUrl" value="<?php echo $callbackUrl; ?>" />
						</div>
						<div class="reng">
							<label for="pluginUrl"><?php echo PluginUrl; ?>:</label>
							<input type="text" id="pluginUrl" name="pluginUrl" value="<?php echo $pluginUrl; ?>" />
						</div>
					</div>
				<!--/form-->
			
			</div>
			
			<!-- PASO 2 - Redes Sociales -->
  			<div id="step-2" class="stepContainer" title="<?php echo IdProviders; ?>">
			
				<h3 class="stepTitle"><?php echo ucwords(IdProviders); ?></h3>
				<div class="contenedorCentral borderBox">
					<!--form id="idProvidersForm"-->
						<?php
						foreach( $identityProviders as $idProvider ){
							$activeVal = (String) $idProvider->active;
							$active = (bool) $activeVal;
							$disabled = $active ? "" : "selected";
							$enabled = $active ? "selected" : "";
							$checked = $active ? "checked" : "";
							$display = $active ?  "" : "display:none;";
						?>
							<div class="idProvider" id="<?php echo $idProvider->attributes()->id; ?>">
								<div class="reng">
							
									<div id="switch" class="field switch">
										<label class="cb-enable <?php echo $enabled; ?>"><span>On</span></label>
										<label class="cb-disable <?php echo $disabled; ?>"><span>Off</span></label>
										<input type="hidden" id="active" name="active[]" value="<?php echo $activeVal; ?>" />	
									</div>
									
									<span class="idProviderName"><?php echo ucwords($idProvider->name);?></span>
								</div>
								
								<div clasS="idProviderData" style="<?php echo $display; ?>">
									<input type="hidden" id="idProviderId" name="idProviderId[]" value="<?php echo $idProvider->attributes()->id; ?>" />
									<div class="reng">
										<label for="clientId"><?php echo clientId; ?>:</label>
										<input type="text" id="clientId" name="clientId[]" value="<?php echo $idProvider->clientId; ?>" />	
									</div>
									<div class="reng">
										<label for="clientSecret"><?php echo ClientSecret; ?>:</label>
										<input type="text" id="clientSecret" name="clientSecret[]" value="<?php echo $idProvider->clientSecret; ?>" />	
									</div>
									<div class="reng">
										<label for="scope"><?php echo Scope; ?>:</label>
										<input type="text" id="scope" name="scope[]" value="<?php echo $idProvider->scope; ?>" />
									</div>
								</div>
								
							</div>
							<div class="reng" style="height:15px;"></div>
						<?php } ?>
					<!--/form-->
				</div>
				
			</div>
			
  			<!-- PASO 3 - Login Form -->
  			<div id="step-3" class="stepContainer" title="<?php echo LoginForm; ?>">
			
				<h3 class="stepTitle"><?php echo ucwords(LoginForm); ?></h3>
				<div class="contenedorCentral borderBox">
					<p><?php echo LoginFormDesc; ?></p>
					<textarea style="width:500px; height:300px;"></textarea>
				</div>
				
			</div>
			
  		</div>
		<!-- End SmartWizard Content --> 
	</form> <!-- end setup form-->