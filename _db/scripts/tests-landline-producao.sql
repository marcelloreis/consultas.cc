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

select 
    entities.doc,
    entities.name,
    entities.mother,
	entities.type,
    entities.gender,
    entities.birthday,
    landlines.ddd,
    landlines.tel,
    landlines.tel_full,
    associations.year,
    addresses.city_id,
    addresses.city,
    addresses.street,
    addresses.complement,
    addresses.number,
    addresses.neighborhood,
    zipcodes.code
from
    entities
        join
    associations ON associations.entity_id = entities.id
        join
    landlines ON landlines.id = associations.landline_id
        join
    addresses ON addresses.id = associations.address_id
        join
    zipcodes ON zipcodes.id = addresses.zipcode_id
where
    1 = 1 
#and landlines.tel = 33411002
#and zipcodes.code = 29168450
and entities.doc = 2820925600
#and entities.id in (1386683,50873,148702,1401404,1440768,1459233,1750152,1695872,225767,240122,355291,537906,672897,734727,1553551)
#group by entities.doc
order by mother;