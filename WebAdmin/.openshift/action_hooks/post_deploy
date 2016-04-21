#!/bin/bash

export MY_PHPCOMPOSER=$OPENSHIFT_DATA_DIR/composer.phar

# if composer not exists, download
if [ ! -f $MY_PHPCOMPOSER ]; then
    cd $OPENSHIFT_DATA_DIR
    echo "Downloading composer..."
    php -r "readfile('https://getcomposer.org/installer');" | php 
fi

$MY_PHPCOMPOSER -n -q self-update
cd $OPENSHIFT_REPO_DIR 
# install
php -dmemory_limit=1G $MY_PHPCOMPOSER install