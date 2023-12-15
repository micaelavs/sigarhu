# Change Log SIGARHU V12.0

## Resumen de nuevas funcionalidades
 - Legajo -> Formación:
 - - Tanto los listado de cursos, como los de titulos obtenido tienen un buscador.
 - - La edición de cursos esta desactivada por defecto, se debe apretar el botón **"Editar Campos"** para activar.
 - - Si el listado de cursos alcanza la cantidad de **10** aparece el botón de **Historial**.
 - Se agrega la pantalla **"Historial de Cursos"** dentro del legajo del empleado en la pestaña formación.
 - Importador de Cursos
 - - Si el código del curso empieza con el prefijo `EXTRA-` se puede aplicar el mismo curso varias veces al mismo empleado.
 - - Si tiene contenido en mas columnas de las necesarias, se ignora y no falla.
 - Se quita el acceso para todos los roles de la pantalla **"Datos Globales"**.
 - Se agrega la pantalla en el listado de ABM **"Créditos para promoción"**.
 - Se agregar **Listado Promociones**, dentro del menú **Informes**.
 - Se agregar modulo **Simulación de Promoción de Grado**, accesible desde el menú **Informes**
 

## Errores corregidos: 
 - Revisión y corrección de todos los botones "Volver" o "Cancelar"
 - ABM -> Ubicaciones: Se agrega validación. Piso y Oficina son requeridos.
 - ABM -> Títulos: Se corrige errores en el buscador
 - (Comisión) ABM -> "Organismos Origen/Destino": Se agrega validación para evitar textos vacíos
 - Legajo -> Perfil de Puesto -> Evaluaciones:
 - - Modificar evaluaciones mantiene el estado del botón "Bonificado"
 - - Historial de Evaluaciones: Muestra la columna "Bonificado" y ordena por "Año" de mayor a menor por defecto.
 - - Alta de evaluaciones: El botón "Bonificado" solo esta disponible para la situación de revista "Planta Permanente"
 - Legajo -> Formación -> Cursos:
 - - Mantiene la opción seleccionada para "Tipo de Promoción" (tramo/grado)
 - Legajo -> Formación -> Historial porcentaje títulos:
 - - Se corrige manejo de errores al dar la baja de porcentajes de títulos
 - Legajo -> Ubicación en la Estructura: Se muestra el árbol de dependencias según organigrama

 

## Cambios para Developers
 - Cambios para nueva estructura de servidores:
 - - Los archivos públicos (img, css, js) se mueven al directorio `public`.
 - - Se agrega el directorio `assets` para archivos que se deben acceder de forma directa y que no requieren permisos de acceso ni están vinculados a un empleado.
 - - Las URL de proyectos externos (e.j: CDN) son dinámicas y se configuran como "endpoint" en `config/app.conf`.
 - - Se corrigen todas las rutas de **endpoints**.
 - - Todo archivo que se accedía directamente desde el directorio `uploads` pasa por un método de PHP (e.j: Foto Persona).
 - - Los archivos `api.php`, `cron.php`, `index.php` se mueven al directorio `public`.
 - En todas las vistas PHP esta disponible `$vista->js_default` que pose una ruta por defecto para archivos JS con formato "public/js/nombre_controlador/nombre_accion.js". Esa ruta de archivo se incluye automáticamente en el footer html, si fue creado.
 - En todas las vistas PHP esta disponible `$vista->getSystemConfig()['app']['endpoint_cdn'];` para obtener la ruta base en caso de necesitar dependencias de CDN.
 - En todo contexto **JavaScript** esta disponible la variable `$endpoint_cdn` que contiene la ruta base para CDN.
 - `Cron.php` implementa \FMT\Consola
 - Para todas las vistas de `Legajos.php` se agrega por defecto en el footer el archivo `script.js`.
 - Migracion de PHP 5.6 a 7.4: Las variables inicializadas como `string` pero implementadas como `array` arrojan Fatal Error.
 - En `Controlador\Legajos` se agrega la accion `mostrar_foto_persona`. Tenerlo en cuenta para futuros accesos de imágenes.
 - Para cuando se realiza `Modelo\Empleado::obtener($cuit)` con el fin de mostrar en pantalla Nombre, Apellido y CUIT se agrega `Modelo\Empleado::contiene(['persona' => []]);` con el fin de reducir la consulta de datos innecesarios.
 - Se agrega constante `PHP_INTERPRETE` en `bootstrap.php` para configurar el interprete de PHP usando en los procesos por consola asíncronicos.
 - Constante `$SITUACION_REVISTA` tenia mal el ID para PLANTA_PERMANENTE.
 - En la mayoría de los modelos para el método `::arrayToObject()` se implementa `parent::arrayToObject($res, $campos);`.
 - En `Modelo\ImportadorExcel` las rutas de carpetas para subir archivos son una constante.
 - En `\App\Modelo\Modelo` se incorpora el método `::init()` global para todas las herencias, con el fin de inicializar los comportamientos en el caso de llamadas asíncronas.
 - Se agregan pruebas unitarias para los `endpoint` se ejecuta con **"php74 vendor/bin/phpunit"**.
 - Para el archivo `src/Helper/Conexiones.php`:
 - - Se agrega el cierre de cursores por defecto. Util para cuando una vista SQL implementa cursores
 - - `activarDebug()` se activa automáticamente ante errores de Base de Datos en entorno de desarrollo.
 - En la base de datos se agrega una tabla `empleado_historial_creditos` que almacena todo el historial de suma o quita de creditos y porcentajes de reconocidos
 - Comportamientos globales de JS
 - - Si se aplica dentro del HTML la clase `activarSelect2` a una etiqueta **"select"**, automáticamente se incorpora un buscador
 - - Los botones de **"volver"** para legajo, aplican el comportamiento adecuado usando la clase `volver_legajo` (leer implementación en `script.js`)
