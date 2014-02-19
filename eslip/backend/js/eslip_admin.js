//**********************************************************
// Variables Definitions
//**********************************************************

var SERVICES_URL = "services/";
var TEMPLATES_COMMON_DIR = "views/common/";
var TEMPLATES_BASE_DIR = "views/admin/";
var TEMPLATES = {
	"generalConfig": TEMPLATES_BASE_DIR + "generalConfig.html",
	"userConfig": TEMPLATES_BASE_DIR + "userConfig.html",
	"idProviders": TEMPLATES_BASE_DIR + "idProviders.html",
	"openIdProviderStandar": TEMPLATES_COMMON_DIR + "openIdProvider.html", 
	"idProvidersStandar": TEMPLATES_COMMON_DIR + "idProviders.html",
	"idProviderForm": TEMPLATES_BASE_DIR + "idProviderForm.html",
	"openIdProviderForm": TEMPLATES_BASE_DIR + "openIdProviderForm.html",
	"languagesConfig": TEMPLATES_BASE_DIR + "languagesConfig.html"
};
var SERVICES = {
	"login": "login",
	"logout": "logout",
	"getGeneralConfigData": "getGeneralConfigData",
	"saveGeneralConfig" : "saveGeneralConfig",
	"getUserConfigData" : "getUserConfigData",
	"saveUserConfig" : "saveUserConfig",
	"getIdProvidersData": "getIdProvidersData",
	"getIdProvidersDataTable": "getIdProvidersDataTable",
	"getIdProviderData" : "getIdProviderData",
	"updateIdProviders" : "updateIdProviders",
	"saveIdProvider" : "saveIdProvider",
	"deleteIdProvider" : "deleteIdProvider",
	"getLanguagesConfigData" : "getLanguagesConfigData",
	"uploadLangFile" : "uploadLangFile",
	"compileLanguageFile" : "compileLanguageFile"
};

var $loginButton;
var $loginForm;
var $content;
var oTable;
var oTableSettings = {};
var selectedTab = 0;

//**********************************************************
// Document Ready
//**********************************************************

$(function() {

	initSelectors();

	initButtons();

	bindLoginEvents();

	bindLogoutEvents();

	initMenuOptions();

	bindAdminResize();
});

//**********************************************************
// Common Functions
//**********************************************************

function initSelectors(){
	$loginButton = $("#login");
	$loginForm = $("#loginForm");
	$content = $("#content");
}

function bindAdminResize(){

	$(window).resize(function() {
		updateAdminHeight();
	});
	
	var updateAdminHeight = function(){
		var height = $(window).outerHeight() - ($("#adminHeader").outerHeight() + $("#adminFooter").outerHeight() + 55);
		//console.info(height +" = "+ $(window).outerHeight() + " - " + " ( "+$("#adminHeader").outerHeight()+" + "+$("#adminFooter").outerHeight()+" + 55 )");
		$content.height(height);
	};
	updateAdminHeight();
}

//**********************************************************
// Login Functions
//**********************************************************

function bindLoginEvents(){

	$loginButton.click(function(){
		$data = $loginForm.serialize();
		apiPost(SERVICES.login, $data, function(data){
			if (data.status == "ERROR"){
				$(".errorMessage").show();
			}else{
				window.location.reload(true);
			}
		});
	});

	$loginForm.find('input').keypress(function(e) {
		if(e.which == 13) {
			$(this).blur();
			$loginButton.focus().click();
		}
	});
}

function bindLogoutEvents(){
	$("#logout a").click(function(){
		apiPost(SERVICES.logout, {}, function(){
			window.location.reload(true);
		});
	});
}

//**********************************************************
// Admin Menu Functions
//**********************************************************

function initMenuOptions(){
	$("#idProviders").click(function(){
		selectMenuItem($(this));
		loadIdProvidersContent();
	});
	
	$("#configUser").click(function(){
		selectMenuItem($(this));
		loadUserConfigContent();
	});
	
	$("#generalConfig").click(function(){
		selectMenuItem($(this));
		loadGeneralConfigContent();
	});

	$("#languagesConfig").click(function(){
		selectMenuItem($(this));
		loadLanguagesConfigContent();
	});

	$("#generalConfig").click();
}

function selectMenuItem($element){
	$(".ui-menu-item").removeClass("ui-state-highlight");
	$element.parents(".ui-menu-item").addClass("ui-state-highlight");
}

//**********************************************************
// Admin General Config Functions
//**********************************************************

function loadGeneralConfigContent(){
	loadContent(SERVICES.getGeneralConfigData, {}, TEMPLATES.generalConfig, $content, loadGeneralConfigContentCallback);
}

function loadGeneralConfigContentCallback(data){
	
	initDefaultValues();

	initLanguageSelect(data);

	initButtons();

	initGeneralConfigFormValidation();

	bindGeneralConfigEvents();
}

function initDefaultValues(){
	suggestSiteUrl($("#siteUrl"));
	suggestPluginUrl($("#pluginUrl"));
}

function initLanguageSelect(data){
	$("#language").val(data.settings.selectedLang);
	
	$.each( $("#language").find("option"), function(i, element){
		$(element).html($(element).text());
	});
}

function initGeneralConfigFormValidation(){
	$("#form").validate({
		rules: {
			siteUrl: "required",
			callbackUrl: "required",
			pluginUrl: "required"
		}
	});
}

function bindGeneralConfigEvents(){
	$('#save').off("click").on("click", function() {
		if ($("#form").valid()){
			$("#siteUrl").val( formatUrl( $("#siteUrl").val() ) );
			$("#pluginUrl").val( formatUrl( $("#pluginUrl").val() ) );
			$data = $("#form").serialize();
			submitData(SERVICES.saveGeneralConfig, $data);
		}
	});
	
	$('#cancel').off("click").on("click", function() {
		$("#generalConfig").click();
	});
}

//**********************************************************
// Admin User Config Functions
//**********************************************************

function loadUserConfigContent(){
	loadContent(SERVICES.getUserConfigData, {}, TEMPLATES.userConfig, $content, loadUserConfigContentCallback);
}

function loadUserConfigContentCallback(data){
	
	initButtons();

	initUserConfigFormValidation();

	bindUserConfigEvents();

}

function initUserConfigFormValidation(){
	$("#form").validate({
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
}

function bindUserConfigEvents(){
	$('#save').off("click").on("click", function() {
		if ($("#form").valid()){
			$data = $("#form").serialize();
			submitData(SERVICES.saveUserConfig, $data);
		}

	});

	$('#cancel').off("click").on("click", function() {
		$("#configUser").click();
	});
}

//**********************************************************
// Admin Languages Config Functions
//**********************************************************

function loadLanguagesConfigContent(){
	loadContent(SERVICES.getLanguagesConfigData, {}, TEMPLATES.languagesConfig, $content, loadLanguagesConfigContentCallback);
}

function loadLanguagesConfigContentCallback(data){

	initButtons();
	
	initLanguagesConfigFormValidation();

	bindLanguagesConfigEvents();	
}

function initLanguagesConfigFormValidation(){
	$("#uploadForm").validate({
		rules: {
			langName: "required",
			langFile: "required"
		}
	});
}

function bindLanguagesConfigEvents(){

	$("#downloadLangFile").click(function(){
		window.location.href = "download.php";
	});

	var $uploadForm = $("#uploadForm");
	$uploadForm.attr("action", SERVICES_URL+SERVICES.uploadLangFile);
	$("#uploadLangFile").click(function(){
		if ($uploadForm.valid()){
			$uploadForm.submit();
		}
	});
	
	var $hiddenUploadFrame = $("#hiddenUpload");
	$hiddenUploadFrame.load(function(){
		uploadLangFileCallback(this);
	});
}

function uploadLangFileCallback(hiddenUploadFrame) {

	var response = $(hiddenUploadFrame).contents().find("body").html();
	if (response.length) {
		// Convertir a objeto JSON
		var responseObject = eval("("+response+")");
		var params = {langFile: responseObject.file, langName: responseObject.name};
		submitData(SERVICES.compileLanguageFile, params);
	}
}

//**********************************************************
// Admin Id Providers Functions
//**********************************************************

function loadIdProvidersContent(){

	loadContent(SERVICES.getIdProvidersData, {}, TEMPLATES.idProviders, $content, function(data){
		
		$.each(data.idProviders,function(i, p){ data.idProviders[i].labels = data.labels; });
		$("#idProvidersContainer").loadTemplate(TEMPLATES.idProvidersStandar, data.idProviders, {
			overwriteCache: true,
			complete: function(){

				data.openIdProvider.labels = data.labels;
				$("#idProvidersContainer").loadTemplate(TEMPLATES.openIdProviderStandar, data.openIdProvider, {
					prepend: true,
					overwriteCache: true,
					complete: function(){
						loadIdProvidersContentCallback(data);
					}
				});
			}
		});

	});
}

function loadIdProvidersContentCallback(data){

	sortIdProviders();
	
	vtip();

	initButtons();

	initDataTables(data);

	initSwitchOnOff();

	initTabs();

	initIdProvidersDialogs(data);

	bindIdProvidersEvents(data);

}

function initTabs(){
	selectedTab = selectedTab || 0;
	$( "#tabs" ).tabs({ active: selectedTab });
}

// Get the rows which are currently selected
function fnGetSelected( oTableLocal ){
	return oTableLocal.$('tr.row_selected');
}

function initDataTables(data){

	// Add a click handler to the rows
	$("#idProviderTable tbody").click(function(event) {
		$(oTable.fnSettings().aoData).each(function (){
			$(this.nTr).removeClass('row_selected');
		});
		$(event.target.parentNode).addClass('row_selected');
	});

	oTableSettings = {
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"iDisplayLength": 100,
		"bRetrieve":true,
		"sAjaxSource": SERVICES_URL+SERVICES.getIdProvidersDataTable,
		"bProcessing": true,
		"bFilter": false,
		"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
			// Add row id
			$(nRow).attr("id",aData[0]);
		},
		"oLanguage": {
			"sProcessing": data.labels.processing,
			"sLoadingRecords": data.labels.dtsLoadingRecords,
			"sLengthMenu": data.labels.dtsLengthMenu,
			"sZeroRecords": data.labels.dtsZeroRecords,
			"sInfo": data.labels.dtsInfo,
			"sInfoEmpty": data.labels.dtsInfoEmpty,
			"sInfoFiltered": data.labels.dtsInfoFiltered,
			"sSearch": data.labels.dtsSearch,
			"oPaginate": {
				"sFirst": data.labels.dtsFirst,
				"sPrevious": data.labels.dtsPrevious,
				"sNext": data.labels.dtsNext,
				"sLast": data.labels.dtsLast
			}
		}
	};

	oTable = $('#idProviderTable').dataTable(oTableSettings);
}

function initIdProvidersDialogs(data){

	var dialogEditButtons = {};
	var dialogConfirmButtons = {};
	var dialogCancelButton = function() {
		$( this ).dialog( "close" );
	};

	dialogEditButtons[data.labels.btnSave] = function() {
		var $data = $( this ).find("form").serialize();
		saveOrUpdateIdProvider($data);
		$( this ).dialog( "close" );
	};

	dialogEditButtons[data.labels.btnCancel] = dialogCancelButton;

	dialogConfirmButtons[data.labels.btnConfirm] = function() {
		var selected = fnGetSelected( oTable );
		if ( selected.length !== 0 ) {
			deleteIdProvider($(selected).attr("id"));
		}
		$( this ).dialog( "close" );
	};

	dialogConfirmButtons[data.labels.btnCancel] = dialogCancelButton;

	$( "#dialog-edit" ).dialog({
		autoOpen: false,
		resizable: false,
		//height: 300,
		width: 550,
		modal: true,
		buttons: dialogEditButtons
	});
	
	 $( "#dialog-confirm" ).dialog({
		autoOpen: false,
		resizable: false,
		height:140,
		modal: true,
		buttons: dialogConfirmButtons
	});
}

function bindIdProvidersEvents(data){

	// Vista Avanzada
	
	$('#new').click( function() {
		$("#dialog-edit" ).dialog( "option", "title", data.labels.btnNew );
		loadIdProviderData();
	} );
	
	$('#edit').click( function() {
		var selected = fnGetSelected( oTable );
		if ( selected.length !== 0 ) {
			$("#dialog-edit" ).dialog( "option", "title", data.labels.btnEdit );
			loadIdProviderData($(selected).attr("id"));
		}
	} );

	$('#delete').click( function() {
		var anSelected = fnGetSelected( oTable );
		if ( anSelected.length !== 0 ) {
			$("#dialog-confirm" ).dialog( "option", "title", data.labels.btnDelete );
			$("#dialog-confirm" ).dialog( "open" );
		}
	} );

	// Vista estandar
	
	$('#save').off("click").on("click", function() {
		$data = $("#form3").serialize();
		submitData(SERVICES.updateIdProviders, $data);
	});
	
	$('#cancel').off("click").on("click", function() {
		$("#idProviders").click();
	});

}

function deleteIdProvider(selectedId){
	submitData(SERVICES.deleteIdProvider, {id: selectedId}, function(){
		oTable.fnDestroy();
		oTable = $('#idProviderTable').dataTable(oTableSettings);
	});
}

function saveOrUpdateIdProvider($data){
	submitData(SERVICES.saveIdProvider, $data, function(){
		/*oTable.fnDestroy();
		oTable = $('#idProviderTable').dataTable(oTableSettings);*/
		selectedTab = $( "#tabs" ).tabs( "option", "active" );
		loadIdProvidersContent();
	});
}

function loadIdProviderData(selectedId){
	var formTemplate = (selectedId == "openid") ? TEMPLATES.openIdProviderForm : TEMPLATES.idProviderForm;
	loadContent(SERVICES.getIdProviderData, {id: selectedId}, formTemplate, $("#dialog-edit"), loadIdProviderDataCallback);
}

function loadIdProviderDataCallback(data){
	
	updateSelectValues(data);

	if( $("#method").val() == "new" ){
		addNewIdProviderInput(data);
	}
	
	// open dialog
	$("#dialog-edit" ).dialog( "open" );
}

function updateSelectValues(data){
	$("#active").val(data.idProvider.active).change();
	$("#oauth").val(data.idProvider.oauth).change();
	$("#authorizationHeader").val(data.idProvider.authorizationHeader).change();
	$("#hasAccessTokenExtraParameter").val(data.idProvider.hasAccessTokenExtraParameter).change();
	$("#immediate").val(data.idProvider.immediate).change();
}

function addNewIdProviderInput(data){
	var newId = '<div class="reng">'+
					'<label for="id">'+data.labels.id+':</label>'+
					'<input type="text" id="id" name="id" value="" />'+
				'</div>'
	;

	$("#id").remove();
	$("#form").prepend(newId);
	
	$( "#form" ).find("#id").autocomplete({
		source: availableTags
	});
}