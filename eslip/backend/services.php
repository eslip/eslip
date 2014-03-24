<?php
	
	include_once("../eslip_api.php");
	session_start();

	class ServiceApi {
		
		private $xmlApi = null;

		public function __construct($eslip){
			$this->xmlApi = $eslip;
		}
		
		/*
		 * Función pública para acceos de la API de Servicios.
		 * Esta función llama dinámicamente a la función de la API de Servicios correspondiente dependiendo del valor del parámetro request
		 *
		 */
		public function callService(){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			//else
				//$this->response('',404);				// If the method not exist with in this class, response would be "Page not found".
		}
		
		/*
		 *	Convertir arreglo en un objeto JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}

		private function response($data){
			//$this->_code = ($status)?$status:200;
			//$this->set_headers();
			echo $this->json($data);
			exit;
		}

		/******************************************************
		* Admin Functions
		*******************************************************/

		private function login(){
			$eslipSettings = $this->xmlApi->getElementValue("configuration");
			$adminUser = (string)$eslipSettings->adminUser;
			$adminPass = (string)$eslipSettings->adminPass;
			
			$result = "ERROR";
			if( ($adminUser == $_POST["adminUser"]) && ($adminPass == getEncrypted($_POST["adminPass"])) ){
				$_SESSION["usuario"] = $adminUser;
				 $result = "SUCCESS";
			}

			$data = array(
				"status" => $result
			);

			$this->response($data);
		}

		private function logout(){
			session_destroy();

			$result = "SUCCESS";
			$data = array(
				"status" => $result
			);
			$this->response($data);
		}

		private function isAuthenticated(){
			$isAuthenticated = ( isset($_SESSION['usuario']) && ! empty($_SESSION['usuario']) );
			$data = array(
				"isAuthenticated" => $isAuthenticated
			);
			$this->response($data);	
		}

		private function getGeneralConfigData(){

			$labels = array(
				"generalConfigs" => GeneralConfigs,
				"siteUrl" => SiteUrl,
				"callbackUrl" => CallbackUrl,
				"pluginUrl" => PluginUrl,
				"selectLangTitle" => SelectLangTitle,
				"btnSave" => btnSave,
				"btnCancel" => btnCancel,
				"messageSuccess" => messageSuccess,
				"messageError" => messageError
			);

			$eslipSettings = $this->xmlApi->getElementValue("configuration");
			$settings = array(
				"siteUrl" => (string)$eslipSettings->siteUrl,
				"callbackUrl" => (string)$eslipSettings->callbackUrl,
				"pluginUrl" => (string)$eslipSettings->pluginUrl,
				"selectedLang" => $this->xmlApi->selected_language
			);

			$languageOptions = array();
			$languages = $this->xmlApi->getElementList("language");
 			foreach( $languages as $lang ){
 				$langOption = array(
 					"value" => (string)$lang->code,
 					"content" => (string)$lang->name
 				);
 				array_push($languageOptions, $langOption);
 			}

			//options: [{ value: 3, content: 'test3' }, { value: 4, content: 'test4' }, { value: 5, content: 'test5' }, { value: 6, content: 'test6'}],

			$data = array(
				"labels" => $labels,
				"settings" => $settings,
				"languageOptions" => $languageOptions,
			);

			$this->response($data);
		}

		private function saveGeneralConfig(){

			//update language
			$this->xmlApi->updateElement(array("selected"), array("0"), "language");
			$this->xmlApi->setElementListByFieldValue("code", $_POST["language"], "language",null,"selected","1");

			//update configuration
			$this->xmlApi->setElementValue("siteUrl", $_POST["siteUrl"], "configuration");
			$this->xmlApi->setElementValue("callbackUrl", $_POST["callbackUrl"], "configuration");
			$this->xmlApi->setElementValue("pluginUrl", $_POST["pluginUrl"], "configuration");

			$result = "SUCCESS";

			$data = array(
				"status" => $result
			);

			$this->response($data);
		}

		private function getUserConfigData(){

			$labels = array(
				"configUser" => ConfigUser,
				"adminUser" => AdminUser,
				"adminPass" => AdminPass,
				"adminPassConfirm" => AdminPassConfirm,
				"btnSave" => btnSave,
				"btnCancel" => btnCancel,
				"messageSuccess" => messageSuccess,
				"messageError" => messageError
			);

			$eslipSettings = $this->xmlApi->getElementValue("configuration");
			$settings = array(
				"adminUser" => (string)$eslipSettings->adminUser,
			);

			$data = array(
				"labels" => $labels,
				"settings" => $settings
			);

			$this->response($data);
		}

		private function saveUserConfig(){

			//update admin user
			$this->xmlApi->setElementValue("adminUser", $_POST["adminUser"], "configuration");
			$this->xmlApi->setElementValue("adminPass", getEncrypted($_POST["adminPass"]), "configuration");

			$result = "SUCCESS";

			$data = array(
				"status" => $result
			);

			$this->response($data);
		}

		private function getIdProviders(){

			// Identity Provider
			$identityProviders = $this->xmlApi->getElementList("identityProvider");
			$openIdProvider = array();
			$idProviders = array();
			foreach( $identityProviders as $idProvider ){
				$idProvider->id = $idProvider->attributes()->id;
				
				$idProviderFixed = array();
				foreach ($idProvider as $key => $value){
					if ( is_object($value) ){
						$idProviderFixed[$key] = (string)$value;
					}
				}

				if ($idProviderFixed["id"] == "openid"){
					$openIdProvider = $idProviderFixed;
				}else{
					array_push($idProviders, $idProviderFixed);
				}

			}

			$data = array(
				"idProviders" => $idProviders,
				"openIdProvider" => $openIdProvider
			);

			return $data;
		}

		private function getIdProvidersData(){

			$labels = array(
				"idProviders" => IdProviders,
				"id" => Id,
				"name" => Name,
				"oauth" => Oauth,
				"active" => Active,
				"clientId" => ClientId,
				"clientSecret" => ClientSecret,
				"userDataIdKey" => UserDataIdKey,
				"scopeRequired" => ScopeRequired,
				"scopeOptional" => ScopeOptional,
				"scope" => Scope,
				"btnNew" => btnNew,
				"btnEdit" => btnEdit,
				"btnDelete" => btnDelete,
				"btnSave" => btnSave,
				"btnCancel" => btnCancel,
				"btnConfirm" => btnConfirm,
				"processing" => Procesando,
				"dtsLoadingRecords" => dtsLoadingRecords,
				"dtsLengthMenu" => dtsLengthMenu,
				"dtsZeroRecords" => dtsZeroRecords,
				"dtsInfo" => dtsInfo,
				"dtsInfoEmpty" => dtsInfoEmpty,
				"dtsInfoFiltered" => dtsInfoFiltered,
				"dtsSearch" => dtsSearch,
				"dtsFirst" => dtsFirst,
				"dtsPrevious" => dtsPrevious,
				"dtsNext" => dtsNext,
				"dtsLast" => dtsLast,
				"messageSuccess" => messageSuccess,
				"messageError" => messageError
			);

			$idProvidersData = $this->getIdProviders();
			$idProviders = $idProvidersData["idProviders"];
			$openIdProvider = $idProvidersData["openIdProvider"];
			
			$data = array(
				"labels" => $labels,
				"idProviders" => $idProviders,
				"openIdProvider" => $openIdProvider
			);

			$this->response($data);
		}

		private function getIdProvidersDataTable(){

			$data = array();
			$row = array();
			$totalRecords = 0;
			// Identity Provider
			$identityProviders = $this->xmlApi->getElementList("identityProvider");
			foreach( $identityProviders as $idProvider ){
				
				$row = array((String)$idProvider->attributes()->id, (String)$idProvider->name, (String)$idProvider->oauth, (String)$idProvider->active);
				array_push($data,$row);
				$totalRecords++;
			}
			
			$data = array("sEcho" => 1, "iTotalRecords" => $totalRecords, "iTotalDisplayRecords" => $totalRecords, "aaData" => $data);

			$this->response($data);
		}

		private function getIdProviderData(){

			$labels = array(
				"id" => Id,
				"name" => Name,
				"label" => Label,
				"active" => Active,
				"no" => No,
				"yes" => Yes,
				"oauth" => Oauth,
				"requestTokenUrl" => RequestTokenUrl,
				"dialogUrl" => DialogUrl,
				"accessTokenUrl" => AccessTokenUrl,
				"authorizationHeader" => AuthorizationHeader,
				"urlParameters" => UrlParameters,
				"clientId" => ClientId,
				"clientSecret" => ClientSecret,
				"scope" => Scope,
				"hasAccessTokenExtraParameter" => HasAccessTokenExtraParameter,
				"accessTokenExtraParameterName" => AccessTokenExtraParameterName,
				"userDataUrl" => UserDataUrl,
				"userDataNameKey" => UserDataNameKey,
				"formUrl" => FormUrl,
				"scopeRequired" => ScopeRequired,
				"scopeOptional" => ScopeOptional,
				"userDataIdKey" => UserDataIdKey,
				"immediate" => Immediate,
				"buttonPreview" => ButtonPreview
			);

			$new = true;
			$method = "new";
			$id = "";
			if (isset($_GET["id"]) && !empty($_GET["id"])){
				$id = $_GET["id"];
				$new = false;
				$method = "update";
			}
			
			$idProviderFixed = array();
			$idProvider = new stdClass();
			if (!empty($id)){
				$idProvider = $this->xmlApi->getElementById("identityProvider",$id);
				$idProvider->id = $idProvider->attributes()->id;
				foreach ($idProvider as $key => $value){
					if ( is_object($value) ){
						$idProviderFixed[$key] = (string)$value;
					}
				}
			}

			$buttonsArray = array();
			$buttons = $this->xmlApi->getElementList("buttonStyle");
 			
 			foreach( $buttons as $b ){
 				array_push($buttonsArray, (String)$b->attributes()->id);
 			}
			asort($buttonsArray);

			$buttonsOptions = array();
 			foreach( $buttonsArray as $b ){
 				$bOption = array(
 					"value" => $b,
 					"content" => $b
 				);
 				array_push($buttonsOptions, $bOption);
 			}
			//options: [{ value: 3, content: 'test3' }, { value: 4, content: 'test4' }, { value: 5, content: 'test5' }, { value: 6, content: 'test6'}],

			$data = array(
				"labels" => $labels,
				"new" => $new,
				"method" => $method,
				"idProvider" => $idProviderFixed,
				"idProvidersButtons" => $buttonsOptions
			);

			$this->response($data);
		}

		private function deleteIdProvider(){
			$this->xmlApi->removeElementById("identityProvider", $_POST["id"]);

			$result = "SUCCESS";

			$data = array(
				"status" => $result
			);

			$this->response($data);
		}

		private function updateIdProvidersImpl(){

			//update idprovider data
			for($i=0;$i<count($_POST["idProviderId"]);$i++){
				$this->xmlApi->updateElementById(
					array("active","clientId","clientSecret","scope"),
					array($_POST["active"][$i],$_POST["clientId"][$i],$_POST["clientSecret"][$i],$_POST["scope"][$i]),
					"identityProvider",
					$_POST["idProviderId"][$i]
				);
			}

			// update openid idprovider data
			$this->xmlApi->updateElementById(
				array("active","scopeRequired","scopeOptional"),
				array($_POST["openIdActive"],$_POST["scopeRequired"],$_POST["scopeOptional"]),
				"identityProvider",
				$_POST["openIdProviderId"]
			);
			
		}

		private function updateIdProviders(){

			$this->updateIdProvidersImpl();

			$data = array(
				"status" => "SUCCESS"
			);

			$this->response($data);	
		}

		private function saveIdProvider(){

			//crear uno nuevo si no existe
			if ($_POST["method"] == "new"){
				
				$this->newIdProvider();
				
			}else{
				//update idprovider data
				$this->updateIdProvider();
			}

			$result = "SUCCESS";

			$data = array(
				"status" => $result
			);

			$this->response($data);
		}

		private function newIdProvider(){
			if($_POST["id"] != "openid"){

				$this->xmlApi->addElement(
				
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
						"clientId",
						"clientSecret",
						"scope",
						"hasAccessTokenExtraParameter",
						"accessTokenExtraParameterName",
						"userDataUrl",
						"userDataIdKey"
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
						$_POST["clientId"],
						$_POST["clientSecret"],
						$_POST["scope"],
						$_POST["hasAccessTokenExtraParameter"],
						$_POST["accessTokenExtraParameterName"],
						$_POST["userDataUrl"],
						$_POST["userDataIdKey"]
					),
					
					"identityProvider",
					
					"identityProviders"
				);
			}
		}

		private function updateIdProvider(){
			if($_POST["id"] == "openid"){
					
				$this->updateOpenIdProvider();
			
			}else{
			
				$this->xmlApi->updateElementById(
					
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
						"clientId",
						"clientSecret",
						"scope",
						"hasAccessTokenExtraParameter",
						"accessTokenExtraParameterName",
						"userDataUrl",
						"userDataIdKey"
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
						$_POST["clientId"],
						$_POST["clientSecret"],
						$_POST["scope"],
						$_POST["hasAccessTokenExtraParameter"],
						$_POST["accessTokenExtraParameterName"],
						$_POST["userDataUrl"],
						$_POST["userDataIdKey"]
					),
					
					"identityProvider",
					
					$_POST["id"]
				);
			}
		}

		private function updateOpenIdProvider(){
			$this->xmlApi->updateElementById(
					
				array(
					"name",
					"label",
					"active",
					"formUrl",
					"scopeRequired",
					"scopeOptional",
					"userDataIdKey",
					"immediate"
				),
				
				array(
					$_POST["name"],
					$_POST["label"],
					$_POST["active"],
					$_POST["formUrl"],
					$_POST["scopeRequired"],
					$_POST["scopeOptional"],
					$_POST["userDataIdKey"],
					$_POST["immediate"]
				),
				
				"identityProvider",
				
				$_POST["id"]
			);
		}

		private function getLanguagesConfigData(){
			$labels = array(
				"languagesConfig" => LanguagesConfig,
				"downloadLangFile" => DownloadLangFile,
				"uploadLangFile" => UploadLangFile,
				"translateLangFile" => TranslateLangFile,
				"langName" => LangName,
				"btnDownload" => btnDownload,
				"btnUpload" => btnUpload,
				"messageSuccess" => messageSuccess,
				"messageError" => messageError
			);

			$data = array(
				"labels" => $labels
			);

			$this->response($data);
		}

		private function uploadLangFile(){

			$targetFilepath = "i18n/" . basename($_FILES['langFile']['name']);
 			
 			$result = "ERROR";

			if (move_uploaded_file($_FILES['langFile']['tmp_name'], $targetFilepath)) {
				$result = "SUCCESS";
			}

			$data = array(
				"status" => $result,
				"file" => $_FILES['langFile']['name'],
				"name" => $_POST['langName']
			);

			$this->response($data);
		}

		private function compileLanguageFile(){

			$content = array();

			function printDefine($key,$value,&$content){
				//global $content;
				array_push($content, 'define("'.$key.'","'.$value.'");' );
				array_push($content, "\n");
			}

			function printSection($key,&$content){
				//global $content;
				array_push($content, "\n");
				array_push($content, '/* '.$key.' */' );
				array_push($content, "\n\n");
			}

			function recorrer($anArray,&$content){
				foreach ($anArray as $key => $value){
		    		printDefine($key,$value,$content);
				}
			}

			$langFile = $_POST["langFile"];
			$langName = $_POST["langName"];
			$langCode = str_replace(".ini", "", $langFile);

			if ($langFile != ""){
				
				$dir = "i18n/";
				$fileCompiled = "../".$dir.str_replace("ini", "php", $langFile);
				$inifile = parse_ini_file($dir.$langFile, true);

				array_push($content, "<?php");
				array_push($content, "\n");

				foreach ($inifile as $key => $value){
			    	if ( is_array($value) ){
						printSection($key,$content);
						recorrer($value,$content);
					}else{
						printDefine($key,$value,$content);
					}
					
				}

				array_push($content, "\n");
				array_push($content, "?>");

				file_put_contents($fileCompiled, $content);

				$this->xmlApi->addElement(
				
					"",
					
					array(
						"name",
						"code",
						"selected",
					),
					
					array(
						$langName,
						$langCode,
						"0",
					),
					
					"language",
					
					"languages"
				);
			}
			

			$result = "SUCCESS";

			$data = array(
				"status" => $result
			);

			$this->response($data);
		}

		private function getLoginWidgetData(){

			$labels = array(
				"loginFormDesc" => LoginFormDesc
			);

			$eslipSettings = $this->xmlApi->getElementValue("configuration");
			$pluginUrl = (string)$eslipSettings->pluginUrl;

			$data = array(
				"labels" => $labels,
				"pluginUrl"  => $pluginUrl,
				"pluginCss" => $pluginUrl.$_GET["cssUri"],
				"pluginJs" => $pluginUrl.$_GET["jsUri"],
				"eslipDivId" => $_GET["eslipDiv"]
			);

			$this->response($data);
		}

		private function getIdProvidersButtonsData(){

			$labels = array(
				"idProvidersButtons" => IdProvidersButtons,
				"id" => Id,
				"btnNew" => btnNew,
				"btnEdit" => btnEdit,
				"btnDelete" => btnDelete,
				"btnSave" => btnSave,
				"btnCancel" => btnCancel,
				"btnConfirm" => btnConfirm,
				"processing" => Procesando,
				"dtsLoadingRecords" => dtsLoadingRecords,
				"dtsLengthMenu" => dtsLengthMenu,
				"dtsZeroRecords" => dtsZeroRecords,
				"dtsInfo" => dtsInfo,
				"dtsInfoEmpty" => dtsInfoEmpty,
				"dtsInfoFiltered" => dtsInfoFiltered,
				"dtsSearch" => dtsSearch,
				"dtsFirst" => dtsFirst,
				"dtsPrevious" => dtsPrevious,
				"dtsNext" => dtsNext,
				"dtsLast" => dtsLast,
				"messageSuccess" => messageSuccess,
				"messageError" => messageError
			);

			$data = array(
				"labels" => $labels
			);

			$this->response($data);
		}

		private function getIdProvidersButtonsDataTable(){
			$data = array();
			$row = array();
			$totalRecords = 0;
			// Identity Provider
			$idButtons = $this->xmlApi->getElementList("buttonStyle");
			foreach( $idButtons as $idButton ){
				
				$row = array((String)$idButton->attributes()->id, (String)$idButton->logo, (String)$idButton->textColor, (String)$idButton->backgroundColor);
				array_push($data,$row);
				$totalRecords++;
			}
			
			$data = array("sEcho" => 1, "iTotalRecords" => $totalRecords, "iTotalDisplayRecords" => $totalRecords, "aaData" => $data);

			$this->response($data);
		}

		private function getIdProviderButtonData(){
			$labels = array(
				"id" => Id,
				"logo" => Logo,
				"textColor" => TextColor,
				"backgroundColor" => BackgroundColor
			);

			$new = true;
			$method = "new";
			$id = "";
			if (isset($_GET["id"]) && !empty($_GET["id"])){
				$id = $_GET["id"];
				$new = false;
				$method = "update";
			}
			
			$buttonFixed = array();
			$button = new stdClass();
			if (!empty($id)){
				$button = $this->xmlApi->getElementById("buttonStyle",$id);
				$button->id = $button->attributes()->id;
				foreach ($button as $key => $value){
					if ( is_object($value) ){
						$buttonFixed[$key] = (string)$value;
					}
				}
			}

			$data = array(
				"labels" => $labels,
				"new" => $new,
				"method" => $method,
				"button" => $buttonFixed
			);

			$this->response($data);
		}

		private function saveIdProviderButton(){

			$result = "ERROR";

			if ( ! empty($_FILES['logo']['name']) ){
				$nameParts = explode('.', $_FILES['logo']['name']);
				$logo = $_POST["id"].'.'.$nameParts[1];
				$targetFilepath = '../frontend/img/icons/' . $logo;

				if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFilepath)) {
					$_POST["logo"] = $logo;

					//crear uno nuevo si no existe
					if ($_POST["method"] == "new"){
						
						$this->newIdProviderButton();
						
					}else{
						//update idprovider button data
						$this->updateIdProviderButton();
					}

					$result = "SUCCESS";
				}
			}else if ($_POST["method"] == "update"){
				$button = $this->xmlApi->getElementById("buttonStyle",$_POST["id"]);
				$_POST["logo"] = (string)$button->logo;
				$this->updateIdProviderButton();
				$result = "SUCCESS";
			}

			$data = array(
				"status" => $result,
				"id" => $_POST["id"]
			);

			$this->response($data);
		}

		private function newIdProviderButton(){

			$this->xmlApi->addElement(
			
				$_POST["id"],
				
				array(
					"logo",
					"textColor",
					"backgroundColor"
				),
				
				array(
					$_POST["logo"],
					$_POST["textColor"],
					$_POST["backgroundColor"]
				),
				
				"buttonStyle",
				
				"buttonStyles"
			);
		}

		private function updateIdProviderButton(){
			
			$this->xmlApi->updateElementById(
				
				array(
					"logo",
					"textColor",
					"backgroundColor"
				),
				
				array(
					$_POST["logo"],
					$_POST["textColor"],
					$_POST["backgroundColor"]
				),
				
				"buttonStyle",
				
				$_POST["id"]
			);
		}

		private function deleteIdProviderButton(){
			
			$result = "ERROR";
			
			$button = $this->xmlApi->getElementById("buttonStyle",$_POST["id"]);
			$logo = '../frontend/img/icons/' . (string)$button->logo;
			if (unlink($logo)){
				$this->xmlApi->removeElementById("buttonStyle", $_POST["id"]);
				$result = "SUCCESS";
			}

			$data = array(
				"status" => $result
			);

			$this->response($data);
		}

		/******************************************************
		* Wizard Setup Functions
		*******************************************************/

		private function getLanguages(){
			$eslipLangs = $this->xmlApi->getElementList("language");
			$this->response($eslipLangs);	
		}
		
		private function runFullWizard(){
			$runWizard = (bool)(String)$this->xmlApi->getElementValue("runWizard","configuration");
			$data = array(
				"runFullWizard" => $runWizard
			);
			$this->response($data);
		}

		private function getWizardData(){


			if ( isset($_POST["lang"]) ){
				$selectedLang = $_POST["lang"];
			}else{
				$selectedLang = $this->xmlApi->getElementListByFieldValue("selected", "1", "language");	
				$selectedLang = (empty($selectedLang) || empty($selectedLang[0]->code )) ? getSystemLang() : (String)$selectedLang[0]->code;
			}
			
			$labels = array(
				"createAdminUser" => CreateAdminUser,
				"adminUser" => AdminUser,
				"adminPass" => AdminPass,
				"adminPassConfirm" => AdminPassConfirm,
				"generalConfigs" => GeneralConfigs,
				"siteUrl" => SiteUrl,
				"callbackUrl" => CallbackUrl,
				"pluginUrl" => PluginUrl,
				"idProviders" => IdProviders,
				"clientId" => ClientId,
				"clientSecret" => ClientSecret,
				"scopeRequired" => ScopeRequired,
				"scopeOptional" => ScopeOptional,
				"scope" => Scope,
				"next" => next,
				"previous" => previous,
				"cancel" => cancel,
				"finish" => finish
			);

			// Configuration
			$eslipSettings = $this->xmlApi->getElementValue("configuration");
			$settings = array(
				"adminUser" => (string)$eslipSettings->adminUser,
				"siteUrl" => (string)$eslipSettings->siteUrl,
				"callbackUrl" => (string)$eslipSettings->callbackUrl,
				"pluginUrl" => (string)$eslipSettings->pluginUrl,
				"runFullWizard" => (bool)(string)$eslipSettings->runWizard
			);

			$idProvidersData = $this->getIdProviders();
			$idProviders = $idProvidersData["idProviders"];
			$openIdProvider = $idProvidersData["openIdProvider"];
			
			$data = array(
				"selectedLang" => $selectedLang,
				"labels"  => $labels,
				"settings" => $settings,
				"idProviders" => $idProviders,
				"openIdProvider" => $openIdProvider
			);

			$this->response($data);	
		}

		private function saveConfiguration(){

			//update first time config
			$this->xmlApi->setElementValue("runWizard", "0", "configuration");
			
			//update language
			$this->xmlApi->updateElement(array("selected"), array("0"), "language");
			$this->xmlApi->setElementListByFieldValue("code", $_POST["language"], "language",null,"selected","1");

			//update admin user
			if (isset($_POST["adminUser"])){
				$this->xmlApi->setElementValue("adminUser", $_POST["adminUser"], "configuration");
				$this->xmlApi->setElementValue("adminPass", getEncrypted($_POST["adminPass"]), "configuration");	
			}
			
			
			//update configuration
			$this->xmlApi->setElementValue("siteUrl", $_POST["siteUrl"], "configuration");
			$this->xmlApi->setElementValue("callbackUrl", $_POST["callbackUrl"], "configuration");
			$this->xmlApi->setElementValue("pluginUrl", $_POST["pluginUrl"], "configuration");
			
			// update id providers and open id provider
			$this->updateIdProvidersImpl();

			$data = array(
				"status" => "SUCCESS"
			);

			$this->response($data);	
		}

		private function getWizardEndData(){

			$labels = array(
				"wizardEndTitle" => WizardEndTitle,
				"wizardEndSubTitle" => WizardEndSubTitle,
				"loginFormDesc" => LoginFormDesc
			);

			$eslipSettings = $this->xmlApi->getElementValue("configuration");
			$pluginUrl = (string)$eslipSettings->pluginUrl;

			$data = array(
				"labels" => $labels,
				"pluginUrl"  => $pluginUrl,
				"pluginCss" => $pluginUrl.$_GET["cssUri"],
				"pluginJs" => $pluginUrl.$_GET["jsUri"],
				"eslipDivId" => $_GET["eslipDiv"]
			);

			$this->response($data);	
		}

	}
	
	// Inicializar la API
	
	$serviceApi = new ServiceApi($eslip);
	$serviceApi->callService();
?>