#Tabelas auxiliares
SELECT * FROM naszaco_pessoas._counter;
SELECT * FROM naszaco_pessoas._settings;
SELECT * FROM naszaco_pessoas._logs order by `table`;
SELECT * FROM naszaco_pessoas._timing;

#Tempo medio das queries
select query_desc, count(id) as qt_consultas, avg(time) as tempo_medio from naszaco_pessoas._timing group by query_id order by avg(time) desc;

#Nomes repetidos sem definicao de sexo
SELECT doc, name, type, count(h1) as ocorrencias FROM naszaco_pessoas.entities where type != 2 and gender is null group by h1 order by count(h1) desc, name limit 10000;

#Selects das tabelas de telefone fixo
SELECT * FROM naszaco_pessoas.entities order by id desc;
SELECT * FROM naszaco_pessoas.associations order by id desc;
SELECT * FROM naszaco_pessoas.addresses order by id desc;
SELECT * FROM naszaco_pessoas.zipcodes order by id desc;
SELECT * FROM naszaco_pessoas.landlines order by id desc;
SELECT * FROM naszaco_pessoas.mobiles order by id desc;

SELECT * FROM naszaco_pessoas.i_entities order by id;
SELECT * FROM naszaco_pessoas.i_associations order by id;
SELECT * FROM naszaco_pessoas.i_addresses order by id;
SELECT * FROM naszaco_pessoas.i_zipcodes order by id;
SELECT * FROM naszaco_pessoas.i_landlines order by id;
SELECT * FROM naszaco_pessoas.i_mobiles;
