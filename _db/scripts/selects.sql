#Tabelas auxiliares
SELECT * FROM _counter;
SELECT * FROM _settings;
SELECT * FROM _logs order by `table` desc limit 10000;
SELECT * FROM _timing;

#Nomes repetidos sem definicao de sexo
SELECT doc, name, type, count(h1) as ocorrencias FROM entities where type != 2 and gender is null group by h1 order by count(h1) desc, name limit 10000;

#Selects das tabelas de telefone fixo
SELECT * FROM entities order by id desc;
SELECT * FROM associations order by id desc;
SELECT * FROM addresses order by id desc;
SELECT * FROM zipcodes order by id desc;
SELECT * FROM landlines order by id desc;
SELECT * FROM mobiles order by id desc;

SELECT * FROM i_entities order by id desc;
SELECT * FROM i_associations order by id desc;
SELECT * FROM i_addresses order by id desc;
SELECT * FROM i_zipcodes order by id;
SELECT * FROM i_landlines order by id;
SELECT * FROM i_mobiles order by id;
