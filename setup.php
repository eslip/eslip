<?php
include("eslip_api.php");
$runWizard = (bool)(String)$xmlApi->getElementValue("runWizard","configuration");
$eslip_langs = $xmlApi->getElementList("language");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>ESLIP Setup</title>

	<link type="text/css" rel="stylesheet" href="css/smoothness/jquery-ui-1.10.3.custom.min.css">
	<link type="text/css" rel="stylesheet" href="css/jquery.jWizard.css">
	<link type="text/css" rel="stylesheet" href="css/style.css">
	<link type="text/css" rel="stylesheet" href="css/onoff_switch.css">

	<script type="text/javascript" src="js/jquery/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="js/jquery/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="js/jquery/jquery.jWizard.js"></script>
	<script type="text/javascript" src="js/jquery/jquery.validate.js"></script>
	<script type="text/javascript" src="js/jquery/onoff_switch.js"></script>
	
	<script>
		var $serviceUrl = "<?php echo getServiceUrl(); ?>";
		var $adminUrl = "<?php echo getAdminUrl(); ?>";
		var $runWizard = "<?php echo $runWizard; ?>";
		$(function() {
		
			function loadContent(){
				$.ajax($serviceUrl,{
					data: {section: "wizard", action: "content", lang: $("#selectLang").val()},
					dataType: 'html',
					type: 'POST',
					async: true
				}).done(function(data){
						$("#wizardContainer").html(data);
				});
			}
			
			// if ($runWizard){
				$( "#dialog-lang" ).dialog({
					resizable: false,
					height:200,
					modal: true,
					buttons: {
						/* "Cancelar": function() {
							$( this ).dialog( "close" );
						}, */
						"Seleccionar": function() {
							loadContent();
							$( this ).dialog( "close" );
						}
					}
				});
			/* }else{
				window.location = $adminUrl;
			} */
		});
	</script>

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
		<select id="selectLang">
			<?php
				foreach( $eslip_langs as $lang ){
			?>
					<option value="<?php echo $lang->code;?>" <?php if ($lang->code == $selectedLang) echo "selected";?>><?php echo $lang->name;?></option>
			<?php
				}
			?>
		</select>
	</div>
	
</body>

</html>
