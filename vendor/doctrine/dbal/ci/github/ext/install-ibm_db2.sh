#!/usr/bin/env bash

set -ex

echo "Installing extension"
(
    # updating APT packages as per support recommendation
    sudo apt-get -y -q update
    sudo apt-get install ksh php-pear

    cd /tmp

    wget http://cdn1.netmake.com.br/download/Conexao/DB2/Linux/x64_v10.5fp8_linuxx64_dsdriver.tar.gz

    tar xf x64_v10.5fp8_linuxx64_dsdriver.tar.gz
    ksh dsdriver/installDSDriver

    pecl download ibm_db2
    tar xf ibm_db2-*
    rm ibm_db2-*.tgz
    cd ibm_db2-*
    phpize
    ./configure --with-IBM_DB2=/tmp/dsdriver
    make -j "$(nproc)"
    sudo make install
)
