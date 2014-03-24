<?php
include_once("../eslip_api.php");
session_start();

$isAuthenticated = ( isset($_SESSION['usuario']) && ! empty($_SESSION['usuario']) );

$runWizard = (bool)(string)$eslip->configuration->runWizard;

$_SESSION['referrer'] = currentPageUrl();

// si (no esta autenticado y run wizard es verdadero)
//		mostrar wizard original
//	si no (no esta autenticado y run wizard es false)
//		redirigir a login
//	si no (si esta autenticado y run wizard es false)
//		correr wizard nuevo con menos opciones
//  ! $isAuthenticated && $runWizard

// si no esta autenticado y run wizard es falso
if ( ! $isAuthenticated && ! $runWizard){
	header( 'Location: login.php' );
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>ESLIP Setup</title>

	<link type="text/css" rel="stylesheet" href="css/smoothness/jquery-ui-1.10.3.custom.min.css">
	<link type="text/css" rel="stylesheet" href="css/jquery.jWizard.css">
	<link type="text/css" rel="stylesheet" href="css/style.css">
	<link type="text/css" rel="stylesheet" href="css/onoff_switch.css">
	<link type="text/css" rel="stylesheet" href="css/vtip.css">

	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="js/jquery.loadTemplate-1.3.2.min.js"></script>
	
	<script type="text/javascript" src="js/jquery.jWizard.js"></script>
	<script type="text/javascript" src="js/jquery.validate.js"></script>
	<script type="text/javascript" src="js/onoff_switch.js"></script>
	<script type="text/javascript" src="js/vtip.js"></script>
	<script type="text/javascript" src="js/eslip_common.js"></script>
	<script type="text/javascript" src="js/eslip_setup.js"></script>

</head>

<body>

	<table class="pageContainer" align="center" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td> 
				<div id="wizardContainer">
			
				</div>
			</td>
		</tr>
	</table>
    
	<div id="dialog-lang" title="<?php echo SelectLangTitle; ?>" style="display:none;">
		<div id="selectLangContainer"></div>
		
	</div>
	
</body>

</html>
