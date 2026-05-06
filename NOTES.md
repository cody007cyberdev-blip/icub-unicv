# Atualizar tata de entra de candidatos para ultimos 6 meses
```` SQL
UPDATE tbl_candidato
SET data_entrada =  DATE_ADD('2024-01-01', INTERVAL FLOOR(RAND() * 6) MONTH);

UPDATE tbl_supervisor
SET data_entrada =  DATE_ADD('2024-01-01', INTERVAL FLOOR(RAND() * 6) MONTH);

UPDATE tbl_coordenador
SET data_entrada =  DATE_ADD('2024-01-01', INTERVAL FLOOR(RAND() * 6) MONTH);

UPDATE tbl_coordenador
SET data_nascimento =  DATE_ADD('1995-01-01', INTERVAL FLOOR(RAND() * 30) YEAR);

UPDATE tbl_supervisor
SET data_nascimento =  DATE_ADD('1995-01-01', INTERVAL FLOOR(RAND() * 30) YEAR);

UPDATE tbl_candidato
SET data_nascimento =  DATE_ADD('2000-01-01', INTERVAL FLOOR(RAND() * 18) YEAR);


````

