<?php

/* WIZARD */

//step titles
define("CreateAdminUser","Create Admin User");
define("GeneralConfigs","General Settings");
define("IdProviders","Identity Providers");

//labels
define("AdminUser", "User Name");
define("AdminPass", "Password");
define("AdminPassConfirm", "Password Confirm");

//texts
define("LoginFormDesc","You must copy the following code on your page Login");

//buttons
define("next","Next");
define("previous","Previous");
define("cancel","Cancel");
define("finish","Finish");

//dialogs
define("SelectLangTitle","Select Language");
define("WizardEndTitle","Configuration Ends");
define("WizardEndSubTitle","Thanks for using ESLIP Plugin");

/* ADMIN */

//title
define("adminTitle", "ESLIP Admin");
define("Login", "Account Login");

//menu
define("ConfigUser","Usuer Settings");
define("LanguagesConfig", "Language Settings");

//labels
define("SiteUrl", "Site URL");
define("CallbackUrl", "Callback URL");
define("PluginUrl", "ESLIP Plugin URL");
define("ClientId", "Client ID");
define("ClientSecret", "Client Secret");
define("Scope", "Scope");
define("Id", "ID");
define("Name", "Name");
define("Label", "Button Label");
define("Active", "Active");
define("Oauth", "OAuth Version");
define("RequestTokenUrl", "Request Token Url");
define("DialogUrl", "Dialog Url");
define("AccessTokenUrl", "Access Token Url");
define("AuthorizationHeader", "Authorization Header");
define("UrlParameters", "Url Parameters");
define("RedirectUri", "Redirect Uri");
define("HasAccessTokenExtraParameter", "Has Access Token Extra Parameter");
define("AccessTokenExtraParameterName", "Access Token Extra Parameter Name");
define("UserDataUrl", "User Data Url");
define("UserDataNameKey", "User Data Name Key");
define("FormUrl", "Form Url");
define("ScopeRequired", "Required Scope");
define("ScopeOptional", "Optional Scope");
define("UserDataIdKey", "User Data Id Key");
define("Immediate", "Immediate Authentication");
define("Yes", "Yes");
define("No", "No");
define("LangName", "Language Name");
define("DownloadLangFile", "Download the template language file");
define("UploadLangFile", "Upload the translated language file");
define("TranslateLangFile", "Translate the downloaded file and rename it with the appropriate two letters ISO language code in lower case (http://reference.sitepoint.com/html/lang-codes)");

//dataTable
define("Procesando", "Loading...");
define("dtsLoadingRecords", "Cargando...");
define("dtsLengthMenu", "Display _MENU_ records per page");
define("dtsZeroRecords", "No records were found");
define("dtsInfo", "Showing _START_ to _END_ of _TOTAL_ entries");
define("dtsInfoEmpty", "No records were found");
define("dtsInfoFiltered","(filtering from _MAX_ records)");
define("dtsSearch", "Search:");
define("dtsFirst", "First");
define("dtsPrevious", "Previous");
define("dtsNext", "Next");
define("dtsLast", "Last");

//buttons
define("btnNew","New");
define("btnEdit","Edit");
define("btnDelete","Delete");
define("btnSave","Save");
define("btnCancel","Cancel");
define("btnConfirm","Confirm");
define("btnLogin","Log In");
define("btnLogout","Log Out");
define("btnWizard","Configuration Wizard");
define("btnDownload", "Download");
define("btnUpload", "Upload");

//confirm
define("deleteConfirm", "These item will be permanently deleted. Are you sure?");

//messages
define("messageSuccess","Data successfully saved");
define("messageError","An error occurred when saving data");
define("messageLoginInfo","If you have not registered your user you must run the ");
define("messageLoginError","An error occurred when trying to log in, please check your input data");

/* ERRORS */

//textos de errores de excepciones de la api
define("CurlError", "ESLIP ERROR: CURL PHP extension required.");
define("SessionError", "ESLIP ERROR: It was not possible to start the PHP session.");
define("ParametersErrorEslipDataInConstruct", "ESLIP ERROR: No required parameters passed. Need Eslip data in Constructor.");
define("ParametersErrorIPInConstruct", "ESLIP ERROR: No required parameters passed. Identity Provider in Constructor.");
define("NoConfigFileError", "ESLIP ERROR: No XML configuration file setted.");

//openid
define("ParametersErrorURLInOpenIDConstruct", "ESLIP ERROR: No required parameters passed. Need URL in OpenID Constructor.");
define("ParametersErrorIdentityInOpenIDDiscover", "ESLIP ERROR: No required parameters passed. Identity in OpenID Discover.");
define("URIErrorInOpenIDDiscover", "ESLIP ERROR: Could not discover an OpenID identity server endpoint.");
define("CancelInfo", "ESLIP INFO: User has canceled authentication.");
define("NoIdResError", "ESLIP ERROR: There was an error in the verification of the authentication. Was obtained a mode different than id_res.");
define("DifferentsReturnURLError", "ESLIP ERROR: There was an error in the verification of the authentication. Return URLs must match.");
define("ImmediateRedirecting", "Imposible realizar la autenticación inmediata. Redirigiendo...");

//oauth
define("CurlResponseError", "ESLIP ERROR: It was not possible to access the %s: It was returned an unexpected response status %s. Response: %s");
define("OAuthVersionError", "ESLIP ERROR: %s is not a supported version of the OAuth protocol.");
define("NoAccessTokenError", "ESLIP ERROR: Can not perform API call because the access token doesn't exist.");
define("RequestTokenDeniedError", "ESLIP ERROR: The Request Token was denied.");
define("NotReturnedAccessTokenError", "ESLIP ERROR: It was not returned the access token or secret.");
define("ExpiryTimeError", "ESLIP ERROR: OAuth server did not return a supported type of access token expiry time.");
define("AuthorizationErrorWithCodeError", "ESLIP ERROR: There was an error in the authorization. OAuth error code: %s.");
define("AuthorizationError", "ESLIP ERROR: There was an error in the authorization. It was not returned the OAuth dialog code.");
define("ServerNotReturnAccessTokenError", "ESLIP ERROR: OAuth server did not return the access token.");
define("ServerNotReturnAccessTokenWhiteCodeError", "ESLIP ERROR: It was not possible to retrieve the access token: it was returned the error: %s.");

?>