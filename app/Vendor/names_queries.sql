#NOMES MASCULINOS
SELECT 
    concat('\'',
            substring_index(name, ' ', 1),
            '\',')
FROM
    naszaco_pessoas.entities
where
    type != 2 and gender is null
		and CHAR_LENGTH(substring_index(name, ' ', 1)) > 3
        and (
        substring_index(name, " ", 1) like '%o' 
        or substring_index(name, " ", 1) like '%or' 
        or substring_index(name, " ", 1) like '%os'
        or substring_index(name, ' ', 1) like '%f'
        or substring_index(name, ' ', 1) like '%j'
        or substring_index(name, ' ', 1) like '%q'
        or substring_index(name, ' ', 1) like '%v'
        or substring_index(name, ' ', 1) like '%w'
        or substring_index(name, ' ', 1) like '%x'
        or substring_index(name, ' ', 1) like '%b'
        or substring_index(name, ' ', 1) like '%c'
        or substring_index(name, ' ', 1) like '%d'
        or substring_index(name, ' ', 1) like '%k'
        or substring_index(name, ' ', 1) like '%g'
        or substring_index(name, ' ', 1) like '%p')
group by h1
order by name
limit 50000;


#NOMES FEMININOS
SELECT 
    concat('\'',
            substring_index(name, ' ', 1),
            '\',')
FROM
    naszaco_pessoas.entities
where
    type != 2 and gender is null
        and CHAR_LENGTH(substring_index(name, ' ', 1)) > 3
        and (substring_index(name, ' ', 1) like '%a'
        or substring_index(name, ' ', 1) like '%e')
group by h1
order by name
limit 50000;