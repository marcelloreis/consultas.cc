#!/bin/bash
# My first script

#Posiciona o diretorio na raiz do projeto
cd /var/www/consultas.cc


#Declaracao das variaveis
app=`pwd`
user=root
password=62792

# Pausa a importacao
echo '0' > "$app/_db/settings/on_off"

#Zera as tabelas auxiliares
mysql -u $user -p$password -e 'UPDATE consultas_cc._counter SET success = null, fails = null, extracted = null, start_time = null;'
mysql -u $user -p$password -e 'UPDATE consultas_cc._timing SET time = null;'
mysql -u $user -p$password -e 'TRUNCATE table consultas_cc._logs;'

#Migra todos os dados importados ate o momento
mysql -u $user -p$password -e 'INSERT INTO consultas_cc.entities SELECT * FROM consultas_cc.i_entities;'
mysql -u $user -p$password -e 'INSERT INTO consultas_cc.landlines SELECT * FROM consultas_cc.i_landlines;'
mysql -u $user -p$password -e 'INSERT INTO consultas_cc.zipcodes SELECT * FROM consultas_cc.i_zipcodes;'
mysql -u $user -p$password -e 'INSERT INTO consultas_cc.addresses SELECT * FROM consultas_cc.i_addresses;'
mysql -u $user -p$password -e 'INSERT INTO consultas_cc.associations SELECT * FROM consultas_cc.i_associations;'

#Zera as tableas de importacao
mysql -u $user -p$password -e 'TRUNCATE TABLE consultas_cc.i_entities;'
mysql -u $user -p$password -e 'TRUNCATE TABLE consultas_cc.i_landlines;'
mysql -u $user -p$password -e 'TRUNCATE TABLE consultas_cc.i_zipcodes;'
mysql -u $user -p$password -e 'TRUNCATE TABLE consultas_cc.i_addresses;'
mysql -u $user -p$password -e 'TRUNCATE TABLE consultas_cc.i_associations;'

#Sincroniza o auto increment das tabelas de importacao e de producao
ai=`mysql -u $user -p$password -e "SELECT AUTO_INCREMENT FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'consultas_cc' AND TABLE_NAME = 'entities';" | sed 's/[^0-9]*//g'`
mysql -u $user -p$password -e "ALTER TABLE consultas_cc.i_entities AUTO_INCREMENT = $ai;"

ai=`mysql -u $user -p$password -e "SELECT AUTO_INCREMENT FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'consultas_cc' AND TABLE_NAME = 'landlines';" | sed 's/[^0-9]*//g'`
mysql -u $user -p$password -e "ALTER TABLE consultas_cc.i_landlines AUTO_INCREMENT = $ai;"

ai=`mysql -u $user -p$password -e "SELECT AUTO_INCREMENT FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'consultas_cc' AND TABLE_NAME = 'zipcodes';" | sed 's/[^0-9]*//g'`
mysql -u $user -p$password -e "ALTER TABLE consultas_cc.i_zipcodes AUTO_INCREMENT = $ai;"

ai=`mysql -u $user -p$password -e "SELECT AUTO_INCREMENT FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'consultas_cc' AND TABLE_NAME = 'addresses';" | sed 's/[^0-9]*//g'`
mysql -u $user -p$password -e "ALTER TABLE consultas_cc.i_addresses AUTO_INCREMENT = $ai;"

ai=`mysql -u $user -p$password -e "SELECT AUTO_INCREMENT FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'consultas_cc' AND TABLE_NAME = 'associations';" | sed 's/[^0-9]*//g'`
mysql -u $user -p$password -e "ALTER TABLE consultas_cc.i_associations AUTO_INCREMENT = $ai;"


# Reinicia a importacao
echo '1' > "$app/_db/settings/on_off"
php app/exec.php /landlines_import/run/$1