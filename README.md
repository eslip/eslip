[ESLIP](http://eslip.com.ar/)
=============================

Easy Social Login Integration Plugin (ESLIP), en español Plugin Simple para Integración de Social Login, es un plugin PHP que permite integrar fácilmente Social Login a un sitio web, tal como lo indica su nombre. El principal objetivo de esta herramienta es permitir a los desarrolladores web incorporar de forma sencilla un widget de inicio de sesión que ofrezca la posibilidad de que los usuarios se autentiquen en un sitio web por medio de las cuentas de redes sociales y servicios en línea más populares, como por ejemplo Facebook, Twitter, Google, entre otros.

Si bien existen en la web diferentes tipos de plugins o librerías que facilitan la inclusión de Social Login en un sitio web, ESLIP se diferencia del resto por su premisa de sencillez de integración para desarrolladores. ESLIP está preparado para ser configurado e integrado en simples pasos ya que cuenta con un wizard de configuración que guía al desarrollador para que en pocos pasos pueda configurar su widget de Social Login. A su vez cuenta con un módulo de administración desde el cual es posible agregar y quitar proveedores de identidad entre muchas más opciones.

Gracias a la investigación llevada a cabo sobre Social Login y los principales protocolos que involucra, y al consecuente descubrimiento de los beneficios que esta tecnología de identificación tiene, las dificultades existentes para su incorporación en una aplicación o sitio web y la carencia de una herramienta gratuita y de código abierto que permita integrarla sencilla e intuitivamente, se comenzó a desarrollar ESLIP.

¿Por qué utilizarlo?
--------------------

### Fácil configuración

ESLIP cuenta con un wizard de configuración, es decir, un asistente con los mínimos pasos necesarios para obtener un widget de Social Login funcional. El desarrollador deberá completar dicho asistente para realizar la configuración inicial.

### Fácil integración

Una vez realizada la configuración, para visualizar el widget en una página sólo se debe incluir en dicha página una hoja de estilos CSS, un archivo JavaScript y un elemento HTML <code>&lt;div&gt;</code> con el identificador "eslip-plugin" en la parte del documento HTML donde se desee que aparezca el widget.

La creación del widget se realiza en el archivo JavaScript, allí primero se obtiene por medio de una petición ajax a la API de servicios de ESLIP los datos necesarios como son por ejemplo los proveedores de identidad activos y las configuraciones de personalización del widget. Luego se generará el bloque de código HTML correspondiente al widget, el cual, para finalizar es incluido en el elemento HTML <code>&lt;div&gt;</code> con id "eslip-plugin".

Cabe destacar que el código HTML que se genera, fue validado satisfactoriamente en el sitio web de la W3C. Por lo tanto, la inclusión de ESLIP no altera el estado de validación del sitio o página en cuestión.

### Fácil administración y personalización

Adicionalmente el plugin cuenta con un módulo de administración, el cual provee una interfaz de usuario para modificar la configuración de forma muy sencilla. Aquí se permite agregar o quitar proveedores de identidad, actualizar los datos de los mismos y activarlos o desactivarlos para que aparezcan o no en el widget. También se permite personalizar diferentes aspectos del widget en general así como también de cada botón de un proveedor de identidad.

Para facilitar más aún las cosas, ESLIP cuenta con una lista de proveedores de identidad precargados, listos para ser configurados y activados de forma muy simple.

### El desarrollador tiene control total sobre los datos

ESLIP es sólo un plugin que facilita la comunicación con el proveedor de identidad que el usuario eligió para identificare utilizando el protocolo correspondiente. No se encarga de crear ni administrar las aplicaciones cliente en cada proveedor de identidad que se quiera ofrecer. De esta tarea se debe encargar el desarrollador.

Además ESLIP es alojado junto al sitio o aplicación que lo incluye, en el mismo servidor, sin comunicarse con ningún otro servidor. Por lo tanto, al utilizar ESLIP no existen otros servidores intermediarios, todas las comunicaciones se realizan entre el servidor del sitio o aplicación web y el proveedor de identidad.

### Es software de código abierto

Los usuarios pueden estudiar, modificar y mejorar su diseño e implementación mediante la disponibilidad de su código fuente, el cual se encuentra alojado en [GitHub](https://github.com/eslip/eslip).

Luego de analizar diferentes licencias de código abierto, se optó por elegir la [licencia MIT](http://www.opensource.org/licenses/mit-license.php) para ESLIP. Esta licencia la única condición que establece es que se incluya la nota de copyright y la sección de los derechos en todas las copias o partes sustanciales que se utilicen del software.

Los derechos que otorga MIT son muchos: sin restricciones; incluyendo usar, copiar, modificar, integrar con otro software, publicar, sublicenciar y/o vender copias del software ya demás permitir a las personas a las que se les entrega el software hacer lo mismo.

Asimismo, contribuir con código no es la única manera de ayudar, también es posible traducir ESLIP a un idioma determinado, mejorar o ampliar la documentación o simplemente compartir el link de este sitio para que más gente pueda conocerlo.

¿Cómo surgió?
-------------

Gran parte de la población que tiene acceso a Internet está registrada al menos en un sitio que funciona como proveedor de identidad, siendo, las redes sociales, el tipo de sitio que predomina en este conjunto gracias a la popularidad creciente que han ganado en este último tiempo.

A raíz de esto, es que empieza a ganar terreno en la web algo conocido como Social Login, un modelo de autenticación y autorización que permite a los usuarios identificarse en un sitio web particular utilizando sus cuentas de Facebook, Google o Twitter, entre otros proveedores de identidad, agilizando enormemente el proceso de identificación, haciendo de lado los formularios de registro.

Por consecuencia, han ido surgiendo empresas u organizaciones que proveen servicios para incorporar Social Login en un sitio web. Estas organizaciones, que proporcionan formas sencillas de integración, y buena documentación o soporte, son pagas, y terminan siendo un intermediario entre el sitio web que contrata el servicio y el proveedor de identidad, ya que los datos que se extraen de la red social o servicio en línea que el usuario elige para autenticarse, pasan antes por los servidores del prestador del servicio de Social Login y luego llegan al sitio web que el usuario está navegando.

También, la mayoría de los proveedores de identidad ofrece librerías para facilitar la comunicación con sus APIs. El problema que existe es que todas las APIs de los proveedores de identidad son diferentes entre sí y por lo tanto se dificulta mucho integrar las distintas librerías y ofrecer Social Login con varias opciones.

Por otro lado, existen soluciones que son gratuitas, de código abierto y en las cuales el manejo de la información queda bajo el control del desarrollador web, pero actualmente hay muy pocas, y la mayoría de ellas son difíciles de integrar o poseen escasa documentación e incluso muchas están a medio desarrollar.

A pesar de todas las opciones que existen para que un desarrollador pueda implementar Social Login en sus sitios web, ninguna de ellas logra satisfacer la necesidad más importante que tiene hoy en día el desarrollador promedio: facilidad y rapidez de integración y de adaptación. Con esta premisa se comienza a pensar en ESLIP.

Nos propusimos como objetivos, primero, realizar una investigación teórica sobre Social Login y los protocolos más utilizados en esta tecnología; luego, en base a lo investigado, implementar un plugin que le permita a un desarrollador web integrar de manera sencilla Social Login en una aplicación o sitio web.

En lo que respecta a la investigación, se propuso estudiar el estado del arte de esta forma actual de identificación: cómo y por qué surgió, beneficios y desventajas tanto para los usuarios como para los desarrolladores. A su vez, evaluar el funcionamiento de los protocolos más utilizados para implementar identificación o autorización de un usuario en un sitio web: <b>OAuth</b> y <b>OpenID</b>.

En base a lo analizado, se implementó ESLIP.


Instalación y uso
------------------

### 1. Requerimientos

+ Servidor web Apache
+ PHP >= 5.3

#### Configuraciones necesarias del servidor web Apache:

+ Tener activado el módulo 'mod_rewrite'.
+ En el VirtualHost correspondiente:
    + Permitir la reescritura estableciendo la opción 'AllowOverride' en 'All'.
    + Asegurarse de NO incluir la opción 'Multiviews'.

#### Módulos necesarios de PHP

+ Módulo PHP SimpleXML
+ Módulo PHP CURL

### 2. Descarga del Plugin

Como primer paso se debe descargar la última versión del plugin ESLIP desde el [sitio web](http://eslip.com.ar) oficial del plugin o desde [GitHub](https://github.com/eslip/eslip). Éste estará en forma de fichero comprimido (zip). Una vez realizada la descarga, se procede a descomprimir el archivo.

Otra forma de obtener el plugin es directamente desde GIT realizando la clonación del proyecto. Esto se puede llevar a cabo mediante el comando:

<pre><code>$ git clone git://github.com/eslip/eslip.git</code></pre>

Como resultado, de cualquiera de las alternativas de descarga antes mencionadas, se debería obtener esta estructura de archivos:

<pre>
carpeta descomprimida/
    ├── example/
    ├── docs/
    ├── eslip/
</pre>

+ Dentro de la carpeta `example/` se encuentra un ejemplo de como funciona el plugin. En la misma se incluye un archivo `index.php` en donde se encuentra el código que renderiza el widget de Social Login. Allí también se encuentra un archivo llamado `login.php` que es el archivo a donde se retorna una vez realizado todo el procesamiento por parte del plugin  y donde se muestra cómo recuperar los datos que fueron obtenidos del proveedor de identidad.

+ Por otro lado, en la carpeta `docs/` se encuentra la documentación del código fuente de los archivos más importantes del plugin. Dicha documentación fue generada con la herramienta [phpDocumentor](http://www.phpdoc.org/). Esta documentación ofrece una visión en profundidad del proyecto orientada tanto a desarrolladores como a consumidores y contribuyentes.

+ Por último, en la carpeta `eslip/` se encuentra el plugin. Esta es la carpeta que se debe alojar en el sitio web donde se desea integrar el plugin.

### 3. Subir el Plugin al Servidor

Como se mencionó anteriormente, una vez descargado el plugin se debe proceder a colocar la carpeta `eslip/` (la cual contiene el código del plugin) en el servidor web correspondiente. Se puede alojar en cualquier ruta. Por ejemplo si se aloja en la raíz del sitio web `www.example.com` la ruta sería `www.example.com/eslip`.

### 4. Configuración del Plugin

Una vez subido el plugin al servidor, ya se encuentra apto para ser configurado. Para ello se debe escribir en la barra de direcciones del navegador la ruta donde fue ubicada la carpeta del plugin y de esta manera se accede al administrador.
Al ser la primera vez que se ingresa al administrador, se debe ejecutar el Wizard de configuración. Esta acción se lleva a cabo haciendo clic en el link del cartel informativo del formulario para ingrsar al administrador.

### 5. Manejo de la información devuelta por ESLIP

Una vez que el proveedor de identidad devuelve los recursos solicitados, ESLIP los envía a la URL de retorno configurada por el desarrollador.

A partir de aquí, los recursos del usuario obtenidos están en manos del desarrollador y será él quien decida qué hacer con ellos.

Dichos recursos son enviados a la URL de retorno por POST pero también son almacenados bajo la llave <code>['ESLIP']</code> en la sesión de PHP.

Los recursos se le envían al desarrollador dentro de una estructura de arreglo. Dicho arreglo contiene la siguiente información:

<b>'state':</b> 'success' | 'error'<br />
<b>'referer':</b> URL de la página en la que estaba el usuario y desde donde proviene el intento de identificación<br />
<b>'server':</b> Proveedor de identidad elegido por el usuario para identificarse

Si la identificación es satisfactoria, es decir que el status es 'success' se incluye la siguiente información:

<b>'user':</b> Recursos del usuario obtenidos del proveedor de identidad<br />
<b>'user_identification':</b> Identificador del usuario. La llave del recurso que se considera identificador debe ser configurada en el administrador para cada proveedor de identidad

Si ocurre un error durante el proceso de identificación, es decir que el status es 'error' se incluye la siguiente información:

<b>'error': </b> Descripción del error

Documentación completa
----------------------

La documentación completa de ESLIP está disponible el [sitio web](http://eslip.com.ar/) oficial del plugin.

Contribuir con el proyecto
--------------------------

ESLIP es un proyecto de código abierto, como ya se ha mencionado anteriormente, por ese motivo es que se pone a disposición de la comunidad, tanto su código, como su documentación. Invitando a colaborar con el desarrollo, a quien desee hacerlo, buscando permanente el crecimiento y la evolución del plugin. Asimismo, contribuir con código no es la única manera de ayudar, también es posible traducir ESLIP a un idioma determinado o simplemente compartir los links de la página del plugin, para que más gente pueda conocerlo.

Para contribuir con el desarrollo del plugin se recomienda seguir el siguiente proceso:

1. Instalar [Git](http://git-scm.com/downloads)
2. Registrarse en [GitHub](https://github.com/join).
3. Crear un fork del repositorio de ESLIP (https://github.com/eslip/eslip). Clonar el fork en la máquina donde se va a desarrollar. Configurar los remotos. Crear un nuevo branch (del branch principal de desarrollo del proyecto) para contener su mejora, cambio o arreglo. Detalles [aquí](https://help.github.com/articles/fork-a-repo).
4. Agregar, quitar o modificar lo que se crea necesario para la mejora del plugin.
5. Una vez realizadas las modificaciones, realizar un push de los cambios locales al fork en GitHub. Luego crear una solicitud de pull desde el branch creado al branch principal (master). Detalles [aquí](https://help.github.com/articles/using-pull-requests). En la solicitud de pull, describir lo que hacen los cambios y mencionar el número de issue que se encuentra involucrado. Por ejemplo, "Cierra #123".
6. Probablemente se origine una discusión sobre la solicitud de pull y, de ser necesario se realizará algún cambio, si todo resulta bien, se realizará el merge al branch principal del proyecto.


Licencia
--------

ESLIP Plugin se distribuye bajo la [Licencia MIT](http://opensource.org/licenses/mit-license.php). Usted es libre de usar, modificar y distribuir este software, siempre y cuando la cabecera de los derechos de autor se deje intacta.
