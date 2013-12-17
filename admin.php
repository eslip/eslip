<?php
include_once('eslip_api.php');
session_start();

$isAuthenticated = ( isset($_SESSION['usuario']) && ! empty($_SESSION['usuario']) );
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>ESLIP Admin</title>

	<link type="text/css" rel="stylesheet" href="css/smoothness/jquery-ui-1.10.3.custom.min.css">
	<link type="text/css" rel="stylesheet" href="css/style.css?<?php echo time(); ?>">
	
	<link type="text/css" rel="stylesheet" href="js/DataTables-1.9.4/media/css/demo_table.css">
	<link type="text/css" rel="stylesheet" href="js/DataTables-1.9.4/media/css/demo_table_jui.css">
	<link type="text/css" rel="stylesheet" href="js/DataTables-1.9.4/extras/TableTools/media/css/TableTools.css">
	
	<script type="text/javascript" src="js/jquery/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="js/jquery/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="js/jquery/jquery.validate.js"></script>
	<script type="text/javascript" src="js/eslip_idproviders_list.js"></script>
	
	<script type="text/javascript" language="javascript" src="js/DataTables-1.9.4/media/js/jquery.dataTables.js"></script>
	<script type="text/javascript" language="javascript" src="js/DataTables-1.9.4/extras/TableTools/media/js/TableTools.min.js"></script>
	
	<script>
		var $serviceUrl = "<?php echo getAdminServiceUrl(); ?>";
		$(function() {
			
			$( "input[type=button], button" ).button();
			
			$("#idProviders").click(function(){
				$(".ui-menu-item").removeClass("ui-state-highlight");
				$(this).parents(".ui-menu-item").addClass("ui-state-highlight");
				$("#content").load($serviceUrl,{section: "admin", action: "idProviders"});
			});
			
			$("#configUser").click(function(){
				$(".ui-menu-item").removeClass("ui-state-highlight");
				$(this).parents(".ui-menu-item").addClass("ui-state-highlight");
				$("#content").load($serviceUrl,{section: "admin", action: "configUser"});
			});
			
			$("#generalConfig").click(function(){
				$(".ui-menu-item").removeClass("ui-state-highlight");
				$(this).parents(".ui-menu-item").addClass("ui-state-highlight");
				$("#content").load($serviceUrl,{section: "admin", action: "generalConfig"});
			});
			
			$("#login").click(function(){
				$data = "section=admin&action=login&"+$("#loginForm").serialize();
				
				$.ajax($serviceUrl,{
						data: $data,
						dataType: 'html',
						type: 'POST',
						async: true
					}).done(function(data){
							window.location.reload(true);
					});
				
				//$("#content").load($serviceUrl,$data,function(){ /*window.location.reload(true);*/});
			});
			
			$("#logout a").click(function(){
				$data = "section=admin&action=logout";
				
				$.ajax($serviceUrl,{
						data: $data,
						dataType: 'html',
						type: 'POST',
						async: true
					}).done(function(data){
							window.location.reload(true);
					});
				
				//$("#content").load($serviceUrl,$data,function(){ /*window.location.reload(true);*/});
			});
			
			$("#loginForm").find('input').keypress(function(e) {
				if(e.which == 13) {
					$(this).blur();
					$('#login').focus().click();
				}
			});
			
			$("#generalConfig").click();
		});

	</script>

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
									<div class="info" style=""><?php echo messageLoginInfo; ?><a href="setup.php" id="wizard"><?php echo btnWizard; ?><a/></div>
								</div>
						
							</div>
						
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
   
   <div id="dialog-confirm" class="adminDialog" style="display:none;">
		<div class="reng">
			<span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
			<?php echo deleteConfirm; ?>
		</div>
	</div>
</body>

</html>
