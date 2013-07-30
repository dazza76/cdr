#!/bin/sh


echo "Backup project - v-0.1"
echo
##
## Параметры проекта
##
PROJECT_NAME="cdr"
BACKUP_DIR="build"
DIR_ADD="css images js lib protected .htaccess"
# -------------------------------------------

## Системные параметры
PHP_BIN="z:/usr/local/php5/php"
WWW_DIR="z:/home/localhost/html"
# -------------------------------------------

## параметры выполнения
ROOT_DIR="${WWW_DIR}/${PROJECT_NAME}"
BC_DATE=$(date +%F_%H-%M-%S)
BC_PREFIX=

if [[ $1 != "" ]]; then
    BC_PREFIX=".$1"
    if [[ $2 != "" ]]; then
        BC_PREFIX="${BC_PREFIX}.$2"
    fi
fi
BACKUP_FILENAME="${BACKUP_DIR}/${PROJECT_NAME}.${BC_DATE}${BC_PREFIX}.tar.gz"
# -------------------------------------------

cd $ROOT_DIR

## Файлы бекапа
FILES_ADD=$(ls | grep '.php' | grep -v 'test')
FILES_ADD="${DIR_ADD} ${FILES_ADD}"
# -------------------------------------------



tar -zacf ${BACKUP_FILENAME} ${FILES_ADD}


echo ${FILES_ADD}
echo "Archive file ${BACKUP_FILENAME} save"