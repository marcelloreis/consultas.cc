#Monique
#10114816778

#Ismar
#57851344700

#Irmao do Ismar (Moacir Ferreira da Fonseca Nao esta achando)
#49340581768

#Amanda
#10821445766

#Tia Maria
#65248554772

#Papai
#41640624791

#Giuliano
#04357181771

#Theotonio
#09685634734

#Joao
#05686477779

#Beth
#9270317790

#Mathielo
#10837125782

#Genesio
#35360461772

#MARIA CRISTINA TOLEDO BELTRAO (Atualmente este telefone pertence a Ana Paula Tavares)
#04577169754

#Rodrigo Beltrao
#00445928760
#03102410731

#Benigna (Sogra Rodrigo)
#05412808646

#CEP Feu Rosa
#29172027

#Fabio Dipre
#08486986737

#Adelson Dipre
#34296093720

#Tia Zilda
#36596833534

#Ana de Assis Souza
#34862234615

#Leticia
#5772697714

#Dilma Guilherme
#76541150753

select * from i_entities where doc = 09685634734;

select 
    i_entities.doc,
	i_addresses.lote,
    i_entities.name,
    i_entities.mother,
	i_entities.type,
    i_entities.gender,
    i_entities.birthday,
    i_landlines.ddd,
    i_landlines.tel,
    i_landlines.tel_full,
    i_associations.year,
    i_addresses.city_id,
    i_addresses.city,
	i_addresses.state,
    i_addresses.street,
    i_addresses.complement,
    i_addresses.number,
    i_addresses.neighborhood,
    i_zipcodes.code
from
    i_entities
        join
    i_associations ON i_associations.entity_id = i_entities.id
        join
    i_landlines ON i_landlines.id = i_associations.landline_id
        join
    i_addresses ON i_addresses.id = i_associations.address_id
        join
    i_zipcodes ON i_zipcodes.id = i_addresses.zipcode_id
where
    1 = 1 
#and i_landlines.tel_full = 2733411002
#and i_zipcodes.code = 29168450
and i_entities.doc = 05412808646
#and i_entities.id in (1386683,50873,148702,1401404,1440768,1459233,1750152,1695872,225767,240122,355291,537906,672897,734727,1553551)
#group by i_entities.doc
order by name;