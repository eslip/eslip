<?php
include_once('../eslip_api.php');

$section = $_REQUEST["section"];
$action = $_REQUEST["action"];

switch($section){               
	
	case "wizard":
		
		switch($action){
		
			case "content":
	
				// Configuration
				$eslip_settings = $xmlApi->getElementValue("configuration");
				$siteUrl = (string)$eslip_settings->siteUrl;
				$callbackUrl = (string)$eslip_settings->callbackUrl;
				$pluginUrl = (string)$eslip_settings->pluginUrl;
				$adminUser = (string)$eslip_settings->adminUser;
				$adminPass = (string)$eslip_settings->adminPass;
				
				// Identity Provider
				$identityProviders = $xmlApi->getElementList("identityProvider");
			
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
						
						var serviceUrl = "<?php echo getSetupServiceUrl(); ?>";
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
							},
							progress: {
								label: "count",/*percentage*/
								append: ""
							},
							
						});
						
						//wizard resize
						
						$(window).resize(function() {
							updateWizardHeight();
						});
						
						var updateWizardHeight = function(){
							var height = $(window).outerHeight() - ($(".jw-header").outerHeight() + $(".jw-footer").outerHeight() + 55);
							console.info(height +" = "+ $(window).outerHeight() + " - " + " ( "+$(".jw-header").outerHeight()+" + "+$(".jw-footer").outerHeight()+" + 55 )");
							$wizard.find(".jw-steps-wrap").height(height);
						};
						updateWizardHeight();
						
						//wizard finish
						$wizard.on("wizardfinish",{},function(event){
							$defaultData = "&section=wizard&action=save"
							//$formData = $("#setupForm").serialize();
							var $formData = "";
							$.each($wizard.find("form"),function(i,form){
									$formData += $(form).serialize()+"&";
							});
							
							$.ajax(serviceUrl,{
								data: $formData+$defaultData,
								dataType: 'html',
								type: 'POST',
								async: true
							}).done(function(data){
								$wizard.hide();
								$("#dialog-wizard-end").dialog({
									resizable: false,
									height:350,
									width:700,
									modal: true,
									buttons: {
										"OK": function() {
											$( this ).dialog( "close" );
											window.location.href = "admin.php";
										}
									}
								});
							});
							return false;
						});
						
						//wizard cancel
						$wizard.on("wizardcancel",{},function(event){
							$wizard.hide();
						});
						
						/*** Forms Validation ***/
						
						$wizard.find("#form1").validate({
							// Specify the validation rules
							rules: {
								adminUser: "required",
								adminPass: {
									required: true,
									minlength: 6
								},
								adminPassConfirm: {
									equalTo: "#adminPass"
								}
							}
						});
						
						$wizard.find("#form2").validate({
							// Specify the validation rules
							rules: {
								siteUrl: "required",
								callbackUrl: "required",
								pluginUrl: "required"
							}
						});
						
						$wizard.find(".stepContainer").on("stephide",{},function(event){
							var $container = $(event.target).find(".contenedorCentral");
							if ($container.find("form").valid()){
								return true;
							}else{
								$wizard.find(".jw-buttons").find("button").removeClass("ui-state-disabled");
								return false;
							}
							
						});
						
						/*** Ends Forms Validation ***/
						
						//switch
						$(".switch").switchOnOff();
						
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

				<!--form id="setupForm" action="" method="POST"-->
					
					<!-- STEP 0 - Language Selection -->
					<input type="hidden" id="language" name="language" value="<?php echo $selectedLang; ?>" />
						
					<!-- Wizard -->
					<div id="wizard" class="wizard">
					
						<!-- STEP 1 - Create User Admin -->
						<div id="step-1" class="stepContainer" title="<?php echo CreateAdminUser; ?>">
						
							<h3 class="stepTitle"><?php echo ucwords(CreateAdminUser); ?></h3>
							<div class="contenedorCentral borderBox">
							<form id="form1" action="" method="POST">
								<div class="reng">
									<label for="adminUser"><?php echo AdminUser; ?>:</label>
									<input type="text" id="adminUser" name="adminUser" value="<?php echo $adminUser; ?>" />
								</div>
								<div class="reng">
									<label for="adminPass"><?php echo AdminPass; ?>:</label>
									<input type="password" id="adminPass" name="adminPass" value="" />
								</div>
								<div class="reng">
									<label for="adminPassConfirm"><?php echo AdminPassConfirm; ?>:</label>
									<input type="password" id="adminPassConfirm" name="adminPassConfirm" value="" />
								</div>
								
							</div>
							</form>
						</div>
						
						<!-- STEP 2 - General Settings -->
						<div id="step-2" class="stepContainer" title="<?php echo GeneralConfigs; ?>">
						
							<h3 class="stepTitle"><?php echo ucwords(GeneralConfigs); ?></h3>
							<div class="contenedorCentral borderBox">
							<form id="form2" action="" method="POST">
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
							</form>
							</div>
						</div>
						
						<!-- STEP 3 - Identity Providers -->
						<div id="step-3" class="stepContainer" title="<?php echo IdProviders; ?>">
						
							<h3 class="stepTitle"><?php echo ucwords(IdProviders); ?></h3>
							<div class="contenedorCentral borderBox">
							<form id="form3" action="" method="POST">
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
												<label for="clientId"><?php echo ClientId; ?>:</label>
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
							</div>
						</form>	
						</div>
						
					</div><!-- End Wizard Content --> 
					
				<!--/form--> <!-- end setup form-->
				
				<!-- END WIZARD - Login Form -->
				<div id="dialog-wizard-end" title="<?php echo WizardEndTitle; ?>" style="display:none;">
					<h3><?php echo WizardEndSubTitle; ?></h3>
					<div class="contenedorCentral borderBox">
						<p><?php echo LoginFormDesc; ?></p>
						<!-- HTML generated using hilite.me -->
						<div style="background: #ffffff; overflow:auto;width:auto;border:solid gray;border-width:.1em .1em .1em .8em;padding:.2em .6em; line-height: 16px;">
							<span style="color: #007700">&lt;html&gt;</span><br/>
							<span style="margin-left: 20px; color: #007700">&lt;head&gt;</span><br/>
							<span style="margin-left: 40px; color: #007700">&lt;link</span> <span style="color: #0000CC">rel=</span><span style="background-color: #fff0f0">&quot;stylesheet&quot;</span> <span style="color: #0000CC">type=</span><span style="background-color: #fff0f0">&quot;text/css&quot;</span> <span style="color: #0000CC">href=</span><span style="background-color: #fff0f0">&quot;<?php echo $pluginUrl."frontend/eslip_plugin.css"; ?>&quot;</span> <span style="color: #007700">/&gt;</span><br/>
							<span style="margin-left: 40px; color: #007700">&lt;script </span><span style="color: #0000CC">type=</span><span style="background-color: #fff0f0">&quot;text/javascript&quot;</span> <span style="color: #0000CC">src=</span><span style="background-color: #fff0f0">&quot;<?php echo $pluginUrl."frontend/eslip_plugin.js"; ?>&quot;</span><span style="color: #007700">&gt;&lt;/script&gt;</span><br/>
							<span style="margin-left: 20px; color: #007700">&lt;/head&gt;</span><br/>
							<span style="margin-left: 20px; color: #007700">&lt;body&gt;</span><br/>
							<span style="margin-left: 40px; color: #007700">&lt;div</span> <span style="color: #0000CC">id=</span><span style="background-color: #fff0f0">&quot;ESLIP_Plugin&quot;</span><span style="color: #007700">&gt;&lt;/div&gt;</span><br/>
							<span style="margin-left: 20px; color: #007700">&lt;/body&gt;</span><br/>
							<span style="color: #007700">&lt;/html&gt;</span><br/>
						</div>
					</div>
				</div>
				
			<?php
			
			
			break;
			
			case "save":
			
				//update first time config
				$xmlApi->setElementValue("runWizard", "0", "configuration");
				
				//update language
				$xmlApi->updateElement(array("selected"), array("0"), "language");
				$xmlApi->setElementListByFieldValue("code", $_POST["language"], "language",null,"selected","1");
	
				//update admin user
				$xmlApi->setElementValue("adminUser", $_POST["adminUser"], "configuration");
				$xmlApi->setElementValue("adminPass", getEncrypted($_POST["adminPass"]), "configuration");
				
				//update configuration
				$xmlApi->setElementValue("siteUrl", $_POST["siteUrl"], "configuration");
				$xmlApi->setElementValue("callbackUrl", $_POST["callbackUrl"], "configuration");
				$xmlApi->setElementValue("pluginUrl", $_POST["pluginUrl"], "configuration");
				
				
				
				//update idprovider data
				for($i=0;$i<count($_POST["idProviderId"]);$i++){
					$xmlApi->updateElementById(
						array("active","clientId","clientSecret","scope"),
						array($_POST["active"][$i],$_POST["clientId"][$i],$_POST["clientSecret"][$i],$_POST["scope"][$i]),
						"identityProvider",
						$_POST["idProviderId"][$i]
					);
				}
				
		
			break;
		}
		
	break;
	/* END Wizard */
}

?>