#Criando um usuario
CREATE USER 'sexshoplove_me'@'localhost' IDENTIFIED BY '53x5h0p*m3**p455';
GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP ON sexshoplove_me.* TO 'sexshoplove_me'@'localhost';

CREATE USER 'sexshoplove_me'@'%' IDENTIFIED BY '53x5h0p*m3**p455';
GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP ON sexshoplove_me.* TO 'sexshoplove_me'@'%';

#Deletando um usuario
drop user 'sexshoplove_me'@localhost;
drop user 'sexshoplove_me'@'%';
flush privileges;