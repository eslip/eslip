<?php
include_once('../eslip_api.php');
session_start();

$isAuthenticated = ( isset($_SESSION['usuario']) && ! empty($_SESSION['usuario']) );

$_SESSION['referrer'] = currentPageUrl();

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>ESLIP Admin</title>

	<link type="text/css" rel="stylesheet" href="css/smoothness/jquery-ui-1.10.3.custom.min.css">
	<link type="text/css" rel="stylesheet" href="css/style.css">
	<link type="text/css" rel="stylesheet" href="css/onoff_switch.css">
	<link type="text/css" rel="stylesheet" href="css/demo_table.css">
	<link type="text/css" rel="stylesheet" href="css/demo_table_jui.css">
	<link type="text/css" rel="stylesheet" href="css/TableTools.css">
	<link type="text/css" rel="stylesheet" href="css/vtip.css">
	<link type="text/css" rel="stylesheet" href="css/jquery.minicolors.css">
	<link type="text/css" rel="stylesheet" href="../frontend/eslip_plugin.css">
	
	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="js/jquery.loadTemplate-1.3.2.min.js"></script>
	
	<script type="text/javascript" src="js/jquery.validate.js"></script>
	<script type="text/javascript" src="js/onoff_switch.js"></script>
	<script type="text/javascript" src="js/eslip_idproviders_list.js"></script>
	<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="js/TableTools.min.js"></script>
	<script type="text/javascript" src="js/vtip.js"></script>
	<script type="text/javascript" src="js/jquery.minicolors.min.js"></script>
	<script type="text/javascript" src="js/eslip_common.js"></script>
	<script type="text/javascript" src="js/eslip_admin.js"></script>

</head>

<body>

	<table class="pageContainer admin" align="center" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td> 
				<div id="adminContainer" class="ui-widget">
				
					<div id="adminHeader" class="adminHeader ui-widget-header ui-corner-top ui-helper-clearfix">
						<h2 class="title"><?php echo adminTitle; ?></h2>
						<?php if( $isAuthenticated ){ ?>
							<span id="logout" class="logout" style="float:right;"><a href="javascript:;"><?php echo btnLogout; ?></a></span>
						<?php } ?>
					</div>
					
					<div id="adminContent" class="adminContent ui-widget-content ui-helper-clearfix">

						<?php if( ! $isAuthenticated ){ ?>
							<?php header( 'Location: login.php' ); ?>
						<?php }else{ ?>
					
							<div id="menu" class="menu">
								<ol class="ui-menu ui-widget ui-widget-content ui-corner-all">
									<li class="ui-menu-item" role="presentation">
										<a href="javascript:void(0);" id="generalConfig" class="menuitem ui-corner-all" tabindex="-1" role="menuitem"><?php echo GeneralConfigs; ?></a>
									</li>
									<li class="ui-menu-item" role="presentation">
										<a href="javascript:void(0);" id="idProviders" class="menuitem ui-corner-all" tabindex="-1" role="menuitem"><?php echo IdProviders; ?></a>
									</li>
									<li class="ui-menu-item" role="presentation">
										<a href="javascript:void(0);" id="configUser" class="menuItem ui-corner-all" tabindex="-1"  role="menuitem"><?php echo ConfigUser; ?></a>
									</li>
									<li class="ui-menu-item" role="presentation">
										<a href="javascript:void(0);" id="languagesConfig" class="menuItem ui-corner-all" tabindex="-1"  role="menuitem"><?php echo LanguagesConfig; ?></a>
									</li>
									<li class="ui-menu-item" role="presentation">
										<a href="javascript:void(0);" id="loginWidget" class="menuItem ui-corner-all" tabindex="-1"  role="menuitem"><?php echo LoginWidget; ?></a>
									</li>
									<li class="ui-menu-item" role="presentation">
										<a href="javascript:void(0);" id="idProvidersButtons" class="menuItem ui-corner-all" tabindex="-1"  role="menuitem"><?php echo IdProvidersButtons; ?></a>
									</li>
								</ol>
							</div>
							
							<div id="content" class="content">
								
							
							</div>
						
						<?php } ?>
					</div>
				
					<div id="adminFooter" class="adminFooter ui-widget-header ui-corner-bottom">
					</div>
				
				</div>
			</td>
		</tr>
	</table>
	
	<div id="dialog-edit" class="adminDialog" style="display:none;">
	</div>

	<div id="dialog-edit-id" class="adminDialog" style="display:none;">
	</div>
   
	<div id="dialog-confirm" class="adminDialog" style="display:none;">
		<div class="reng">
			<span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
			<?php echo deleteConfirm; ?>
		</div>
	</div>

	<div id="dialog-confirm-id" class="adminDialog" style="display:none;">
		<div class="reng">
			<span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
			<?php echo deleteConfirm; ?>
		</div>
	</div>
</body>

</html>
