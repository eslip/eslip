<?php
include("php/funciones.php");
include("i18n/".getLang().".php");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Smart Wizard 2 - Basic Example  - a javascript jQuery wizard control plugin</title>

<link type="text/css" rel="stylesheet" href="css/smart_wizard.css">
<link type="text/css" rel="stylesheet" href="css/style.css">
<script type="text/javascript" src="js/jquery/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.smartWizard-2.0.min.js"></script>

<script type="text/javascript">
    $(function(){
		
		var serviceUrl = "<?php echo getServiceUrl(); ?>";
		var $wizard = $('#wizard');
		var $wizardConfig = {
			contentURL: serviceUrl+"?action=wizard",
			transitionEffect:'slideleft',
			onLeaveStep:leaveAStepCallback,
			onFinish:onFinishCallback
		};
    	// Smart Wizard 	
  		$wizard.smartWizard($wizardConfig);
      
		function onFinishCallback(){
			alert('Finish Called');
		}
		
		function leaveAStepCallback(obj){
			var step = obj.attr('rel');
			var continuar = false;
			$.ajax(serviceUrl,{
				data: {action: "service", step_number: step, },
				dataType: 'html',
				type: 'POST',
				async: true
			}).done(function(html){
				setTimeout(function(){
					var opt = $.extend({}, $wizardConfig, {selected: (step)});
					console.dir(opt);
					$wizard.smartWizard(opt);
				}, 3000); 
			});
			
			console.info('Leave A Step Called: '+step);
			return continuar;
		}
	});
</script>

</head>
<body>

<table align="center" border="0" cellpadding="0" cellspacing="0">
<tr><td> 
<!-- Smart Wizard -->
  		<div id="wizard" class="swMain">
  			<ul>
  				<li><a href="#step-1">
                <label class="stepNumber">1</label>
                <span class="stepDesc">
                   <?php echo BDTitulo; ?><br />
                   <span><?php echo BDSubTitulo; ?></span>
                </span>
            </a></li>
  				<li><a href="#step-2">
                <label class="stepNumber">2</label>
                <span class="stepDesc">
                   <?php echo AppsTitulo; ?><br />
                   <span><?php echo AppsSubTitulo; ?></span>
                </span>
            </a></li>
  				<li><a href="#step-3">
                <label class="stepNumber">3</label>
                <span class="stepDesc">
                   Step 3<br />
                   <span>Step 3 description</span>
                </span>                   
             </a></li>
  				<li><a href="#step-4">
                <label class="stepNumber">4</label>
                <span class="stepDesc">
                   Step 4<br />
                   <span>Step 4 description</span>
                </span>                   
            </a></li>
  			</ul>
			
  			<div id="step-1"></div>
  			<div id="step-2"></div>           
  			<div id="step-3"></div>
  			<div id="step-4"></div>
  		</div>
<!-- End SmartWizard Content -->  		
 		
</td></tr>
</table>
    		
</body>
</html>
