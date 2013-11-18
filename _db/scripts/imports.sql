#Reinicia a importacao
update naszaco_pessoas._counter set success = null, fails = null, extracted = null, start_time = null;
update naszaco_pessoas._timing set time = null;


#Reseta todas as tabelas e deixa pronto para iniciar a importacao
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

#Repara as tabelas
repair table naszaco_pessoas._logs;
repair table naszaco_pessoas.entities;
repair table naszaco_pessoas.associations;
repair table naszaco_pessoas.addresses;
repair table naszaco_pessoas.zipcodes;
repair table naszaco_pessoas.landlines;
repair table naszaco_pessoas.mobiles;

repair table naszaco_pessoas.i_entities;
repair table naszaco_pessoas.i_associations;
repair table naszaco_pessoas.i_addresses;
repair table naszaco_pessoas.i_zipcodes;
repair table naszaco_pessoas.i_landlines;
repair table naszaco_pessoas.i_mobiles;

#Migracao dos dados importados
INSERT INTO naszaco_pessoas.entities SELECT * FROM naszaco_pessoas.i_entities;
INSERT INTO naszaco_pessoas.landlines SELECT * FROM naszaco_pessoas.i_landlines;
INSERT INTO naszaco_pessoas.zipcodes SELECT * FROM naszaco_pessoas.i_zipcodes;
INSERT INTO naszaco_pessoas.addresses SELECT * FROM naszaco_pessoas.i_addresses;
INSERT INTO naszaco_pessoas.associations SELECT * FROM naszaco_pessoas.i_associations;
