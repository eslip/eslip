<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<title>ESLIP Example</title>
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" type="text/css" href="example.css" />
</head>
<body class="results">
	<div class="container">
		<div class="header">
			<a href="http://eslip.com.ar" title="Sitio Web Oficial de ESLIP" target="_blank">
				<img id="eslip-logo" src="../eslip/backend/images/eslip-logo.png" alt="ESLIP" >
			</a>
		</div>
		<div class="content">
			<?php 
			/*
			* Acá el desarrollador va hacer el login correspondiente en su sitio 
			* con los datos que le enviamos por POST o tambien lo puede obtener de la SESSION
			*/
			?>
			<?php session_start(); ?>
			<h2>Datos generales:</h2>
			<div class="row">
				<label>Proveedor de identidad: </label>
				<span><?php echo $_POST['server'] ?></span>
			</div>
			<div class="row">
				<label>Origen:</label>
				<span><?php echo $_POST['referer'] ?></span>
			</div>
			<div class="row">
				<label>Estado:</label>
				<span class="<?php echo $_POST['state'] ?>"><?php echo $_POST['state']; ?></span>
			</div>
			<?php if($_POST['state'] == 'success'){ ?>
				<h2 class="hola">Hola <?php echo $_POST['user_identification'];?>!! Gracias por probar ESLIP!</h2> 
				<h2>Recursos obtenidos:</h2>
				<pre><?php print_r(json_decode($_POST['user'])); ?></pre>
			<?php }else{ ?>
				<h2>Datos del error</h2>
				<pre><?php echo $_POST['error']; ?></pre>
			<?php } ?>
		</div>
		<div class="footer">
			<span class="pull-left">[nicoburghi@gmail.com] Nicolás Burghi</span>
			<span class="pull-right">Martín Estigarribia [martinestiga@gmail.com]</span>
		</div>
	</div>
</body>
</html>