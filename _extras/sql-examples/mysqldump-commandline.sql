#CADASTRO
mysqldump --complete-insert --allow-keywords --skip-tz-utc --skip-comments --skip-set-charset --skip-add-drop-table --single-transaction --disable-keys -h 192.168.3.121 -uroot -prdr155c CADASTRO | gzip > /var/www/__CADASTRO.sql.gz

#FATURAMENTO
mysqldump --complete-insert --allow-keywords --skip-tz-utc --skip-comments --skip-set-charset --skip-add-drop-table --single-transaction --disable-keys -h 192.168.3.121 -uroot -prdr155c FATURAMENTO --ignore-tables=FATURAMENTO.ACESSOS_AUX | gzip > /var/www/__FATURAMENTO.sql.gz

#SEGURANCA
mysqldump --complete-insert --allow-keywords --skip-tz-utc --skip-comments --skip-set-charset --skip-add-drop-table --single-transaction --disable-keys -h 192.168.3.121 -uroot -prdr155c SEGURANCA | gzip > /var/www/__SEGURANCA.sql.gz
