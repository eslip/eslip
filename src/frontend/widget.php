<?php

include_once("../eslip_api.php");

$result = $xmlApi->getElementListByFieldValue("active", "1", "identityProvider");

?>

<?php foreach ($result as $ip): ?>

	<a href='javascript:;' class='zocial <?php echo (string)$ip->attributes()?>' style='font-size: 14px;' onclick='clickLogin("<?php echo (string)$ip->attributes()?>")' ><?=$ip->label?></a>

<?php endforeach; ?>