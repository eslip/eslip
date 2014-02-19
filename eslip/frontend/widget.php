<?php

/**
* Aqui se crea el widget que se mostrara en el sitio web al usuario con los iconos de los 
* proveedores de identidad configurados en el administrador del plugin.
* 
* @author Nicolás Burghi [nicoburghi@gmail.com]
* @author Martín Estigarribia [martinestiga@gmail.com]
* @license http://opensource.org/licenses/mit-license.php The MIT License (MIT)
* @package Eslip
*/

include_once("../eslip_api.php");

$result = $xmlApi->getElementListByFieldValue("active", "1", "identityProvider");

?>

<?php foreach ($result as $ip): ?>

	<a href='javascript:;' class='zocial <?php echo (string)$ip->attributes()?>' style='font-size: 14px;' onclick='clickLogin("<?php echo (string)$ip->attributes()?>")' ><?php echo (string)$ip->label?></a>

<?php endforeach; ?>