-- ==========================
-- INSERTS DE BASE
-- ==========================

INSERT INTO operateur(nom)
VALUES
('Orange Money'),
('MVola'),
('Airtel Money');

INSERT INTO prefixe(prefixe,id_operateur)
VALUES
('032',2),
('033',1),
('034',2),
('038',2);

INSERT INTO type_operation(nom)
VALUES
('Depot'),
('Retrait'),
('Transfert');

-- ==========================
-- EXEMPLE BAREME TRANSFERT
-- ==========================

INSERT INTO bareme(id_operation,montant_min,montant_max,frais)
VALUES
(3,100,1000,50),
(3,1001,5000,50),
(3,5001,10000,100),
(3,10001,25000,200),
(3,25001,50000,400),
(3,50001,100000,800),
(3,100001,250000,1500),
(3,250001,500000,1500),
(3,500001,1000000,2500),
(3,1000001,2000000,3000);

-- ==========================
-- EXEMPLE CLIENT
-- ==========================

INSERT INTO client(nom,prenom,cin)
VALUES
('Rakoto','Jean','123456789012'),
('Rabe','Paul','987654321012');

INSERT INTO numero(numero,solde,etat,id_client,id_operateur)
VALUES
('0341234567',500000,'ACTIF',1,3),
('0339876543',250000,'ACTIF',2,1);