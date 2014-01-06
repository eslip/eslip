<?php

/**
* Aquí se realiza el procesamiento de las llamadas del protocolo correspondiente al proveedor
* de identidad. Si todo el proceso se realiza satisfactoriamente se redirige a un script el 
* cual postea los datos obtenidos del proveedor de identidad a la URL configurada.
* 
* @author Nicolás Burghi [nicoburghi@gmail.com]
* @author Martín Estigarribia [martinestiga@gmail.com]
*
* @package Eslip
*/

include_once('eslip_api.php');
include_once('eslip_classes.php');

if (IsSet($_GET['eslip_data']))
{
	$eslip_data = $_GET['eslip_data'];
	$aux = json_decode(base64url_decode($eslip_data));
	$referer = $aux->referer;
	$server = $aux->server;
}
else
{
	$referer = (IsSet($_GET['referer'])) ? $_GET['referer'] : '';
	$server = (IsSet($_GET['server'])) ? $_GET['server'] : '';
}

$eslip_data = array('server' => $server, 'referer' => $referer );
$eslip_data = base64url_encode(json_encode($eslip_data));

$eslip_settings = $xmlApi->getElementValue("configuration");

$identity_provider_data = $xmlApi->getElementById("identityProvider", $server);

try
{

	if ( $server != 'openid' )
	{

		$client = new eslip_oauth($eslip_data, $identity_provider_data);

		$client->Process();
		
		if($client->ExitProgram())
		{
			exit;
		}

		$user = $client->GetUserData();

		$client->StoreUserDataInSession($user, 'oauth');

		$return = array('user' => $user,
						'user_identification' => $client->GetUserId($user),
						'server' => $server, 
						'referer' => $referer,
						'state' => 'success',
						'client_callback_url' => (string)$eslip_settings->callbackUrl);

		$return = base64url_encode(json_encode($return));

		$callback_url_preocess = str_replace('{DATA}', $return, (string)$eslip_settings->pluginUrl."eslip_callback_process.php?data={DATA}");
	}
	else
	{
		
		$client = new eslip_openid($eslip_data, $identity_provider_data, (string)$eslip_settings->siteUrl);

		$client->Process();

		if($client->ExitProgram())
		{
			exit;
		}

		$user = $client->getAttributes();
		
		$client->StoreUserDataInSession($user, 'openid');

		$return = array('user' => $user,
						'user_identification' => $user['id'],
						'server' => $server, 
						'referer' => $referer,
						'state' => 'success',
						'client_callback_url' => (string)$eslip_settings->callbackUrl);

		$return = base64url_encode(json_encode($return));

		$callback_url_preocess = str_replace('{DATA}', $return, (string)$eslip_settings->pluginUrl."eslip_callback_process.php?data={DATA}");

	}
}
catch (EslipException $e)
{
	$return = array('error' => $e->getMessage(), 
					'server' => $server, 
					'referer' => $referer,
					'state' => 'error',
					'client_callback_url' => (string)$eslip_settings->callbackUrl);

	$return = base64url_encode(json_encode($return));

	$callback_url_preocess = str_replace('{DATA}', $return, (string)$eslip_settings->pluginUrl."eslip_callback_process.php?data={DATA}");
}
?>
<script>
	window.close();
	window.opener.location.href = "<?php echo $callback_url_preocess; ?>";
</script>