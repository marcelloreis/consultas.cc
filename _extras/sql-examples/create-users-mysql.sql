#Criando um usuario
CREATE USER 'castroviejo'@'localhost' IDENTIFIED BY 'c457r0v13j0';
GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP ON castroviejo.* TO 'castroviejo'@'localhost';

CREATE USER 'castroviejo'@'%' IDENTIFIED BY 'c457r0v13j0';
GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP ON castroviejo.* TO 'castroviejo'@'%';

#Deletando um usuario
drop user 'castroviejo'@localhost;
drop user 'castroviejo'@'%';
flush privileges;