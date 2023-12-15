# Instrucciones para Deploy

 - Clonar proyecto sigarhu con la version correspondiente: `git clone -b v12.0.0 http://desa.transporte.gob.ar:3000/Transporte/sigarhu.git`
 - Dentro del directorio de la aplicacion configurar: `cp -r config_sample config` y cambiar los datos correspondientes al entorno donde sera ejecutado. Los importantes son: `api_sigarhu.php`, `app.php`, `database.php`, `log.php`, `mail.php` y `recibos.php`.
 - Ejecutar `composer install` o garantizar que existan los `vendor`
 - Ejecutar el archivo `./crear_directorios.sh`
 - Asignar permisos al servidor para leer y escribir: `chown apache:sigarhu -R ./uploads`
 - Asignar permisos al servidor para leer y escribir: `chmod 775 -R ./uploads`
 - Si el archivo `upload/contantes_tmp.php` existe, eliminarlo (el sistema lo regenera al primer loggeo)
 - Setear la el interprete PHP en el archivo `bootstrap.php` variable `PHP_INTERPRETE` ej: `/usr/bin/php71` (**Para uso del cron**)
 - Es posible ejecutar `vendor/bin/phpunit` para probar la conexion con las dependencias. **(dev only)**
 - Agregar regla en crontab: `*/1 * * * * apache /usr/bin/php74 /var/www/html/qa/sigarhu/public/cron.php cola_tareas > /tmp/cron-qa.log 2> /tmp/cron-qa.log &`
 
## Para realizar la migracion de base de datos
Al DUMP que se les pase se le deben aplicar estos comandos para aplicar los nombre apropiados
 - NOTA: Este proceso es necesario debido a que existe llamado cruzado entre las bases de datos. Se intento hacer referencia dinamica y tecnicamente no se pudo.
 - - `sed -i -e 's/sigarhu_aud_desa/sigarhu_aud/g' /ruta/archivo`
 - - `sed -i -e 's/sigarhu_desa/sigarhu/g' /ruta/archivo`
 
 
## Proyectos Dependencias
 - **panel**, version 9.0 o superior: http://desa.transporte.gob.ar:3000/Transporte/panel/src/tag/v9.0
 - **informacion_fecha**, version 1.0.0 o superior: http://desa.transporte.gob.ar:3000/Transporte/informacion_fecha/src/tag/v1.0.0
 - **ubicaciones**, version 1.0.0 o superior: http://desa.transporte.gob.ar:3000/Transporte/ubicaciones/src/tag/v1.0.0
 - **cdn**, version 1.0.0 o superior: http://desa.transporte.gob.ar:3000/Transporte/cdn/src/tag/v1.0.0
 - **logger**, version 1.0.0 o superior: http://desa.transporte.gob.ar:3000/Transporte/logger/src/tag/v1.0.0
 - **mailer**, version 2.0 o superior: http://desa.transporte.gob.ar:3000/Transporte/mailer/src/tag/v2.0
 - **Recibos de Sueldo**, acceso de solo lectura al directorio _*/var/www/html/recibos*_ ubicado en el servidor **https://intranet.transporte.gob.ar** (**MTNLAS100**)
 - Comunicacion bidireccional mediante puerto 80 y 443 contra el servidor **https://intranet.transporte.gob.ar** (**MTNLAS100**)
 
## GIT - Check and set branch
 - **Si el proyecto esta clonado:**
 - - `git fetch origin`
 - - `git checkout master`
 - - `git pull origin master`
 - - `git checkout {TAG_VERSION}`
 - **Si el proyecto nunca fue creado:**
 - - `git clone -b {TAG_VERSION} http://desa.transporte.gob.ar:3000/Transporte/{PROYECTO}.git`
 
## PHP INI
 - 

## VirtualHost
- SetEnv _ "/usr/bin/php74"

## Archivos de configuracion

- **api_sigarhu.php**
- - IP de MTNLAS100 - 192.168.130.67
- - 127.0.0.1

