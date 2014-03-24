//**********************************************************
// Variables Definitions
//**********************************************************

var CSS_URI = "frontend/eslip_plugin.css";
var JS_URI = "frontend/eslip_plugin.js";
var LOGO_URI = "../frontend/img/icons/";
var ESLIP_DIV = "ESLIP_Plugin";
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
	"languagesConfig": TEMPLATES_BASE_DIR + "languagesConfig.html",
	"loginWidget": TEMPLATES_COMMON_DIR + "loginWidget.html",
	"idProvidersButtons": TEMPLATES_BASE_DIR + "idProvidersButtons.html",
	"idProviderButtonForm": TEMPLATES_BASE_DIR + "idProviderButtonForm.html"
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
	"compileLanguageFile" : "compileLanguageFile",
	"getLoginWidgetData": "getLoginWidgetData",
	"getIdProvidersButtonsData": "getIdProvidersButtonsData",
	"getIdProvidersButtonsDataTable": "getIdProvidersButtonsDataTable",
	"getIdProviderButtonData": "getIdProviderButtonData",
	"saveIdProviderButton": "saveIdProviderButton",
	"deleteIdProviderButton" : "deleteIdProviderButton"
};

var $loginButton;
var $loginForm;
var $content;
var oTable;
var oTableSettings = {};
var selectedTab = 0;

var oTableIdsButtons;
var oTableIdsButtonsSettings = {};

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

	$("#loginWidget").click(function(){
		selectMenuItem($(this));
		loadLoginWidgetContent();
	});

	$("#idProvidersButtons").click(function(){
		selectMenuItem($(this));
		loadIdProvidersButtonsContent();
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
// Admin Login Widget Functions
//**********************************************************

function loadLoginWidgetContent(){
	var params = {cssUri: CSS_URI, jsUri: JS_URI, eslipDiv: ESLIP_DIV};
	loadContent(SERVICES.getLoginWidgetData, params, TEMPLATES.loginWidget, $content, function(data){

	});
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

	initIdProvidersButtonsDialogs(data);
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
		$( this ).find("form").find("#id").removeAttr('disabled');
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
		height: 'auto',
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
	
	var idProviderId = data.idProvider.id || $('#selectContainer').find('select#id').val();
	buttonStylePreview(idProviderId, data.idProvider.label);

	if( $( "#form" ).find("#method").val() == "new" ){
		$( "#form" ).find("input#id").remove();
		bindNewIdProviderEvents(data);
	}else{
		$( "#form" ).find("#selectContainer").remove();
	}

	$( "#form" ).find('#label').off('onkeyup').on('keyup', function(){
		$('#buttonStylePreview').find('.buttonStyle a').text($(this).val());
	});

	$("#dialog-edit").css("overflow","auto").css("height","auto");
	// open dialog
	$("#dialog-edit" ).dialog( "open" );
}

function updateSelectValues(data){
	$("#active").val(data.idProvider.active).change();
	$("#oauth").val(data.idProvider.oauth).change();
	$("#authorizationHeader").val(data.idProvider.authorizationHeader).change();
	$("#hasAccessTokenExtraParameter").val(data.idProvider.hasAccessTokenExtraParameter).change();
	$("#immediate").val(data.idProvider.immediate).change();
	$('#selectContainer').find('select#id').val(data.idProvider.id).change();
}

function bindNewIdProviderEvents(data){
	
	$( "#form" ).find("#add").click( function() {
		$("#dialog-edit-id").dialog( "option", "title", data.labels.btnNew );
		loadIdProviderButtonData();
	} );
	
	$( "#form" ).find('#edit').click( function() {
		var selected = $( "#form" ).find("#id").val();
		if ( selected !== "" ) {
			$("#dialog-edit-id").dialog( "option", "title", data.labels.btnEdit );
			loadIdProviderButtonData(selected);
		}
	} );

	$content.off('idProviderButton.updated').on('idProviderButton.updated', function(event, data){
		var $select = $( "#form" ).find('#selectContainer').find('select#id');
		if ($.exists($select)){
			var $option = $select.find('option[value="'+data.id+'"]');
			if (!$.exists($option)){
				$select.append('<option value="'+data.id+'">'+data.id+'</option>');
			}
			$select.val(data.id).change();
		}
	});

	$( "#form" ).find('#selectContainer').find('select#id').off('change').on('change', function(){
		buttonStylePreview($(this).val(), $( "#form" ).find('#label').val());	
	});

}

function buttonStylePreview(id, label){
	apiGet(SERVICES.getIdProviderButtonData, {id: id}, function(data){
		var $a = '<a href="javascript:;" class="button glow " style=""></a>';
		$a = $($a);
		$a.text(label);
		$a.addClass(data.button.id);
		if (typeof data.button.logo != 'undefined' && data.button.logo != '' ){
			$a.css('background-image', 'url(' + LOGO_URI + data.button.logo + ')');	
		}
		$a.css('background-color', data.button.backgroundColor);
		$a.css('color', data.button.textColor);
		$('#buttonStylePreview').find('.buttonStyle').html($a);
	});
}

//**********************************************************
// Admin Id Providers Buttons Functions
//**********************************************************

function loadIdProvidersButtonsContent(){
	loadContent(SERVICES.getIdProvidersButtonsData, {}, TEMPLATES.idProvidersButtons, $content, loadIdProvidersButtonsContentCallback);
}

function loadIdProvidersButtonsContentCallback(data){
	
	initButtons();

	initDataTableIdsButtons(data);

	initIdProvidersButtonsDialogs(data);

	bindIdProvidersButtonsEvents(data);
}

function initDataTableIdsButtons(data){
	
	// Add a click handler to the rows
	$("#idProviderButtonTable tbody").click(function(event) {
		$(oTableIdsButtons.fnSettings().aoData).each(function (){
			$(this.nTr).removeClass('row_selected');
		});
		$(event.target.parentNode).addClass('row_selected');
	});

	oTableIdsButtonsSettings = {
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"iDisplayLength": 100,
		"bRetrieve":true,
		"sAjaxSource": SERVICES_URL+SERVICES.getIdProvidersButtonsDataTable,
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

	oTableIdsButtons = $('#idProviderButtonTable').dataTable(oTableIdsButtonsSettings);
}

function initIdProvidersButtonsDialogs(data){

	var dialogEditButtons = {};
	var dialogConfirmButtons = {};
	var dialogCancelButton = function() {
		$( this ).dialog( "close" );
	};

	dialogEditButtons[data.labels.btnSave] = function() {
		$( this ).find("form").find("#id").removeAttr('disabled');
		
		if ($( this ).find("form").valid()){
			$( this ).find("form").submit();
			$( this ).dialog( "close" );
			$( this ).css("overflow","auto");
		}
	};

	dialogEditButtons[data.labels.btnCancel] = dialogCancelButton;

	dialogConfirmButtons[data.labels.btnConfirm] = function() {
		var selected = fnGetSelected( oTableIdsButtons );
		if ( selected.length !== 0 ) {
			deleteIdProviderButton($(selected).attr("id"));
		}
		$( this ).dialog( "close" );
		$( this ).css("overflow","auto");
	};

	dialogConfirmButtons[data.labels.btnCancel] = dialogCancelButton;

	$( "#dialog-edit-id" ).dialog({
		autoOpen: false,
		resizable: false,
		height: 350,
		width: 550,
		modal: true,
		buttons: dialogEditButtons
	});
	
	 $( "#dialog-confirm-id" ).dialog({
		autoOpen: false,
		resizable: false,
		height:140,
		modal: true,
		buttons: dialogConfirmButtons
	});
}

function bindIdProvidersButtonsEvents(data){
	
	$('#new').click( function() {
		$("#dialog-edit-id" ).dialog( "option", "title", data.labels.btnNew );
		loadIdProviderButtonData();
	} );
	
	$('#edit').click( function() {
		var selected = fnGetSelected( oTableIdsButtons );
		if ( selected.length !== 0 ) {
			$("#dialog-edit-id" ).dialog( "option", "title", data.labels.btnEdit );
			loadIdProviderButtonData($(selected).attr("id"));
		}
	} );

	$('#delete').click( function() {
		var anSelected = fnGetSelected( oTableIdsButtons );
		if ( anSelected.length !== 0 ) {
			$("#dialog-confirm-id" ).dialog( "option", "title", data.labels.btnDelete );
			$("#dialog-confirm-id" ).dialog( "open" );
		}
	} );

}

function deleteIdProviderButton(selectedId){
	submitData(SERVICES.deleteIdProviderButton, {id: selectedId}, function(){
		oTableIdsButtons.fnDestroy();
		oTableIdsButtons = $('#idProviderButtonTable').dataTable(oTableIdsButtonsSettings);
	});
}

function loadIdProviderButtonData(selectedId){
	loadContent(SERVICES.getIdProviderButtonData, {id: selectedId}, TEMPLATES.idProviderButtonForm, $("#dialog-edit-id"), loadIdProviderButtonDataCallback);
}

function loadIdProviderButtonDataCallback(data){
	if( $( "#form-id" ).find("#method").val() == "new" ){
		$( "#form-id" ).find("#id").removeAttr('disabled').removeClass("disabled");
		setNewIdProviderButtonActions();
	}else{
		$( "#form-id" ).find("#id").attr('disabled','disabled').addClass("disabled");
	}
	
	initIdProviderButtonFormValidation();

	$( "#form-id" ).attr("action", SERVICES_URL+SERVICES.saveIdProviderButton);
	
	$("#hiddenUpload").load(function(){
		saveIdProviderButtonCallback(this);
	});

	//$('.colorPicker').minicolors({theme: 'default'});

	$('.colorPicker').each( function() {
		$(this).minicolors({
			control: $(this).attr('data-control') || 'hue',
			defaultValue: $(this).attr('data-defaultValue') || '',
			inline: $(this).attr('data-inline') === 'true',
			letterCase: $(this).attr('data-letterCase') || 'lowercase',
			opacity: $(this).attr('data-opacity'),
			position: $(this).attr('data-position') || 'bottom left',
			change: function(hex, opacity) {
				var log;
				try {
					log = hex ? hex : 'transparent';
					if( opacity ) log += ', ' + opacity;
				} catch(e) {}
			},
			theme: 'eslip'
		});
	});
	
	$("#dialog-edit-id").css("overflow","visible");

	// open dialog
	$("#dialog-edit-id" ).dialog( "open" );
}

function saveIdProviderButtonCallback(hiddenUploadFrame){

	var response = $(hiddenUploadFrame).contents().find("body").html();
	if (response.length) {
		// Convertir a objeto JSON
		var responseObject = eval("("+response+")");
		if ($.exists('#idProviderButtonTable')) {
			oTableIdsButtons.fnDestroy();
			oTableIdsButtons = $('#idProviderButtonTable').dataTable(oTableIdsButtonsSettings);
		}
		$content.trigger('idProviderButton.updated', {id: responseObject.id});
	}
}

function setNewIdProviderButtonActions(){
	// prevenir caracterres especiales en el id
	$( "#form-id" ).find('#id').off('onkeyup').on('keyup', function(){
		var str = $(this).val();
		//str = str.replace(/[_\s]/g, '-').replace(/[^a-z0-9-\s]/gi, '');
		str = str.replace(/[_\W]+/g, "-");
		$(this).val(str);
	});
}

function initIdProviderButtonFormValidation(){
	var rules = {
		id: "required"
	};
	
	if( $( "#form-id" ).find("#method").val() == "new" ){
		rules.logo = "required";
	}

	$("#form-id").validate({
		rules: rules
	});
}