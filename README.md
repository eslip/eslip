[ESLIP](http://eslip.com.ar/)
=============================

Easy Social Login Integration Plugin (ESLIP), en español Plugin Simple para Integración de Social Login, permite integrar fácilmente Social Login a un sitio web, tal como lo indica su nombre. El principal objetivo de esta herramienta es permitir a los desarrolladores web incorporar de forma sencilla un widget de inicio de sesión que ofrezca la posibilidad de que los usuarios se autentiquen en un sitio web por medio de las cuentas de redes sociales y servicios en línea más populares, como por ejemplo Facebook, Twitter, Google, entre otros.

Si bien existen en la web, diferentes tipos de plugins que brindan Social Login, ESLIP se diferencia del resto por su premisa de sencillez de integración para desarrolladores web. ESLIP está preparado para ser integrado y configurado en simples pasos. Asimismo, cuenta con la gran ventaja de poder fácilmente incorporar proveedores de identidad a la configuración para adaptarse a los cambios repentinos que abundan hoy día en el mundo web.

ESLIP cuenta con un wizard de configuración que guía al desarrollador para que en pocos pasos pueda configurar su widget de Social Login. A su vez cuenta con un módulo de administración desde el cual es posible agregar y quitar proveedores de identidad, habilitar y deshabilitar los mismos del widget, configurar el idioma, entre otras opciones que son explicadas en profundidad en la documentación.


¿Cómo surgió?
-------------

Implementar Social Login es una tarea difícil desde el principio, más aún si se planea que lo haga una sola persona. Las grandes redes sociales de todo el mundo ofrecen Social Login y no toma mucho tiempo darse cuenta de que es casi una misión imposible integrar más de dos redes sociales sin asignar una significante cantidad de recursos humanos para la implementación y el mantenimiento de la autenticación. 

Cuando comienza la investigación, los desarrolladores empiezan a darse cuenta que muy pocas de las API’s, de estos proveedores de identidad, siguen las mismas normas y que lo que podría haber funcionado ayer cambia con una actualización hoy. Por ejemplo, mientras que Twitter sigue con OAuth 1.0, Google cierra Buzz que se ejecutaba con un protocolo híbrido (OAuth 1.0 combinado con OpenID) para luego lanzar Google+ sobre OAuth 2.0. Esta versión no es la misma que la de OAuth 2.0 que Facebook está utilizando. Y ahí empieza la pesadilla para los desarrolladores.

Para no tener que lidiar con las cuestiones planteadas anteriormente existen soluciones pagas para implementar Social Login en un sitio web. Estos proveedores, ofrecen sencillez, personalización, y análisis de datos, entre otras cosas. Estas características pueden potencialmente ser muy beneficiosas para los sitios web de comercio electrónico, ya que brindan estadísticas y análisis de los datos de los usuarios.

Contratar un proveedor de Social Login pago puede ser una buena decisión, ya que, básicamente resolvería toda la complejidad que conlleva implementar un Social Login propio. Incluso se podría  contar con soporte post implementación, dependiendo del tipo de servicio contratado. Pero, como todo, la utilización de servicios externos tiene sus contras. Principalmente, incluir a un tercero, el proveedor, tiene el inconveniente de que se estaría compartiendo cierta información con éste, más que nada, se estarían dejando los datos de los usuarios en manos del proveedor de Social Login. Es una realidad que no todos los desarrolladores se sienten cómodos teniendo un intermediario entre el usuario y su sitio web, más aun sabiendo que este intermediario procesará y analizará la información que pase por sus manos. El hecho de elegir utilizar un proveedor de Social Login depende exclusivamente de la confianza que el desarrollador tenga en él.

Por otro lado, existen soluciones que son gratuitas, de código abierto y en las cuales el manejo de la información queda bajo el control total del desarrollador web, pero tienen la desventaja de ser muy difíciles de integrar, y de poseer escasa documentación. Incluso muchas de ellas están a medio desarrollar o manejan muy pocos proveedores de identidad y no son extensibles.

A pesar de todas las opciones que existen para que un desarrollador pueda implementar Social Login en sus sitios web, ninguna de ellas logra satisfacer la necesidad más importante que tiene hoy en día el desarrollador promedio, que la mayoría de los casos es: facilidad y rapidez de integración y de adaptación. Con esta premisa se comienza a pensar en ESLIP, un plugin sencillo, que sea fácil de integrar, que sea él quien guía al usuario en la configuración, que genere código para que el desarrollador lo utilice. Más aún, que se pueda adaptar fácilmente a los nuevos proveedores de identidad que surgen día a día en la vorágine que es internet.


¿Por qué utilizarlo?
--------------------

A nuestro parecer, existe una vasta cantidad de buenas razones para que un desarrollador elija implementar su solución de Social Login mediante ESLIP, pero la principal razón se basa explícitamente en el motivo por el cual se decidió llevar adelante el desarrollo de este plugin, y es la simpleza, de la mano de la facilidad de integración. Partiendo de esta característica, se ramifican los demás beneficios que contrae utilizar ESLIP. 

Asimismo, se debe destacar que el plugin apunta a desarrolladores web en general. Dicho de otro modo, para poder integrar ESLIP a un sitio web, no es necesario contar con desarrolladores expertos, basta con conocimientos mínimos de programación web y con seguir las instrucciones que brinda el plugin. De esta manera, siguiendo los pasos que propone ESLIP con atención, en un lapso breve se podrá contar con un widget de Social Login funcional para realizar autenticación de usuarios en un sitio web.

Para poder guiar al desarrollador, ESLIP cuenta con un wizard de configuración, es decir cuenta con una interfaz de usuario que presenta una secuencia de cuadros de diálogo que conducen al usuario a través de una serie de pasos bien definidos. De esta manera, la tarea de configuración, que en la mayoría de los plugins es compleja, resulta ser más fácil de realizar mediante este asistente.

Adicionalmente el plugin cuenta con un módulo de administración, el cual provee una interfaz de usuario para modificar la configuración forma muy sencilla, permitiendo agregar proveedores de identidad, actualizar los datos de los mismos, activar y desactivar los distintos proveedores, entre otras cosas. Cabe destacar que este módulo de administración brinda la posibilidad de agregar nuevos proveedores de identidad, extendiendo así la funcionalidad del widget de Social Login, para ofrecer a los usuarios nuevas opciones de autenticación. El único requisito necesario para poder agregar un proveedor de identidad nuevo es que debe soportar el protocolo OAuth o el protocolo OpenID en cualquiera de sus versiones.

Más aún, para facilitar las cosas, ESLIP cuenta con una lista de proveedores de identidad precargados, listos para ser configurados y activados de forma muy simple, (sólo deben configurarse dos o tres datos dependiendo del proveedor en cuestión). A su vez, esta lista se irá actualizando en razón de la popularidad que vayan adquiriendo los distintos proveedores de identidad que abundan en la web, de manera tal, que el plugin siga creciendo para dar soporte a la mayor cantidad de proveedores posible.

Uno de los factores más importantes a destacar es que con ESLIP, el desarrollador posee control total sobre los datos de los usuarios, es decir, el plugin se encarga de realizar la autenticación del usuario frente al proveedor de identidad seleccionado y luego retorna un objeto con los datos de ese usuario, que fueron solicitados mediante la configuración de dicho proveedor. De esta manera ESLIP no se interpone entre el sitio web y los datos del usuario, sólo verifica que el usuario sea quien dice ser y pone, en manos del sitio web, los datos del usuario que retornó el proveedor de identidad.

Por último, es importante mencionar que esta herramienta está disponible tanto en español como en inglés. Basta con configurar el idioma deseado en el módulo de administración. Pero si se necesita en otro idioma, resulta muy simple extenderlo para tal requerimiento. Por otra parte, se espera que, en un futuro, los desarrolladores que utilicen ESLIP colaboren con el proyecto y, entre otras cosas, compartan las distintas traducciones para así expandir el uso del plugin más allá de las fronteras.


Instalación y uso
------------------

### 1. Descarga del Plugin

Como primer paso se debe descargar la última versión del plugin ESLIP desde el [http://eslip.com.ar](sitio web) oficial del plugin o desde [https://github.com/eslip/eslip](GitHub). Éste estará en forma de fichero comprimido (zip). Una vez realizada la descarga, se procede a descomprimir el archivo.

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

### 2. Subir el Plugin al Servidor

Como se mencionó anteriormente, una vez descargado el plugin se debe proceder a colocar la carpeta `eslip/` (la cual contiene el código del plugin) en el servidor web correspondiente. Se puede alojar en cualquier ruta. Por ejemplo si se aloja en la raíz del sitio web `www.example.com` la ruta sería `www.example.com/eslip`.

### 3. Configuración del Plugin

Una vez subido el plugin al servidor, ya se encuentra apto para ser configurado. Para ello se debe escribir en la barra de direcciones del navegador la ruta donde fue ubicada la carpeta del plugin y de esta manera se accede al administrador.
Al ser la primera vez que se ingresa al administrador, se debe ejecutar el Wizard de configuración. Esta acción se lleva a cabo haciendo clic en el link del cartel informativo del formulario para ingrsar al administrador.

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
