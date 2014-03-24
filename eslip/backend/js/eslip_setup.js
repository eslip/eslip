//**********************************************************
// Variables Definitions
//**********************************************************

var SERVICES_URL = "services/";
var CSS_URI = "frontend/eslip_plugin.css";
var JS_URI = "frontend/eslip_plugin.js";
var ESLIP_DIV = "ESLIP_Plugin";
var ADMIN_URL = "admin.php";
var TEMPLATES_COMMON_DIR = "views/common/";
var TEMPLATES_BASE_DIR = "views/wizard/";
var TEMPLATES = {
	"wizard": TEMPLATES_BASE_DIR + "wizard.html",
	"openIdProvider": TEMPLATES_COMMON_DIR + "openIdProvider.html", 
	"idProviders": TEMPLATES_COMMON_DIR + "idProviders.html",
	"wizardEnd": TEMPLATES_BASE_DIR + "wizardEndDialog.html"
};
var SERVICES = {
	"runFullWizard": "runFullWizard",
	"getLanguages": "getLanguages",
	"getWizardData": "getWizardData",
	"saveConfiguration" : "saveConfiguration",
	"getWizardEndData" : "getWizardEndData"
};

var $selectLanguageDialog;
var $wizardContainer;
var $selectLangContainer;
var $wizard;

//**********************************************************
// Document Ready
//**********************************************************

$(function() {

	initSelectors();

	apiGet(SERVICES.runFullWizard, {}, function(data){
		if (data.runFullWizard){

			initSelectLanguageDialog();

			populateLanguageSelect();

			showSelectLanguageDialog();

		}else{

			loadWizardContent();
		}
	});

});

//**********************************************************
// Wizard Setup Functions
//**********************************************************

function initSelectors(){
	$selectLanguageDialog = $( "#dialog-lang" );
	$wizardContainer = $("#wizardContainer");
	$selectLangContainer = $("#selectLangContainer");
}

function initSelectLanguageDialog(){

	$selectLanguageDialog.dialog({
		autoOpen: false,
		resizable: false,
		height:200,
		modal: true,
		closeOnEscape: false,
		dialogClass: "no-close",
		buttons: {
			"Seleccionar": function() {
				loadWizardContent();
				$( this ).dialog( "close" );
			}
		}
	});
}

function showSelectLanguageDialog(){
	$selectLanguageDialog.dialog( "open" );
}

function populateLanguageSelect(){
	
	apiGet(SERVICES.getLanguages, {}, function(languages){

		var $selectLang = '<select id="selectLang">';
		
		var selected = '';
		$.each(languages, function(i, lang){
			selected = (lang.selected == 1) ? "selected" : "";
			$selectLang += '<option value="'+lang.code+'" '+selected+'>'+lang.name+'</option>';
		});

		$selectLang += '</select>';

		$selectLangContainer.html($selectLang);
	});
}

function prepareWizard(data){
	$wizardContainer.hide();
	if (!data.settings.runFullWizard){
		$wizardContainer.find("#step-1").remove();	
	}
}

function loadWizardContent(){

	var selectedLang = $("#selectLang").val();

	apiPost(SERVICES.getWizardData, {lang: selectedLang}, function(data){

		$wizardContainer.loadTemplate(TEMPLATES.wizard, data, {
			overwriteCache: true,
			complete: function(){

				// mostrar o no el wizard completo
				prepareWizard(data);

				$.each(data.idProviders,function(i, p){ data.idProviders[i].labels = data.labels; });
				$("#idProvidersContainer").loadTemplate(TEMPLATES.idProviders, data.idProviders, {
					overwriteCache: true,
					complete: function(){

						data.openIdProvider.labels = data.labels;
						$("#idProvidersContainer").loadTemplate(TEMPLATES.openIdProvider, data.openIdProvider, {
							prepend: true,
							overwriteCache: true,
							complete: function(){
								loadWizardContentCallback(data);
							}
						});
					}
				});
			}
		});
		
	});

}

function loadWizardContentCallback(data){

	initDefaultValues();

	initWizard(data);

	initWizardFormValidation();

	bindWizardEvents();

	bindWizardResize();

	sortIdProviders();
	
	vtip();

	initSwitchOnOff();

}

function initDefaultValues(){
	suggestSiteUrl($("#siteUrl"));
	suggestPluginUrl($("#pluginUrl"));
}

function initWizard(data){

	$wizard = $('#wizard');

	$wizardContainer.show();

	$wizard.jWizard({
		buttons: {
			next: {
				text: data.labels.next,
				type: "button"
			},
			prev: {
				text: data.labels.previous,
				type: "button"
			},
			cancel: {
				"class": "ui-priority-secondary",
				text: data.labels.cancel,
				type: "button"
			},
			finish: {
				"class": "ui-priority-primary ui-state-highlight",
				text: data.labels.finish,
				type: "button"
			}
		},
		progress: {
			label: "count",
			append: ""
		},
		
	});
}

function initWizardFormValidation(){
						
	$wizard.find("#form1").validate({
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
	
}

function bindWizardEvents(){

	//wizard finish
	$wizard.on("wizardfinish",{},function(event){
		$("#siteUrl").val( formatUrl( $("#siteUrl").val() ) );
		$("#pluginUrl").val( formatUrl( $("#pluginUrl").val() ) );
		var $formData = "";
		$.each($wizard.find("form"),function(i,form){
				$formData += $(form).serialize()+"&";
		});
		
		apiPost(SERVICES.saveConfiguration, $formData, function(data){
			wizardEnd();
		});

		return false;
	});
	
	//wizard cancel
	$wizard.on("wizardcancel",{},function(event){
		$wizard.hide();
	});

}

function bindWizardResize(){
	//wizard resize
						
	$(window).resize(function() {
		updateWizardHeight();
	});
	
	var updateWizardHeight = function(){
		var height = $(window).outerHeight() - ($(".jw-header").outerHeight() + $(".jw-footer").outerHeight() + 55);
		//console.info(height +" = "+ $(window).outerHeight() + " - " + " ( "+$(".jw-header").outerHeight()+" + "+$(".jw-footer").outerHeight()+" + 55 )");
		$wizard.find(".jw-steps-wrap").height(height);
	};
	updateWizardHeight();
}

function wizardEnd(){
	var params = {cssUri: CSS_URI, jsUri: JS_URI, eslipDiv: ESLIP_DIV};
	loadContent(SERVICES.getWizardEndData, params, TEMPLATES.wizardEnd, $wizardContainer, showWizardEndDialog);
}

function showWizardEndDialog(){
	$("#dialog-wizard-end").dialog({
		resizable: false,
		height:380,
		width:700,
		modal: true,
		buttons: {
			"OK": function() {
				$( this ).dialog( "close" );
				window.location.href = ADMIN_URL;
			}
		}
	});
}