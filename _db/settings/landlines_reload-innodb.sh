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


# Reinicia a importacao
echo '1' > "$app/_db/settings/on_off"
php app/exec.php /landlines_import/run/$1