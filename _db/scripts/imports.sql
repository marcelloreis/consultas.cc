#Pausar importacao
update naszaco_pessoas._settings set actived = 0;

#Reinicia a importacao
update naszaco_pessoas._counter set success = null, fails = null, extracted = null, start_time = null;
update naszaco_pessoas._timing set time = null;
update naszaco_pessoas._settings set actived = 1;

#Reseta todas as tabelas e deixa pronto para iniciar a importacao
SET foreign_key_checks = 0;
truncate table naszaco_pessoas._logs;
truncate table naszaco_pessoas.entities;
truncate table naszaco_pessoas.associations;
truncate table naszaco_pessoas.addresses;
truncate table naszaco_pessoas.zipcodes;
truncate table naszaco_pessoas.landlines;
truncate table naszaco_pessoas.mobiles;

truncate table naszaco_pessoas.i_entities;
truncate table naszaco_pessoas.i_associations;
truncate table naszaco_pessoas.i_addresses;
truncate table naszaco_pessoas.i_zipcodes;
truncate table naszaco_pessoas.i_landlines;
truncate table naszaco_pessoas.i_mobiles;

#Migracao dos dados importados
INSERT INTO naszaco_pessoas.entities SELECT * FROM naszaco_pessoas.i_entities;
INSERT INTO naszaco_pessoas.landlines SELECT * FROM naszaco_pessoas.i_landlines;
INSERT INTO naszaco_pessoas.zipcodes SELECT * FROM naszaco_pessoas.i_zipcodes;
INSERT INTO naszaco_pessoas.addresses SELECT * FROM naszaco_pessoas.i_addresses;
INSERT INTO naszaco_pessoas.associations SELECT * FROM naszaco_pessoas.i_associations;

#Sincronizacao dos auto increments das tableas de importacao
SELECT AUTO_INCREMENT FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'naszaco_pessoas' AND   TABLE_NAME   = 'entities';
ALTER TABLE naszaco_pessoas.i_entities AUTO_INCREMENT = 502865;

SELECT AUTO_INCREMENT FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'naszaco_pessoas' AND   TABLE_NAME   = 'associations';
ALTER TABLE naszaco_pessoas.i_associations AUTO_INCREMENT = 847590;

SELECT AUTO_INCREMENT FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'naszaco_pessoas' AND   TABLE_NAME   = 'addresses';
ALTER TABLE naszaco_pessoas.i_addresses AUTO_INCREMENT = 361956;

SELECT AUTO_INCREMENT FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'naszaco_pessoas' AND   TABLE_NAME   = 'zipcodes';
ALTER TABLE naszaco_pessoas.i_zipcodes AUTO_INCREMENT = 21167;

SELECT AUTO_INCREMENT FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'naszaco_pessoas' AND   TABLE_NAME   = 'landlines';
ALTER TABLE naszaco_pessoas.i_landlines AUTO_INCREMENT = 342781;

SELECT AUTO_INCREMENT FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'naszaco_pessoas' AND   TABLE_NAME   = 'mobiles';
ALTER TABLE naszaco_pessoas.i_mobiles AUTO_INCREMENT = 1;