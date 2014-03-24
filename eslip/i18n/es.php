<?php

/* WIZARD */

//step titles
define("CreateAdminUser","Crear Usuario Administrador");
define("GeneralConfigs","Configuraciones Generales");
define("IdProviders","Proveedores de Identidad");

//labels
define("AdminUser", "Nombre de Usuario");
define("AdminPass", "Contrase&ntilde;a");
define("AdminPassConfirm", "Confirmar Contrase&ntilde;a");

//texts
define("LoginFormDesc","Debe copiar el siguiente c&oacute;digo en su p&aacute;gina de Login");

//buttons
define("next","Siguiente");
define("previous","Anterior");
define("cancel","Cancelar");
define("finish","Finalizar");

//dialogs
define("SelectLangTitle","Seleccionar Lenguaje");
define("WizardEndTitle","Configuración Finalizada");
define("WizardEndSubTitle","Gracias por usar ESLIP Plugin");

/* ADMIN */

//title
define("adminTitle", "Administrador ESLIP");
define("Login", "Iniciar Sesi&oacute;n");

//menu
define("ConfigUser","Configuraciones de Usuario");
define("LanguagesConfig", "Configuración de Idiomas");
define("LoginWidget", "Widget de Login");
define("IdProvidersButtons", "Botones de Proveedores");

//labels
define("SiteUrl", "URL del Sitio");
define("CallbackUrl", "Callback URL");
define("PluginUrl", "URL del Plugin ESLIP");
define("ClientId", "Client ID");
define("ClientSecret", "Client Secret");
define("Scope", "Scope");
define("Id", "ID");
define("Name", "Nombre");
define("Label", "Label del Bot&oacute;n");
define("Active", "Activo");
define("Oauth", "Versi&oacute;n de OAuth");
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
define("FormUrl", "Url del Formulario");
define("ScopeRequired", "Scope Requerido");
define("ScopeOptional", "Scope Opcional");
define("UserDataIdKey", "User Data Id Key");
define("Immediate", "Autenticación Inmediata");
define("Yes", "Si");
define("No", "No");
define("LangName", "Nombre del idioma");
define("DownloadLangFile", "Descargar el template del archivo de idioma");
define("UploadLangFile", "Subir el archivo de idioma traducido");
define("TranslateLangFile", "Traducir el archivo descargado y renombrarlo con el correspondiente codigo de lenguage ISO de dos letras en minúsculas (http://reference.sitepoint.com/html/lang-codes)");
define("Logo", "Logo");
define("TextColor", "Color de Texto");
define("BackgroundColor", "Color de Fondo");
define("ButtonPreview", "Vista Previa del Bot&oacute;n");

//dataTable
define("Procesando", "Procesando...");
define("dtsLoadingRecords", "Cargando...");
define("dtsLengthMenu", "Mostrar _MENU_ registros por p&aacute;gina");
define("dtsZeroRecords", "No se han encontrado registros");
define("dtsInfo", "Mostrando del _START_ al _END_ de _TOTAL_ registros");
define("dtsInfoEmpty", "No se han encontrado registros");
define("dtsInfoFiltered","(filtrado de _MAX_ registros totales)");
define("dtsSearch", "Buscar:");
define("dtsFirst", "Primera");
define("dtsPrevious", "Anterior");
define("dtsNext", "Siguiente");
define("dtsLast", "&Uacute;ltima");

//buttons
define("btnNew","Nuevo");
define("btnEdit","Editar");
define("btnDelete","Eliminar");
define("btnSave","Guardar");
define("btnCancel","Cancelar");
define("btnConfirm","Confirmar");
define("btnLogin","Entrar");
define("btnLogout","Salir");
define("btnWizard","Wizard de configuraci&oacute;n");
define("btnDownload", "Descargar");
define("btnUpload", "Subir");

//confirm
define("deleteConfirm", "Este elemento va a ser eliminado definitivamente. Est&aacute; seguro?");

//messages
define("messageSuccess","Datos guardados correctamente");
define("messageError","Ocurri&oacute; un error al guardar los datos");
define("messageLoginInfo","Si a&uacute;n no ha registrado su usuario debe ejecutar el ");
define("messageLoginError","Ocurri&oacute; un error al iniciar sesi&oacute;n, por favor verifique los datos ingresados");

/* ERRORS */

//textos de errores de excepciones de la api
define("CurlError", "ESLIP ERROR: Se requiere la extensión PHP CURL.");
define("SessionError", "ESLIP ERROR: No fué posible iniciar la sesión de PHP.");
define("ParametersErrorEslipDataInConstruct", "ESLIP ERROR: No se han pasado los parámetros necesarios. Datos de Eslip en Constructor.");
define("ParametersErrorIPInConstruct", "ESLIP ERROR: No se han pasado los parámetros necesarios. Proveedor de Identidad en Constructor.");
define("NoConfigFileError", "ESLIP ERROR: No se ha establecido el archivo XML de configuración.");

//openid
define("ParametersErrorURLInOpenIDConstruct", "ESLIP ERROR: No se han pasado los parámetros necesarios. URL en Constructor de OpenID.");
define("ParametersErrorIdentityInOpenIDDiscover", "ESLIP ERROR: No se han pasado los parámetros necesarios. Identity en Discover en OpenID.");
define("URIErrorInOpenIDDiscover", "ESLIP ERROR: Discover: No se pudo encontrar la OpenID endpoint URL.");
define("CancelInfo", "ESLIP INFO: El usuario ha cancelado la autenticación.");
define("NoIdResError", "ESLIP ERROR: Ha ocurrido un error en la verificación de la autenticación. Se obtuvo un modo distinto a id_res.");
define("DifferentsReturnURLError", "ESLIP ERROR: Ha ocurrido un error en la verificación de la autenticación. Las URLs de retorno deben coincidir.");
define("ImmediateRedirecting", "Imposible realizar la autenticación inmediata. Redirigiendo...");

//oauth
define("CurlResponseError", "ESLIP ERROR: No fue posible acceder a %s: Se ha devuelto un estado inesperado %s. Respuesta: %s");
define("OAuthVersionError", "ESLIP ERROR: %s no es una versión soportada del protocolo OAuth.");
define("NoAccessTokenError", "ESLIP ERROR: Imposible realizar llamada a la API ya que no se posee access token.");
define("RequestTokenDeniedError", "ESLIP ERROR: El Request Token fue denegado.");
define("NotReturnedAccessTokenError", "ESLIP ERROR: No se devolvió el access token o el secret.");
define("ExpiryTimeError", "ESLIP ERROR: El servidor OAuth devolvió un tipo no compatible de fecha de expiración de access token.");
define("AuthorizationErrorWithCodeError", "ESLIP ERROR: Ha ocurrido un error en la autorización. Código de error OAuth: %s.");
define("AuthorizationError", "ESLIP ERROR: Ha ocurrido un error en la autorización. No se ha devuelto el código de diálogo OAuth.");
define("ServerNotReturnAccessTokenError", "ESLIP ERROR: El servidor OAuth no devolvió el access token.");
define("ServerNotReturnAccessTokenWhiteCodeError", "ESLIP ERROR: No fue posible recuperar el access token: se devolvió el error: %s.");

?>