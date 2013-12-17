<?php

/**
*
* ESLIP OpenID
*
* Esta clase implementa una interfaz para autenticar un usuario a traves del protocolo
* OpenID. Soporta tanto las versiones 1.0 como la 2.0 del protocolo.
*
* @author Nicolás Burghi [nicoburghi@gmail.com]
* @author Martín Estigarribia [martinestiga@gmail.com]
*
* @package Eslip
*
*/

class eslip_openid extends Eslip
{

    /**
    * Arreglo con atributos que se desean requerir al proveedor de identidad
    *
    * @var array
    * @access private
    */

    private $required = array();

    /**
    * Arreglo con atributos que se desean requerir al proveedor de identidad pero serán opcionales
    *
    * @var array
    * @access private
    */

    private $optional = array();

    /**
    * URL a donde debe retornar el proveedor de identidad. Debe ser la URL del script  que procesa la 
    * respuesta del proveedor de identidad
    *
    * @var string
    * @access private
    */
        
    private $returnUrl;

    /**
    * URL del sitio que esta implementando el plugin para ser mostrado en el proveedor de identidad a la 
    * hora de pedir autenticacion
    *
    * @var string
    * @access private
    */

    private $trustRoot;

    /**
    * URL de la ubicacion del formulario donde el usuario debe insertar su OpenID
    *
    * @var string
    * @access private
    */

    private $openid_form_url = '';

    /**
    * Datos enviados al plugin via GET o POST
    *
    * @var array
    * @access private
    */

    private $data;

    /**
    * Modo que devuelve en la resupesta el proveedor de identidad
    *
    * @var string
    * @access private
    */

    private $mode;

    /**
    * Identificador OpenID que el usuario ingresa en el formulario
    *
    * @var string
    * @access private
    */

    private $identity;

    /**
    * Es el identificador reclamado.Este identificador es una URL que el usuario final dice poseer 
    * pero aún no fue verificada.
    *
    * @var string
    * @access private
    */

    private $claimed_id;

    /**
    * Indica si el servidor OpenID implementa la extención AX (Attribute Exchange) para luego 
    * enviarle los parametros correspondientes para obtener los datos deseados del usuario
    *
    * @var boolean
    * @access private
    */

    private $ax = FALSE;

    /**
    * Indica si el servidor OpenID implementa la extención SREG (Simple Registration) para luego 
    * enviarle los parametros correspondientes para obtener los datos deseados del usuario
    *
    * @var boolean
    * @access private
    */

    private $sreg = FALSE;

    /**
    * Contiene la URL del servidor OpenID. Extremo del protocolo obtenido de realizar el descubrimiento.
    *
    * @var string
    * @access private
    */

    private $server;

    /**
    * Version del servidor OpenID identificado en el proceso de descubrimiento
    *
    * @var integer
    * @access private
    */

    private $version;

    /**
    * Especifica si el servidor OpenID soporta Identifier Select
    *
    * @var boolean
    * @access private
    */

    private $identifier_select = FALSE;

    /**
    * Determina si la autenticacion se va a realizar utilizando el modo inmediato
    *
    * @var boolean
    * @access private
    */

    private $immediate = FALSE;

    /**
    * Arreglo para mapear nombres de parametros de la extencion AX a nombres de la extension SREG o viceversa
    *
    * @var array
    * @access private
    */

    static private $ax_to_sreg = array(
        'namePerson/friendly'     => 'nickname',
        'contact/email'           => 'email',
        'namePerson'              => 'fullname',
        'birthDate'               => 'dob',
        'person/gender'           => 'gender',
        'contact/postalCode/home' => 'postcode',
        'contact/country/home'    => 'country',
        'pref/language'           => 'language',
        'pref/timezone'           => 'timezone'
        );

    /**
    * Metodo constructor de la clase. Se inicializan las variables de configuración del plugin
    *
    * @access public
    * @param string $eslip_data Cadena codificada en base64 con datos internos del plugin que se deben mantener entre llamadas
    * @param array $identity_provider Arreglo con los datos de configuración del plugin
    * @param string $site_url URL del sitio que esta implementando el plugin
    * @throws EslipException Si el constructor no recibe el parametro $eslip_data
    * @throws EslipException Si el constructor no recibe el parametro $identity_provider
    * @throws EslipException Si el constructor no recibe el parametro $site_url
    */

    public function __construct($eslip_data = FALSE, $identity_provider = FALSE, $site_url = '')
    {
        parent::__construct();

        if (empty($eslip_data))
        {
            throw new EslipException(ParametersErrorEslipDataInConstruct);
        }

        if (empty($site_url))
        {
            throw new EslipException(ParametersErrorURLInOpenIDConstruct);
        }

        if (empty($identity_provider))
        {
            throw new EslipException(ParametersErrorIPInConstruct);
        }

        if (is_array($identity_provider))
        {
            $identity_provider = $this->Array2Object($identity_provider);
        }

        $this->returnUrl = str_replace('{ESLIP_DATA}', $eslip_data, (string)$identity_provider->redirectUri);

        $this->trustRoot = $this->prep_trust_url($site_url); // La url del sitio que esta implementando el plugin

        $this->openid_form_url = $this->my_http_build_query(array('return_url' => $this->returnUrl), '&', $identity_provider->formUrl, '?');

        $this->immediate = ((string)$identity_provider->immediate == '1') ? TRUE : FALSE ;

        $this->data = ($_SERVER['REQUEST_METHOD'] === 'POST') ? $_POST : $_GET;

        $this->mode = $this->GetRequestMode();

        $this->identity = $this->ProcessIdentity();

        $this->claimed_id = $this->identity;

        // The following two lines request email, full name, and a nickname
        // from the provider. Remove them if you don't need that data.
        $this->required = explode(',', $identity_provider->scopeRequired);

        $this->optional = explode(',', $identity_provider->scopeOptional);
    }

    /**
    * Prepara la URL del sitio que implementa el plugin para que pueda ser utilizada correctamente
    * como lo indica el protocolo OpenID 
    *
    * @access private
    * @param string $site_url URL que se quiere preparar
    * @return string URL preparada
    */

    private function prep_trust_url($site_url) // Esta la hice yo (Martin)
    {
        $parse_url = parse_url($site_url);

        if (! isset($parse_url['host']))
        {
            $url = explode('/', $parse_url['path']);
			$url = $url[0];
        }
        else
        {
            $url = $parse_url['host'];
        }
        if (! isset($parse_url['scheme']))
        {
            $url = 'http://'.$url;
        }
        else
        {
            $url = $parse_url['scheme'].'://'.$url;
        }

        return trim($url);
    }

    /**
    * Procesa y prepara el identificador OpenID que el usuario ingresa en el formulario o que ya se encuentra
    * en la sesion para que pueda ser utilizada correctamente como lo indica el protocolo OpenID 
    *
    * @access private
    * @return string identificador del usuario
    */

    private function ProcessIdentity() // La acomode bastante, sacandola de otra api openid, ahora se entiende mas
    {
        if (IsSet($_POST['openid_identifier']))
        {
            $value = trim(strtolower((String)$_POST['openid_identifier']));

            if (strpos($value, 'xri://') !== FALSE)
            {
                $value = substr($value, strlen('xri://')); //Si es un i-name de la manera xri:// se le quita el xri:// y se deja solo el i-name
            }
            else
            {
                // Prepend http:// if not present and append / if (probably) just a domain name, e.g: mytest.myopenid.com
                if ((strpos($value, 'http://') === FALSE) && (strpos($value, 'https://') === FALSE)) 
                {
                    // If no / in the url, then it should be a domain (right?) so append a /
                    if (strpos($value, '/') === FALSE) 
                    {
                        $value = $value . '/';
                    }
                    $value = 'http://'.$value;
                } 
                else
                {
                    // Starts already with http or https. Now if there's no / after the http(s):// then append one, because then it's probably a domain (right?)
                    if ((strpos($value, '/', strlen('http://')) === FALSE) || (strpos($value, '/', strlen('https://')) === FALSE)) 
                    {
                        $value = $value . '/';
                    } 
                }
            }

            $_SESSION['OPENID']['IDENTITY'] = $value; // Lo guardo en la session para armar la setup url en el caso de que immediate falle y no devuelva  setup url

        }
        else
        {
            if(IsSet($_SESSION['OPENID']['IDENTITY']) && !empty($this->mode)) 
            {
                // Se utiliza la identidad almacenada en la session solo cuando es la respuesta del OP
                $value = $_SESSION['OPENID']['IDENTITY'];
            }
            else
            {
                $value = FALSE;    
            }
        }

        return $value;
    }

    /**
    * Devuelve el parametro mode que se utiliza para distinguir que mensaje está siendo enviado o recibido. El mismo 
    * esta presente en todos los mensajes de OpenID
    *
    * @access private
    * @return string parametro mode
    */

    private function GetRequestMode()
    {
        return ( empty($this->data['openid_mode']) ) ? FALSE : $this->data['openid_mode'];
    }

    /**
    * Realiza una peticion utlizando la libreria cURL a una URL con opciones y parametros especificos.
    * La URL con la que se desea comunicar, y los parametros que se utilizan son recibidos por este metodo
    * por parametro.
    *
    * @access private
    * @param string $url URL con la que nos queremos comunicar
    * @param string $method Metodo HTTP que se va a utilizar para comunicarse con el destino
    * @param array $params Parametros que se van a enviar en la petición
    * @param boolean $update_claimed_id Determina si se debe actualizar la propiedad de la clase claimed_id
    * @param boolean $includeHeader Determina si el servidor con el que nos comunicamos debe incluir la cabezera HTTP en la respuesta
    * @return array Arreglo con los datos devueltos por la URL con la que nos comunicamos
    */

    private function request($url, $method='GET', $params=array(), $update_claimed_id = FALSE, $includeHeader = FALSE)
    {
        $params = http_build_query($params, '', '&');

        $config['url'] = $url . ($method == 'GET' && $params ? '?' . $params : '');

        $config['method'] = strtoupper($method);
        
        $config['curl_followlocation'] = TRUE;

        $config['curl_ssl_verifypeer'] = FALSE;
        
        $config['curl_header'] = $includeHeader;                    // Para NO incluir el header en el output
        $config['curl_returntransfer'] = TRUE;                      // Devolver el resultado de la transferencia como string del valor de curl_exec() en lugar de mostrarlo directamente. 
        
        $config['curl_httpheader'][] = 'Accept: application/xrds+xml, */*';

        $config['parameters'] = $params;

        $curl_response = $this->request_curl($config);
        $code = $curl_response['code'];

        if($method == 'HEAD' && $code == 405) 
        {   
            //El servidor no tiene HEAD entonces pido GET con la opcion de que devuelva las cabeceras
            $config['method'] = 'GET';
            $curl_response = $this->request_curl($config);
        }

        $response = $curl_response['response'];
        $headers = array();

        // Pongo el header en un array
        if($method == 'HEAD' || $method == 'GET') 
        {
            $header_response = $response;

            $header_response = substr($response, 0, strpos($response, "\r\n\r\n"));

            foreach(explode("\n", $header_response) as $header)
            {
                $pos = strpos($header,':');
                if ($pos !== false)
                {
                    $name = strtolower(trim(substr($header, 0, $pos)));
                    $headers[$name] = trim(substr($header, $pos+1));
                }
            }

            if($update_claimed_id) 
            {
                // Updating claimed_id in case of redirections.
                $effective_url = $curl_response['effective_url'];
                if($effective_url != $url)
                {
                    $this->identity = $this->claimed_id = $effective_url;
                }
            }
        }

        return array("content" => $response, "headers" => $headers);
    
    }

    /**
    * Función de ayuda usada para analizar las etiquetas HTML <meta> o <link> extraer información de ellas
    *
    * @access private
    * @param string $content Contenido HTML en el que se va a realizar la busqueda
    * @param string $tag Elemento HTML en el que se encuentra el Atributo que queremos saber su valor
    * @param string $attrName Atributo selector por el que vamos a buscar el elemento
    * @param string $attrValue Valor que debe tener el atributo selector
    * @param string $valueName Atributo del cual queremos saber su valor
    * @return string con el valor buscado si existe o FALSE si no se encuentra nada
    */

    private function getValueOfHtmlTag($content, $tag, $attrName, $attrValue, $valueName)
    {
        preg_match_all("#<{$tag}[^>]*$attrName=['\"].*?$attrValue.*?['\"][^>]*$valueName=['\"](.+?)['\"][^>]*/?>#i", $content, $matches1);
        preg_match_all("#<{$tag}[^>]*$valueName=['\"](.+?)['\"][^>]*$attrName=['\"].*?$attrValue.*?['\"][^>]*/?>#i", $content, $matches2);

        $result = array_merge($matches1[1], $matches2[1]);

        return empty($result) ? FALSE : $result[0];
    }

    /**
    * Realiza el descubrimiento Yadis 
    *
    * @param $url URL de identificacion OpenID que el usuario ingresa en el formulario
    * @return string Extremo del protocolo. URL del servidor OpenID
    * @throws EslipException Si no se proporciona el parametro $url
    * @throws EslipException  Si no se puede descubrir el servidor OpenID
    */

    private function discover($url)
    {
        if (!$url)
        {
            throw new EslipException(ParametersErrorIdentityInOpenIDDiscover);
        }

        // Use xri.net proxy to resolve i-name identities
        if (!preg_match('#^https?:#', $url)) 
        {
            $url = "https://xri.net/$url";
        }
		
        for ($i=0; $i < 5 ; $i++)
        { 
    		$response = $this->request($url, 'GET', array(), TRUE, TRUE);
            $content = $response['content'];
    		$headers = $response['headers'];
    		$location = $this->getValueOfHtmlTag($content, 'meta', 'http-equiv', 'X-XRDS-Location', 'content');
    		
    		// check redirection (only once, don't care about Endless redirection)
    		if (isset($headers['x-xrds-location']))
            {
    			$url = trim($headers['x-xrds-location']);
    			continue;
    		}
            else if ($location)
            {
    			$url = $location;
    			continue;
            }

            break;
        }

		// YADIS Discovery
		if (isset($headers['content-type']) && (strpos($headers['content-type'], 'application/xrds+xml') !== FALSE || strpos($headers['content-type'], 'text/xml') !== FALSE) )
		{
			// Apparently, some providers return XRDS documents as text/html.
			// While it is against the spec, allowing this here shouldn't break
			// compatibility with anything.
			// ---
			// Found an XRDS document, now let's find the server, and optionally delegate.

			preg_match_all('#<Service.*?>(.*?)</Service>#s', $content, $m);

			foreach($m[1] as $content) {
				$content = ' ' . $content; // The space is added, so that strpos doesn't return 0.

				// OpenID 2
				$ns = preg_quote('http://specs.openid.net/auth/2.0/', '#'); //Escapar caracteres con #
				if(preg_match('#<Type>\s*'.$ns.'(server|signon)\s*</Type>#s', $content, $type))
                {
					if ($type[1] == 'server')
                    {
                        $this->identifier_select = TRUE;   
                    }

                    // Server
					preg_match('#<URI.*?>(.*)</URI>#', $content, $server);
					if (empty($server))
                    {
						throw new EslipException(URIErrorInOpenIDDiscover);
					}
                    $server = $server[1];

                    // Delegate
                    preg_match('#<(Local|Canonical)ID>(.*)</\1ID>#', $content, $delegate);
                    if (isset($delegate[2]))
                    {
                        $this->identity = trim($delegate[2]);
                    }

					// Does the server advertise support for either AX or SREG?
					$this->ax   = (bool) strpos($content, '<Type>http://openid.net/srv/ax/1.0</Type>');
					$this->sreg = strpos($content, '<Type>http://openid.net/sreg/1.0</Type>') 
                                || strpos($content, '<Type>http://openid.net/extensions/sreg/1.1</Type>');

					$this->version = 2;

					$this->server = $server;

					return $server;
				}

				// OpenID 1.1
				$ns = preg_quote('http://openid.net/signon/1.1', '#');
				if (preg_match('#<Type>\s*'.$ns.'\s*</Type>#s', $content)) {

                    // Server
					preg_match('#<URI.*?>(.*)</URI>#', $content, $server);
					if (empty($server))
                    {
						throw new EslipException(URIErrorInOpenIDDiscover);
					}
                    $server = $server[1];

                    // Delegate
                    preg_match('#<.*?Delegate>(.*)</.*?Delegate>#', $content, $delegate);
                    if (isset($delegate[1]))
                    {
                        $this->identity = $delegate[1];
                    }

					// AX can be used only with OpenID 2.0, so checking only SREG
					$this->sreg = strpos($content, '<Type>http://openid.net/sreg/1.0</Type>')
							   || strpos($content, '<Type>http://openid.net/extensions/sreg/1.1</Type>');

					$this->version = 1;

					$this->server = $server;

					return $server;
				}
			}
		}
		
		// At this point, the YADIS Discovery has failed, so we'll switch
		// to openid2 HTML discovery, then fallback to openid 1.1 discovery.
		$server   = $this->getValueOfHtmlTag($content, 'link', 'rel', 'openid2.provider', 'href');
		$delegate = $this->getValueOfHtmlTag($content, 'link', 'rel', 'openid2.local_id', 'href');
		$this->version = 2;

		if (!$server) {
			// The same with openid 1.1
			$server   = $this->getValueOfHtmlTag($content, 'link', 'rel', 'openid.server', 'href');
			$delegate = $this->getValueOfHtmlTag($content, 'link', 'rel', 'openid.delegate', 'href');
			$this->version = 1;
		}

		if ($server) 
        {
			// We found an OpenID2 OP Endpoint
			if ($delegate) 
            {
				// We have also found an OP-Local ID.
				$this->identity = $delegate;
			}
			$this->server = $server;
			return $server;
		}
		
		throw new EslipException(URIErrorInOpenIDDiscover);
    }

    /**
    *  
    *
    * @return array 
    */

    private function sregParams()
    {
        $params = array();

        if ( !empty($this->required) || !empty($this->optional)) 
        {
            // We always use SREG 1.1, even if the server is advertising only support for 1.0.
            // That's because it's fully backwards compatibile with 1.0, and some providers
            // advertise 1.0 even if they accept only 1.1. One such provider is myopenid.com
            $params['openid.ns.sreg'] = 'http://openid.net/extensions/sreg/1.1';

            if ( !empty($this->required))
            {
                $params['openid.sreg.required'] = array();
                foreach ($this->required as $required)
                {
                    if (isset(self::$ax_to_sreg[$required]))
                    {
                        $params['openid.sreg.required'][] = self::$ax_to_sreg[$required];
                    }
                }
                $params['openid.sreg.required'] = implode(',', $params['openid.sreg.required']);
            }

            if ( !empty($this->optional))
            {
                $params['openid.sreg.optional'] = array();
                foreach ($this->optional as $optional)
                {
                    if (isset(self::$ax_to_sreg[$optional]))
                    {
                        $params['openid.sreg.optional'][] = self::$ax_to_sreg[$optional];
                    }
                }
                $params['openid.sreg.optional'] = implode(',', $params['openid.sreg.optional']);
            }
        }

        return $params;
    }

    /**
    *  
    *
    * @return array 
    */

    private function axParams()
    {
        $params = array();

        if ( !empty($this->required) || !empty($this->optional)) 
        {
            $params['openid.ns.ax'] = 'http://openid.net/srv/ax/1.0';
            $params['openid.ax.mode'] = 'fetch_request';

            $aliases  = array();
            $counts   = array();

            if ( !empty($this->required))
            {
                $required = array();
                foreach ($this->required as $alias => $field) 
                {
                    if (is_int($alias))
                    {
                        $alias = strtr($field, '/', '_'); //reemplaza la / por el _
                    }
                    $aliases[$alias] = 'http://axschema.org/' . $field;
                    if (empty($counts[$alias]))
                    {
                        $counts[$alias] = 0;
                    }
                    $counts[$alias] += 1;
                    $required[] = $alias;
                }

                $params['openid.ax.required'] = implode(',', $required);

            }
            
            if ( !empty($this->optional))
            {
                $optional = array();
                foreach ($this->optional as $alias => $field) 
                {
                    if (is_int($alias))
                    {
                        $alias = strtr($field, '/', '_'); //reemplaza la / por el _
                    }
                    $aliases[$alias] = 'http://axschema.org/' . $field;
                    if (empty($counts[$alias]))
                    {
                        $counts[$alias] = 0;
                    }
                    $counts[$alias] += 1;
                    $optional[] = $alias;
                }

                $params['openid.ax.if_available'] = implode(',', $optional);
            }

            foreach ($aliases as $alias => $ns) 
            {
                $params['openid.ax.type.' . $alias] = $ns;
            }

            foreach ($counts as $alias => $count) 
            {
                if ($count != 1)
                {
                    $params['openid.ax.count.' . $alias] = $count;
                }
            }

        }

        return $params;
    }

    /**
    *  
    *
    * @return string  
    */

    private function authUrl_v1()
    {
        $returnUrl = $this->returnUrl;

        // If we have an openid.delegate that is different from our claimed id,
        // we need to somehow preserve the claimed id between requests.
        // The simplest way is to just send it along with the return_to url.
        if($this->identity != $this->claimed_id)
        {
            $returnUrl .= $this->my_http_build_query(array('openid.claimed_id' => $this->claimed_id), '&', $returnUrl, '?');
        }

        $params = array(
            'openid.return_to'  => $returnUrl,
            'openid.mode'       => ($this->immediate) ? 'checkid_immediate' : 'checkid_setup',
            'openid.identity'   => $this->identity,
            'openid.trust_root' => $this->trustRoot,
        );

        $params += $this->sregParams();

        return($this->my_http_build_query($params, '&', $this->server, '?'));
    }

    private function authUrl_v2()
    {
        $params = array(
            'openid.ns'          => 'http://specs.openid.net/auth/2.0',
            'openid.mode'        => ($this->immediate) ? 'checkid_immediate' : 'checkid_setup',
            'openid.return_to'   => $this->returnUrl,
            'openid.realm'       => $this->trustRoot
        );

        if ($this->ax) 
        {
            $params += $this->axParams();
        }
        if ($this->sreg) 
        {
            $params += $this->sregParams();
        }
        if (!$this->ax && !$this->sreg) 
        {
            // If OP doesn't advertise either SREG, nor AX, let's send them both
            // in worst case we don't get anything in return.
            $params += $this->axParams() + $this->sregParams();
        }

        if ($this->identifier_select)
        {
            $params['openid.identity']   = 'http://specs.openid.net/auth/2.0/identifier_select';
            $params['openid.claimed_id'] = 'http://specs.openid.net/auth/2.0/identifier_select';
        }
        else
        {
            $params['openid.identity']   = $this->identity;
            $params['openid.claimed_id'] = $this->claimed_id;
        }

        return($this->my_http_build_query($params, '&', $this->server, '?'));
    }

    public function Process()
    {
        if( empty($this->mode) )
        {
            if( $this->identity )
            {   
                $this->discover($this->identity);

                header( 'Location: ' . $this->authUrl() );
                $this->exit = TRUE;
                return(TRUE);
            }
            else
            {
                header( 'Location: ' . $this->openid_form_url );
                $this->exit = TRUE;
                return(TRUE);
            }
        }
        else
        {

            if ($this->mode == 'cancel')
            {
                throw new EslipException(CancelInfo);
            }

            if(isset($this->data['openid_user_setup_url']) || $this->mode == 'setup_needed')
            {

                $this->immediate = FALSE;

                if (isset($this->data['openid_user_setup_url']))
                {
                    $setup_url = $this->data['openid_user_setup_url'];
                }
                else
                {
                    $this->discover($this->identity);
                    
                    $setup_url = $this->authUrl();

                    // Me falta lo que el usuario puso en el input!! :O -> ya lo arregle, lo guardo en la session
                    // throw new EslipException('ESLIP ERROR');
                }

                header( "Refresh:2; Url=".$setup_url ); 
                echo ImmediateRedirecting;

                // header( 'Location: ' . $setup_url );

                $this->exit = TRUE;
                return(TRUE);
            }

            if($this->mode != 'id_res') 
            {
                throw new EslipException(NoIdResError);
            }

            $this->validate();
        }
    }

    /**
     * Returns authentication url. Usually, you want to redirect your user to it.
     * @return String The authentication url.
     * @param String $select_identifier Whether to request OP to select identity for an user in OpenID 2. Does not affect OpenID 1.
     * @throws ErrorException
     */
    private function authUrl()
    {
        if ($this->version == 2)
        {
            return $this->authUrl_v2();
        }
        return $this->authUrl_v1();
    }

    /**
     * Performs OpenID verification with the OP.
     * @return Bool Whether the verification was successful.
     * @throws ErrorException
     */
    private function validate()
    {
        $this->claimed_id = isset($this->data['openid_claimed_id']) ? $this->data['openid_claimed_id'] : $this->data['openid_identity'];

        $params = array(
            'openid.assoc_handle' => $this->data['openid_assoc_handle'],
            'openid.signed'       => $this->data['openid_signed'],
            'openid.sig'          => $this->data['openid_sig'],
        );

        if (isset($this->data['openid_ns'])) 
        {
            // We're dealing with an OpenID 2.0 server, so let's set an ns
            // Even though we should know location of the endpoint,
            // we still need to verify it by discovery, so $server is not set here
            $params['openid.ns'] = 'http://specs.openid.net/auth/2.0';
        }
        elseif (isset($this->data['openid_claimed_id']) && $this->data['openid_claimed_id'] != $this->data['openid_identity'])
        {
            // If it's an OpenID 1 provider, and we've got claimed_id,
            // we have to append it to the returnUrl, like authUrl_v1 does.
            $this->returnUrl .= $this->my_http_build_query(array('openid.claimed_id' => $this->claimed_id), '&', $this->returnUrl, '?');
        }

        if ($this->data['openid_return_to'] != $this->returnUrl) {
            // The return_to url must match the url of current request.
            // I'm assuing that noone will set the returnUrl to something that doesn't make sense.
            throw new EslipException(DifferentsReturnURLError);
        }

        $server = $this->discover($this->claimed_id);

        foreach (explode(',', $this->data['openid_signed']) as $item)
        {
            // Checking whether magic_quotes_gpc is turned on, because
            // the function may fail if it is. For example, when fetching
            // AX namePerson, it might containg an apostrophe, which will be escaped.
            // In such case, validation would fail, since we'd send different data than OP
            // wants to verify. stripslashes() should solve that problem, but we can't
            // use it when magic_quotes is off.
            $value = $this->data['openid_' . str_replace('.','_',$item)];
            $params['openid.' . $item] = function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc() ? stripslashes($value) : $value;
        }

        $params['openid.mode'] = 'check_authentication';

        $response = $this->request($server, 'POST', $params);

        return preg_match('/is_valid\s*:\s*true/i', $response['content']);
    }

    private function getAxAttributes()
    {
        $alias = NULL;

        if (isset($this->data['openid_ns_ax']) && $this->data['openid_ns_ax'] != 'http://openid.net/srv/ax/1.0')
        { 
            // It's the most likely case, so we'll check it before
            $alias = 'ax';
        }
        else
        {
            // 'ax' prefix is either undefined, or points to another extension,
            // so we search for another prefix
            foreach ($this->data as $key => $val)
            {
                if (substr($key, 0, strlen('openid_ns_')) == 'openid_ns_' && $val == 'http://openid.net/srv/ax/1.0')
                {
                    $alias = substr($key, strlen('openid_ns_'));
                    break;
                }
            }
        }

        $attributes = array();

        if ($alias)
        {
            foreach (explode(',', $this->data['openid_signed']) as $key)
            {
                $keyMatch = $alias . '.value.';           

                if (substr($key, 0, strlen($keyMatch)) == $keyMatch)
                {
                    $key = substr($key, strlen($keyMatch));

                    if (isset($this->data['openid_' . $alias . '_value_' . $key]))
                    {
                        $value = $this->data['openid_' . $alias . '_value_' . $key];
                        $attributes[$key] = $value;
                    }
                }
            }    
        }
        
        return $attributes;
    }

    private function getSregAttributes()
    {
        $attributes = array();
        $sreg_to_ax = array_flip(self::$ax_to_sreg); // array_flip — Intercambia todas las keys con sus valores asociados en un array
        foreach (explode(',', $this->data['openid_signed']) as $key)
        {
            $keyMatch = 'sreg.';

            if (substr($key, 0, strlen($keyMatch)) == $keyMatch) 
            {
                $key = substr($key, strlen($keyMatch));

                if (isset($sreg_to_ax[$key])) 
                {
                    $attributes[$sreg_to_ax[$key]] = $this->data['openid_sreg_' . $key];    
                }

            }           
        }

        return $attributes;
    }

    /**
     * Gets AX/SREG attributes provided by OP. should be used only after successful validaton.
     * Note that it does not guarantee that any of the required/optional parameters will be present,
     * or that there will be no other attributes besides those specified.
     * In other words. OP may provide whatever information it wants to.
     *     * SREG names will be mapped to AX names.
     *     * @return Array Array of attributes with keys being the AX schema names, e.g. 'contact/email'
     * @see http://www.axschema.org/types/
     */
    public function getAttributes()
    {
        $attributes = array();

        $attributes['id'] = $this->data['openid_claimed_id'];

        if (isset($this->data['openid_ns']) && $this->data['openid_ns'] == 'http://specs.openid.net/auth/2.0')
        { 
            // OpenID 2.0
            // We search for both AX and SREG attributes, with AX taking precedence.
            $attributes += $this->getAxAttributes() + $this->getSregAttributes();
        }
        else
        {
            // OpenID 1.0
            $attributes += $this->getSregAttributes();
        }

        return $attributes; 
    }
}