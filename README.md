[ESLIP Plugin](http://eslip.com.ar/)
====================================

Esay Social Login Integration Plugin (ESLIP), en español Plugin Simple para Integración de Social Login, permite integrar fácilmente Social Login a un sitio web, tal como lo indica su nombre. El principal objetivo de esta herramienta es permitir a los desarrolladores web incorporar de forma sencilla un widget que ofrezca la posibilidad de que los usuarios se identifiquen en un sitio web por medio de las cuentas de redes sociales y servicios en línea más populares, como por ejemplo Facebook, Twitter, Google, entre otros.

Instalación y uso
------------------

### 1. Descargar el plugin

Lo primero que hay que hacer es descargar la última versión del plugin. Éste estará en forma de fichero comprimido (zip). Esto lo podemos hacer desde el sitio web [http://eslip.com.ar](http://eslip.com.ar) o desde [https://github.com/eslip/eslip](https://github.com/eslip/eslip).
Una vez descargado, procedemos a descomprimir el fichero. Como resultado deberíamos ver esta estructura:

<pre>
carpeta descomprimida/
    ├── example/
    ├── docs/
    ├── eslip/
</pre>

+ En la carpeta `example` se encuentra un ejemplo de como funciona el plugin. Aquí se incluye un archivo `index.php` en donde se encuentra el código que muestra el widget, y un `login.php` que sería el archivo donde se retorna una vez realizado todo el procesamiento por parte del plugin  y donde se muestra como recuperar los datos que fueron obtenidos del proveedor de identidad.

+ En la carpeta `docs` se encuentra la documentación del código fuente de los archivos más importantes del plugin. Dicha documentacion fue generada con la herramienta [phpDocumentor](http://www.phpdoc.org/). Esta documentación ofrece una visión en profundidad del proyecto para nosotros como desarrolladores, para los consumidores y para los contribuyentes.

+ En la carpeta `eslip` se encuentra el plugin. Esta sería la carpeta que hay que alojar en el sitio web donde queremos integrar el plugin . 

### 2. Subir el plugin a tu servidor

Debes subir a tu servidor la carpeta del plugin. Lo puedes alojar en cualquier ruta. Por ejemplo si lo alojas en la raiz del sitio web `www.example.com` la ruta sería `www.example.com/eslip`.

### 3. Configurando el plugin

Para configurar y hacer visible el plugin debemos escribir en la barra de direcciones de nuestro navegador la ruta donde ubicamos la carpeta del plugin y así accederemos al administrador.
Al ser la primera vez, como primer paso debemos ejecutar el wizard de configuración haciendo clic en el link del cartel informativo.

Documentación completa
----------------------

La documentación completa de ESLIP está disponible el [sitio web](http://eslip.com.ar/) oficial del plugin. En el sitio también se encuentra el informe desarrollado para la Tesina de grado para la cual fue desarrollado este plugin

Licencia
--------

ESLIP Plugin se distribuye bajo la [Licencia MIT](http://opensource.org/licenses/mit-license.php). Usted es libre de usar, modificar y distribuir este software, siempre y cuando la cabecera de los derechos de autor se deje intacta.
