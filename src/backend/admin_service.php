<?php
include_once('../eslip_api.php');
session_start();

$section = $_REQUEST["section"];
$action = $_REQUEST["action"];

switch($section)
{

	case "admin":
	
		switch($action){
		
			case "configUser":
			
				// Configuration
				$eslip_settings = $xmlApi->getElementValue("configuration");
				$adminUser = (string)$eslip_settings->adminUser;
			?>
				<script>
				$(function() {
					$( "input[type=button], button" ).button();
					
					function doRequest($data){
						$.ajax($serviceUrl,{
							data: $data,
							dataType: 'html',
							type: 'POST',
							async: true
						}).done(function(data){
							$(".success").show();
						}).fail(function( jqXHR, textStatus, errorThrown ) {
							$(".error").show();
						});
					}
					
					/*** Forms Validation ***/
						
					$("#form").validate({
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
					
					$('#save').click( function() {
						if ($("#form").valid()){
							$data = "section=admin&action=saveConfigUser&"+$("#form").serialize();
							doRequest($data);
						}
					});
					
				});
				</script>
			
				<div class="block">
					<h3 class="stepTitle"><?php echo ConfigUser; ?></h3>
					<br/>
					<form id="form" action="" method="POST">
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
					</form>
					<div class="adminToolBar" style="float: right; margin-top: 10px;">
						<input type="button" id="save" value="<?php echo btnSave; ?>" />
						<input type="button" id="cancel" value="<?php echo btnCancel; ?>" />		
					</div>
					<div class="success" style="display:none;"><?php echo messageSuccess; ?></div>
					<div class="error" style="display:none;"><?php echo messageError; ?></div>
				</div>
			
			<?php
			break;
			
			case "saveConfigUser":
			
				//update admin user
				$xmlApi->setElementValue("adminUser", $_POST["adminUser"], "configuration");
				$xmlApi->setElementValue("adminPass", getEncrypted($_POST["adminPass"]), "configuration");
			
			break;
			
			case "generalConfig":
			
				// Configuration
				$eslip_settings = $xmlApi->getElementValue("configuration");
				$siteUrl = (string)$eslip_settings->siteUrl;
				$callbackUrl = (string)$eslip_settings->callbackUrl;
				$pluginUrl = (string)$eslip_settings->pluginUrl;
				$eslip_langs = $xmlApi->getElementList("language");
			?>
				<script>
				$(function() {
					$( "input[type=button], button" ).button();
					
					function doRequest($data){
						$.ajax($serviceUrl,{
							data: $data,
							dataType: 'html',
							type: 'POST',
							async: true
						}).done(function(data){
							$(".success").show();
						}).fail(function( jqXHR, textStatus, errorThrown ) {
							$(".error").show();
						});
					}
					
					/*** Forms Validation ***/
						
					$("#form").validate({
						// Specify the validation rules
						rules: {
							siteUrl: "required",
							callbackUrl: "required",
							pluginUrl: "required"
						}
					});
					
					$('#save').click( function() {
						if ($("#form").valid()){
							$data = "section=admin&action=saveGeneralConfig&"+$("#form").serialize();
							doRequest($data);
						}
					});
					
					$('#cancel').click( function() {
						$("#generalConfig").click();
					});
					
				});
				</script>
				
				<div class="block">
					<h3 class="stepTitle"><?php echo GeneralConfigs; ?></h3>
					<br/>
					<form id="form" action="" method="POST">
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
						<div class="reng">
							<label for="language"><?php echo SelectLangTitle; ?>:</label>
							<select id="language" name="language">
								<?php
									foreach( $eslip_langs as $lang ){
								?>
										<option value="<?php echo $lang->code;?>" <?php if ($lang->code == $selectedLang) echo "selected";?>><?php echo $lang->name;?></option>
								<?php
									}
								?>
							</select>
						</div>
					</form>
					<div class="adminToolBar" style="float: right; margin-top: 10px;">
						<input type="button" id="save" value="<?php echo btnSave; ?>" />
						<input type="button" id="cancel" value="<?php echo btnCancel; ?>" />		
					</div>
					<div class="success" style="display:none;"><?php echo messageSuccess; ?></div>
					<div class="error" style="display:none;"><?php echo messageError; ?></div>
				</div>
			<?php
			break;
			
			case "saveGeneralConfig":
			
				//update language
				$xmlApi->updateElement(array("selected"), array("0"), "language");
				$xmlApi->setElementListByFieldValue("code", $_POST["language"], "language",null,"selected","1");

				//update configuration
				$xmlApi->setElementValue("siteUrl", $_POST["siteUrl"], "configuration");
				$xmlApi->setElementValue("callbackUrl", $_POST["callbackUrl"], "configuration");
				$xmlApi->setElementValue("pluginUrl", $_POST["pluginUrl"], "configuration");
			
			break;
			
			case "idProviders":
			?>
				<script>
					var $serviceUrl = "<?php echo getAdminServiceUrl(); ?>";
					$(function() {
						
						var oTable;
						
						/* Add a click handler to the rows - this could be used as a callback */
						$("#idProviderTable tbody").click(function(event) {
							$(oTable.fnSettings().aoData).each(function (){
								$(this.nTr).removeClass('row_selected');
							});
							$(event.target.parentNode).addClass('row_selected');
						});
					
					
						var oTableSettings = {
							"bJQueryUI": true,
							"sPaginationType": "full_numbers",
							"bRetrieve":true,
							"sAjaxSource": $serviceUrl,
							//"bServerSide": true,
							"bProcessing": true,
							"bFilter": false,
							"fnServerParams": function ( aoData ) {
								aoData.push( { "name": "section", "value": "admin" } );
								aoData.push( { "name": "action", "value": "getIdProvidersJSON" } );
							},
							"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
								
								/*Add row id*/
								$(nRow).attr("id",aData[0]);
								
								/* Add a click handler to the rows - this could be used as a callback */
								/*$(nRow).click( function( e ) {
									if ( $(this).hasClass('row_selected') ) {
										$(this).removeClass('row_selected');
									}
									else {
										oTable.$('tr.row_selected').removeClass('row_selected');
										$(this).addClass('row_selected'); 
									}
								});*/
							},
							"oLanguage": {
									"sProcessing": "<?php echo Procesando; ?>",
									"sLoadingRecords": "<?php echo dtsLoadingRecords; ?>",
									"sLengthMenu": "<?php echo dtsLengthMenu; ?>",
									"sZeroRecords": "<?php echo dtsZeroRecords; ?>",
									"sInfo": "<?php echo dtsInfo; ?>",
									"sInfoEmpty": "<?php echo dtsInfoEmpty; ?>",
									"sInfoFiltered": "<?php echo dtsInfoFiltered; ?>",
									"sSearch": "<?php echo dtsSearch; ?>",
									"oPaginate": {
											"sFirst": "<?php echo dtsFirst; ?>",
											"sPrevious": "<?php echo dtsPrevious; ?>",
											"sNext": "<?php echo dtsNext; ?>",
											"sLast": "<?php echo dtsLast; ?>"
									}
							}
						};
							
						oTable = $('#idProviderTable').dataTable(oTableSettings);
					
						function doRequest($data, openDialog){
							$.ajax($serviceUrl,{
								data: $data,
								dataType: 'html',
								type: 'POST',
								async: true
							}).done(function(data){
									if (openDialog){
										$("#dialog-edit").html(data);
										$("#dialog-edit" ).dialog( "open" );
									}else{
										oTable.fnDestroy();
										oTable = $('#idProviderTable').dataTable(oTableSettings);
									}
							});
						}
						
						/* Add a click handler for new row */
						$('#new').click( function() {
							$("#dialog-edit" ).dialog( "option", "title", "<?php echo btnNew; ?>" );
							doRequest({section: "admin", action: "getForm", id: ""}, true);
						} );
						
						/* Add a click handler for edit row */
						$('#edit').click( function() {
							var anSelected = fnGetSelected( oTable );
							if ( anSelected.length !== 0 ) {
								$("#dialog-edit" ).dialog( "option", "title", "<?php echo btnEdit; ?>" );
								doRequest({section: "admin", action: "getForm", id: $(anSelected).attr("id")}, true);
							}
						} );
						
						/* Add a click handler for delete row */
						$('#delete').click( function() {
							var anSelected = fnGetSelected( oTable );
							if ( anSelected.length !== 0 ) {
								$("#dialog-confirm" ).dialog( "option", "title", "<?php echo btnDelete; ?>" );
								$("#dialog-confirm" ).dialog( "open" );
							}
						} );
						
						$( "input[type=button], button" ).button();
						
						$( "#dialog-edit" ).dialog({
							autoOpen: false,
							resizable: false,
							//height: 300,
							width: 550,
							modal: true,
							buttons: {
								"<?php echo btnSave; ?>": function() {
									$defaultData = "&section=admin&action=save";
									$formData = $( this ).find("form").serialize();
									doRequest($formData+$defaultData, false);
									$( this ).dialog( "close" );
								},
								"<?php echo btnCancel; ?>": function() {
									$( this ).dialog( "close" );
								}
							}
						});
						
						 $( "#dialog-confirm" ).dialog({
							autoOpen: false,
							resizable: false,
							height:140,
							modal: true,
							buttons: {
								"<?php echo btnConfirm; ?>": function() {
									var anSelected = fnGetSelected( oTable );
									doRequest({section: "admin", action: "delete", id: $(anSelected).attr("id")}, false);
									$( this ).dialog( "close" );
								},
								"<?php echo btnCancel; ?>": function() {
									$( this ).dialog( "close" );
								}
							}
						});
						
					});
					
					/* Get the rows which are currently selected */
					function fnGetSelected( oTableLocal ){
						return oTableLocal.$('tr.row_selected');
					}
					
				</script>
				
				<h3 class="stepTitle"><?php echo IdProviders; ?></h3>
				
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="idProviderTable">
					<thead>
						<tr>
							<th width="20%"><?php echo Id; ?></th>
							<th width="20%"><?php echo Name; ?></th>
							<th width="25%"><?php echo Oauth; ?></th>
							<th width="25%"><?php echo Active; ?></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
				
				<div class="adminToolBar" style="float: right; margin-top: 10px;">
					<input type="button" id="new" value="<?php echo btnNew; ?>" />
					<input type="button" id="edit" value="<?php echo btnEdit; ?>" />
					<input type="button" id="delete" value="<?php echo btnDelete; ?>" />
				</div>
			
			<?php
			break;
		
			case "getIdProvidersJSON":
				
				$data = array();
				$row = array();
				$totalRecords = 0;
				// Identity Provider
				$identityProviders = $xmlApi->getElementList("identityProvider");
				foreach( $identityProviders as $idProvider ){
					
					$row = array((String)$idProvider->attributes()->id, (String)$idProvider->name, (String)$idProvider->oauth, (String)$idProvider->active);
					array_push($data,$row);
					$totalRecords++;
				}
				
				$arr = array("sEcho" => 1, "iTotalRecords" => $totalRecords, "iTotalDisplayRecords" => $totalRecords, "aaData" => $data);

				echo json_encode($arr);
			
			break;
			
			case "getForm":
				
				$new = true;
				$method = "new";
				$id = "";
				if ($_POST["id"] != ""){
					$id = $_POST["id"];
					$new = false;
					$method = "update";
				}
			
				$idProvider = new stdClass();
				if (!empty($id)){
					$idProvider = $xmlApi->getElementById("identityProvider",$id);
					
				}
				
			?>
				<form id="form" action="" method="POST">
					<input type="hidden" id="method" name="method" value="<?php echo $method; ?>" />
					<?php if($new){ ?>
					<div class="reng">
						<script>
							$(function() {
								$( "#form" ).find("#id").autocomplete({
									source: availableTags
								});
							});
						</script>
						<label for="id"><?php echo Id; ?>:</label>
						<input type="text" id="id" name="id" value="<?php echo $id; ?>" />
					</div>
					<?php }else{ ?>
						<input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />	
					<?php } ?>
					<div class="reng">
						<label for="name"><?php echo Name; ?>:</label>
						<input type="text" id="name" name="name" value="<?php echo safeValue($idProvider,"name"); ?>" />	
					</div>
					<div class="reng">
						<label for="label"><?php echo Label; ?>:</label>
						<input type="text" id="label" name="label" value="<?php echo safeValue($idProvider,"label"); ?>" />	
					</div>
					<div class="reng">
						<label for="active"><?php echo Active; ?>:</label>
						<!--input type="text" id="active" name="active" value="<?php //echo $idProvider->active; ?>" /-->	
						<!--input type="checkbox" id="active" name="active" value="1" <?php //if ($idProvider->active){ echo "checked"; } ?>-->
						<select id="active" name="active">
							<option value="0" <?php if (safeValue($idProvider,"active") == 0){ echo "selected"; }?>><?php echo No; ?></option>
							<option value="1" <?php if (safeValue($idProvider,"active") == 1){ echo "selected"; }?>><?php echo Yes; ?></option>
						</select>
					</div>
					<?php if($id != "openid"){ ?>
					<div class="reng">
						<label for="oauth"><?php echo Oauth; ?>:</label>
						<!--input type="text" id="oauth" name="oauth" value="<?php //echo $idProvider->oauth; ?>" /-->
						<?php $oauthVersion =  safeValue($idProvider,"oauth"); ?>
						<select id="oauth" name="oauth">
							<option value="1.0" <?php if ($oauthVersion == "1.0"){ echo "selected"; }?>>1.0</option>
							<option value="1.0a" <?php if ($oauthVersion == "1.0a"){ echo "selected"; }?>>1.0a</option>
							<option value="2.0" <?php if ($oauthVersion == "2.0"){ echo "selected"; }?>>2.0</option>
						</select>
					</div>
					<div class="reng">
						<label for="requestTokenUrl"><?php echo RequestTokenUrl; ?>:</label>
						<input type="text" id="requestTokenUrl" name="requestTokenUrl" value="<?php echo safeValue($idProvider,"requestTokenUrl"); ?>" />	
					</div>
					<div class="reng">
						<label for="dialogUrl"><?php echo DialogUrl; ?>:</label>
						<input type="text" id="dialogUrl" name="dialogUrl" value="<?php echo safeValue($idProvider,"dialogUrl"); ?>" />	
					</div>
					<div class="reng">
						<label for="accessTokenUrl"><?php echo AccessTokenUrl; ?>:</label>
						<input type="text" id="accessTokenUrl" name="accessTokenUrl" value="<?php echo safeValue($idProvider,"accessTokenUrl"); ?>" />	
					</div>
					<div class="reng">
						<label for="authorizationHeader"><?php echo AuthorizationHeader; ?>:</label>
						<!--input type="text" id="authorizationHeader" name="authorizationHeader" value="<?php //echo $idProvider->authorizationHeader; ?>" /-->
						<select id="authorizationHeader" name="authorizationHeader">
							<option value="0" <?php if (safeValue($idProvider,"authorizationHeader") == 0){ echo "selected"; }?>><?php echo No; ?></option>
							<option value="1" <?php if (safeValue($idProvider,"authorizationHeader") == 1){ echo "selected"; }?>><?php echo Yes; ?></option>
						</select>
					</div>
					<div class="reng">
						<label for="urlParameters"><?php echo UrlParameters; ?>:</label>
						<input type="text" id="urlParameters" name="urlParameters" value="<?php echo safeValue($idProvider,"urlParameters"); ?>" />	
					</div>
					<?php } ?>
					<div class="reng">
						<label for="redirectUri"><?php echo RedirectUri; ?>:</label>
						<input type="text" id="redirectUri" name="redirectUri" value="<?php echo safeValue($idProvider,"redirectUri"); ?>" />	
					</div>
					<?php if($id != "openid"){ ?>
					<div class="reng">
						<label for="clientId"><?php echo ClientId; ?>:</label>
						<input type="text" id="clientId" name="clientId" value="<?php echo safeValue($idProvider,"clientId"); ?>" />	
					</div>
					<div class="reng">
						<label for="clientSecret"><?php echo ClientSecret; ?>:</label>
						<input type="text" id="clientSecret" name="clientSecret" value="<?php echo safeValue($idProvider,"clientSecret"); ?>" />	
					</div>
					<div class="reng">
						<label for="scope"><?php echo Scope; ?>:</label>
						<input type="text" id="scope" name="scope" value="<?php echo safeValue($idProvider,"scope"); ?>" />
					</div>
					<div class="reng">
						<label for="hasAccessTokenExtraParameter"><?php echo HasAccessTokenExtraParameter; ?>:</label>
						<!--input type="text" id="hasAccessTokenExtraParameter" name="hasAccessTokenExtraParameter" value="<?php //echo $idProvider->hasAccessTokenExtraParameter; ?>" /-->
						<select id="hasAccessTokenExtraParameter" name="hasAccessTokenExtraParameter">
							<option value="0" <?php if (safeValue($idProvider,"hasAccessTokenExtraParameter") == 0){ echo "selected"; }?>><?php echo No; ?></option>
							<option value="1" <?php if (safeValue($idProvider,"hasAccessTokenExtraParameter") == 1){ echo "selected"; }?>><?php echo Yes; ?></option>
						</select>
					</div>
					<div class="reng">
						<label for="accessTokenExtraParameterName"><?php echo AccessTokenExtraParameterName; ?>:</label>
						<input type="text" id="accessTokenExtraParameterName" name="accessTokenExtraParameterName" value="<?php echo safeValue($idProvider,"accessTokenExtraParameterName"); ?>" />
					</div>
					<div class="reng">
						<label for="userDataUrl"><?php echo UserDataUrl; ?>:</label>
						<input type="text" id="userDataUrl" name="userDataUrl" value="<?php echo safeValue($idProvider,"userDataUrl"); ?>" />
					</div>
					<div class="reng">
						<label for="userDataNameKey"><?php echo UserDataNameKey; ?>:</label>
						<input type="text" id="userDataNameKey" name="userDataNameKey" value="<?php echo safeValue($idProvider,"userDataNameKey"); ?>" />
					</div>
					<?php } ?>
					<?php if($id == "openid"){ ?>
					<div class="reng">
						<label for="formUrl"><?php echo FormUrl; ?>:</label>
						<input type="text" id="formUrl" name="formUrl" value="<?php echo safeValue($idProvider,"formUrl"); ?>" />
					</div>
					<div class="reng">
						<label for="scopeRequired"><?php echo ScopeRequired; ?>:</label>
						<input type="text" id="scopeRequired" name="scopeRequired" value="<?php echo safeValue($idProvider,"scopeRequired"); ?>" />
					</div>
					<div class="reng">
						<label for="scopeOptional"><?php echo ScopeOptional; ?>:</label>
						<input type="text" id="scopeOptional" name="scopeOptional" value="<?php echo safeValue($idProvider,"scopeOptional"); ?>" />
					</div>
					<div class="reng">
						<label for="userDataIdKey"><?php echo UserDataIdKey; ?>:</label>
						<input type="text" id="userDataIdKey" name="userDataIdKey" value="<?php echo safeValue($idProvider,"userDataIdKey"); ?>" />
					</div>
					<?php } ?>
				</form>
			
			<?php
			break;
		
			case "save":
				
				//crear uno nuevo si no existe
				if ($_POST["method"] == "new"){
					
					if($_POST["id"] != "openid"){

						$xmlApi->addElement(
						
							$_POST["id"],
							
							array(
								"name",
								"label",
								"active",
								"oauth",
								"requestTokenUrl",
								"dialogUrl",
								"accessTokenUrl",
								"authorizationHeader",
								"urlParameters",
								"redirectUri",
								"clientId",
								"clientSecret",
								"scope",
								"hasAccessTokenExtraParameter",
								"accessTokenExtraParameterName",
								"userDataUrl",
								"userDataNameKey"
							),
							
							array(
								$_POST["name"],
								$_POST["label"],
								$_POST["active"],
								$_POST["oauth"],
								$_POST["requestTokenUrl"],
								$_POST["dialogUrl"],
								$_POST["accessTokenUrl"],
								$_POST["authorizationHeader"],
								$_POST["urlParameters"],
								$_POST["redirectUri"],
								$_POST["clientId"],
								$_POST["clientSecret"],
								$_POST["scope"],
								$_POST["hasAccessTokenExtraParameter"],
								$_POST["accessTokenExtraParameterName"],
								$_POST["userDataUrl"],
								$_POST["userDataNameKey"]
							),
							
							"identityProvider",
							
							"identityProviders"
						);
					}
				}else{
					//update idprovider data
					
					if($_POST["id"] == "openid"){
					
						$xmlApi->updateElementById(
							
							array(
								"name",
								"label",
								"active",
								"redirectUri",
								"formUrl",
								"scopeRequired",
								"scopeOptional",
								"userDataIdKey"
							),
							
							array(
								$_POST["name"],
								$_POST["label"],
								$_POST["active"],
								$_POST["redirectUri"],
								$_POST["formUrl"],
								$_POST["scopeRequired"],
								$_POST["scopeOptional"],
								$_POST["userDataIdKey"]
							),
							
							"identityProvider",
							
							$_POST["id"]
						);
					
					}else{
					
						$xmlApi->updateElementById(
							
							array(
								"name",
								"label",
								"active",
								"oauth",
								"requestTokenUrl",
								"dialogUrl",
								"accessTokenUrl",
								"authorizationHeader",
								"urlParameters",
								"redirectUri",
								"clientId",
								"clientSecret",
								"scope",
								"hasAccessTokenExtraParameter",
								"accessTokenExtraParameterName",
								"userDataUrl",
								"userDataNameKey"
							),
							
							array(
								$_POST["name"],
								$_POST["label"],
								$_POST["active"],
								$_POST["oauth"],
								$_POST["requestTokenUrl"],
								$_POST["dialogUrl"],
								$_POST["accessTokenUrl"],
								$_POST["authorizationHeader"],
								$_POST["urlParameters"],
								$_POST["redirectUri"],
								$_POST["clientId"],
								$_POST["clientSecret"],
								$_POST["scope"],
								$_POST["hasAccessTokenExtraParameter"],
								$_POST["accessTokenExtraParameterName"],
								$_POST["userDataUrl"],
								$_POST["userDataNameKey"]
							),
							
							"identityProvider",
							
							$_POST["id"]
						);
					}
				}

			break;
			
			case "delete":
				
				$xmlApi->removeElementById("identityProvider", $_POST["id"]);
			
			break;
			
			case "login":
			
				$eslip_settings = $xmlApi->getElementValue("configuration");
				$adminUser = (string)$eslip_settings->adminUser;
				$adminPass = (string)$eslip_settings->adminPass;
				
				//echo $adminPass." = (".$_POST["adminPass"].") ".getEncrypted($_POST["adminPass"])."<br>";
				if( ($adminUser == $_POST["adminUser"]) && ($adminPass == getEncrypted($_POST["adminPass"])) ){
					$_SESSION["usuario"] = $adminUser;
					echo "OK";
				}else{
					echo "ERROR";
				}
			
			break;
			
			case "logout":
			
				//$_SESSION["usuario"] = "";
				session_destroy();
				echo "OK";
				
			break;
		}
	
	break;
	/* END Admin */
	
	default:
	break;
}

?>