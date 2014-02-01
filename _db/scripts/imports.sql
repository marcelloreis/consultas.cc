#Reinicia a importacao
update _counter set success = null, fails = null, extracted = null, start_time = null;
update _timing set time = null;


#Reseta todas as tabelas e deixa pronto para iniciar a importacao
truncate table _logs;
truncate table entities;
truncate table associations;
truncate table addresses;
truncate table zipcodes;
truncate table landlines;
truncate table mobiles;

truncate table i_entities;
truncate table i_associations;
truncate table i_addresses;
truncate table i_zipcodes;
truncate table i_landlines;
truncate table i_mobiles;

/*
#Repara as tabelas
repair table _logs;
repair table entities;
repair table associations;
repair table addresses;
repair table zipcodes;
repair table landlines;
repair table mobiles;


repair table i_entities;
repair table i_associations;
repair table i_addresses;
repair table i_zipcodes;
repair table i_landlines;
repair table i_mobiles;


#Migracao dos dados importados

INSERT INTO entities SELECT * FROM i_entities;
INSERT INTO landlines SELECT * FROM i_landlines;
INSERT INTO zipcodes SELECT * FROM i_zipcodes;
INSERT INTO addresses SELECT * FROM i_addresses;
INSERT INTO associations SELECT * FROM i_associations;
*/