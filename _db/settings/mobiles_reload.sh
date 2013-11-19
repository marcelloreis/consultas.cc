#!/bin/bash
# My first script

#Posiciona o diretorio na raiz do projeto
cd /var/www/dados


#Declaracao das variaveis
app=`pwd`
user=root
password=62792

# Pausa a importacao
echo '0' > "$app/_db/settings/on_off"

#Zera as tabelas auxiliares
mysql -u $user -p$password -e 'UPDATE naszaco_pessoas._counter SET success = null, fails = null, extracted = null, start_time = null;'
mysql -u $user -p$password -e 'UPDATE naszaco_pessoas._timing SET time = null;'
mysql -u $user -p$password -e 'TRUNCATE table naszaco_pessoas._logs;'

#Migra todos os dados importados ate o momento
mysql -u $user -p$password -e 'INSERT INTO naszaco_pessoas.entities SELECT * FROM naszaco_pessoas.i_entities;'
mysql -u $user -p$password -e 'INSERT INTO naszaco_pessoas.mobiles SELECT * FROM naszaco_pessoas.i_mobiles;'
mysql -u $user -p$password -e 'INSERT INTO naszaco_pessoas.zipcodes SELECT * FROM naszaco_pessoas.i_zipcodes;'
mysql -u $user -p$password -e 'INSERT INTO naszaco_pessoas.addresses SELECT * FROM naszaco_pessoas.i_addresses;'
mysql -u $user -p$password -e 'INSERT INTO naszaco_pessoas.associations SELECT * FROM naszaco_pessoas.i_associations;'

#Zera as tableas de importacao
mysql -u $user -p$password -e 'TRUNCATE TABLE naszaco_pessoas.i_entities;'
mysql -u $user -p$password -e 'TRUNCATE TABLE naszaco_pessoas.i_mobiles;'
mysql -u $user -p$password -e 'TRUNCATE TABLE naszaco_pessoas.i_zipcodes;'
mysql -u $user -p$password -e 'TRUNCATE TABLE naszaco_pessoas.i_addresses;'
mysql -u $user -p$password -e 'TRUNCATE TABLE naszaco_pessoas.i_associations;'

#Sincroniza o auto increment das tabelas de importacao e de producao
ai=`mysql -u $user -p$password -e "SELECT AUTO_INCREMENT FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'naszaco_pessoas' AND TABLE_NAME = 'entities';" | sed 's/[^0-9]*//g'`
mysql -u $user -p$password -e "ALTER TABLE naszaco_pessoas.i_entities AUTO_INCREMENT = $ai;"

ai=`mysql -u $user -p$password -e "SELECT AUTO_INCREMENT FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'naszaco_pessoas' AND TABLE_NAME = 'mobiles';" | sed 's/[^0-9]*//g'`
mysql -u $user -p$password -e "ALTER TABLE naszaco_pessoas.i_mobiles AUTO_INCREMENT = $ai;"

ai=`mysql -u $user -p$password -e "SELECT AUTO_INCREMENT FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'naszaco_pessoas' AND TABLE_NAME = 'zipcodes';" | sed 's/[^0-9]*//g'`
mysql -u $user -p$password -e "ALTER TABLE naszaco_pessoas.i_zipcodes AUTO_INCREMENT = $ai;"

ai=`mysql -u $user -p$password -e "SELECT AUTO_INCREMENT FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'naszaco_pessoas' AND TABLE_NAME = 'addresses';" | sed 's/[^0-9]*//g'`
mysql -u $user -p$password -e "ALTER TABLE naszaco_pessoas.i_addresses AUTO_INCREMENT = $ai;"

ai=`mysql -u $user -p$password -e "SELECT AUTO_INCREMENT FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'naszaco_pessoas' AND TABLE_NAME = 'associations';" | sed 's/[^0-9]*//g'`
mysql -u $user -p$password -e "ALTER TABLE naszaco_pessoas.i_associations AUTO_INCREMENT = $ai;"


# Reinicia a importacao
echo '1' > "$app/_db/settings/on_off"
php app/exec.php /mobiles_import/run/$1