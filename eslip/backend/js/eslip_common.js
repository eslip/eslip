//**********************************************************
// Variables Definitions
//**********************************************************

var SERVICES_URL = "services/";

//**********************************************************
// Common Functions
//**********************************************************

function initButtons(){
	$( "input[type=button], input[type=submit], button" ).button();
}

function initSwitchOnOff(){
	
	$(".switch").switchOnOff();
	
	$(".idProviderData[active='0']").hide();

	$(".cb-enable, .cb-disable").bind( "onoff-switch" , {}, function(event, params){
		var $idProvider = $(event.target).parents(".idProvider");
		if (params.value == "on"){
			$idProvider.find(".idProviderData").show();
		}else{
			$idProvider.find(".idProviderData").hide();
		}
		
	});
}

function loadContent(url, params, template, container, callback){
	apiGet(url, params, function(data){
		$(container).loadTemplate(template, data, {
			overwriteCache: true,
			complete: function(){
				callback(data);
			}
		});
	});
}

function submitData(url, params, callback){
	apiPost(url, params, function(data){
		if (data.status == "SUCCESS"){
			if (typeof callback == "function"){
				callback(data);
			}
			$(".successMessage").show();
		}else{
			$(".errorMessage").show();
		}
	});
}

function formatUrl(url){
	var hasTrailingSlash = url.charAt(url.length - 1) === "/";
	if (! hasTrailingSlash){
		url += "/";
	}
	return url;
}

function suggestSiteUrl(input){
	var siteUrl = $(input).val();
	if (siteUrl == ""){
		siteUrl = window.location.origin + "/";
		$(input).val(siteUrl);
	}
}

function suggestPluginUrl(input){
	var pluginUrl = $(input).val();
	if ( pluginUrl == ""){
		pluginUrl = window.location.origin + "/eslip-plugin/eslip/";
		$(input).val(pluginUrl);
	}
}

function sortIdProviders(){	
	$(".idProvider").sort(function(a,b){
		return $(a).attr("id") > $(b).attr("id") ? 1 : -1;
	}).appendTo('#idProvidersContainer');

	$.each($(".idProvider").find(".switch[active='1']").get().reverse(), function(i,elem){
		$('#idProvidersContainer').prepend($(elem).parents(".idProvider"));
	});
}

//**********************************************************
// Helper Functions
//**********************************************************

function apiGet(uri, data, callback){
	$.ajax(SERVICES_URL+uri,{
		data: data,
		dataType: 'JSON',
		type: 'GET',
		async: true
	}).done(function(data){
		callback(data);
	});
}

function apiPost(uri, data, callback){
	$.ajax(SERVICES_URL+uri,{
		data: data,
		dataType: 'JSON',
		type: 'POST',
		async: true
	}).done(function(data){
		callback(data);
	});
}

function getFormData($form){
	var unindexed_array = $form.serializeArray();
	var indexed_array = {};

	$.map(unindexed_array, function(n, i){
		indexed_array[n['name']] = n['value'];
	});

	return indexed_array;
}