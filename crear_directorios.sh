#!/bin/bash
echo 'Este script debe ser ejecutado con el usuario que ejecuta los archivos PHP y escribe en la carpeta uploads (normalmente "apache")'
USER_OWNER=$USER
USER_GROUP=apache

mkdir -p \
    uploads/anticorrupcion \
    uploads/designacion_transitoria \
    uploads/evaluacion \
    uploads/foto_persona \
    uploads/importador \
    uploads/tituloCreditos \
    uploads/promocion_grado