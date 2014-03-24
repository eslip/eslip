<?php
include_once('../eslip_api.php');
session_start();

$isAuthenticated = ( isset($_SESSION['usuario']) && ! empty($_SESSION['usuario']) );

$runWizard = (bool)(string)$eslip->configuration->runWizard;

/* (Assuming session already started) */
if(isset($_SESSION['referrer'])){
    // Get existing referrer
    $redirect = $_SESSION['referrer'];

} elseif(isset($_SERVER['HTTP_REFERER'])){
    // Use given referrer
    $redirect = $_SERVER['HTTP_REFERER'];

} else {
    $redirect = "admin.php";
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>ESLIP Login</title>

	<link type="text/css" rel="stylesheet" href="css/smoothness/jquery-ui-1.10.3.custom.min.css">
	<link type="text/css" rel="stylesheet" href="css/style.css">
	
	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
	
	<script type="text/javascript" src="js/jquery.validate.js"></script>
	<script type="text/javascript" src="js/eslip_common.js"></script>
	<script type="text/javascript" src="js/eslip_admin.js"></script>

</head>

<body>

	<table class="pageContainer admin" align="center" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td> 
				<div id="adminContainer" class="ui-widget">
				
					<div id="adminHeader" class="adminHeader ui-widget-header ui-corner-top ui-helper-clearfix">
						<h2 class="title"><?php echo Login; ?></h2>
					</div>
					
					<div id="adminContent" class="adminContent ui-widget-content ui-helper-clearfix">

						<?php if( ! $isAuthenticated ){ ?>
						
							<div id="content" class="content">
							
								<div class="block">
									<h3 class="stepTitle"><?php echo Login; ?></h3>
									<br/>
									<form id="loginForm" action="" method="POST">
										<div class="reng">
											<label for="adminUser"><?php echo AdminUser; ?>:</label>
											<input type="text" id="adminUser" name="adminUser" value="" />
										</div>
										<div class="reng">
											<label for="adminPass"><?php echo AdminPass; ?>:</label>
											<input type="password" id="adminPass" name="adminPass" value="" />
										</div>
									</form>
									<div class="adminToolBar" style="float: right; margin-top: 10px;">
										<input type="button" id="login" value="<?php echo btnLogin; ?>" />
									</div>
									<?php if ($runWizard){ ?>
									<div class="infoMessage" style=""><?php echo messageLoginInfo; ?><a href="setup.php" id="wizard"><?php echo btnWizard; ?></a></div>
									<?php } ?>
									<div class="errorMessage" style="display:none;"><?php echo messageLoginError; ?></div>
								</div>
						
							</div>
						
						<?php }else{ ?>
					
							<?php header( 'Location: '.$redirect ); ?>
						
						<?php } ?>
					</div>
				
					<div id="adminFooter" class="adminFooter ui-widget-header ui-corner-bottom">
					</div>
				
				</div>
			</td>
		</tr>
	</table>
	
</body>

</html>
