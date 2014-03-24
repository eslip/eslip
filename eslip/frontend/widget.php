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

$identity_providers = $eslip->get_active_identity_providers();

?>

<?php foreach ($identity_providers as $ip): ?>

	<a href='javascript:;' style='<?php if (isset($ip->styles->logo_url)): ?>background-image: url("<?php echo $ip->styles->logo_url ?>");<?php endif; ?> <?php if(isset($ip->styles->backgroundColor)): ?> background-color:<?php echo $ip->styles->backgroundColor; ?>; <?php endif; ?> <?php if(isset($ip->styles->textColor)): ?> color:<?php echo $ip->styles->textColor; ?>; <?php endif; ?>' class='button glow <?php echo $ip->id ?>' onclick='clickLogin("<?php echo $ip->id ?>")' ><?php echo $ip->label ?></a>

<?php endforeach; ?>