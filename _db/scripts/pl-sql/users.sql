#Criando um usuario
CREATE USER 'nome_do_usuario'@'localhost' IDENTIFIED BY 'senha';
GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP ON nome_do_banco_da_aplicacao.* TO 'nome_do_usuario'@'localhost';

CREATE USER 'nome_do_usuario'@'%' IDENTIFIED BY 'senha';
GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP ON nome_do_banco_da_aplicacao.* TO 'nome_do_usuario'@'%';

#Deletando um usuario
drop user 'nome_do_usuario'@localhost;
drop user 'nome_do_usuario'@'%';
flush privileges;