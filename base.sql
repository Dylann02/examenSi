DROP DATABASE IF EXISTS mobile_money;
CREATE DATABASE mobile_money;
USE mobile_money;

-- ==========================
-- TABLE OPERATEUR
-- ==========================
CREATE TABLE operateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

-- ==========================
-- TABLE PREFIXE
-- ==========================
CREATE TABLE prefixe (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prefixe VARCHAR(3) NOT NULL UNIQUE,
    id_operateur INT NOT NULL,

    FOREIGN KEY (id_operateur)
        REFERENCES operateur(id)
);

-- ==========================
-- TABLE CLIENT
-- ==========================
CREATE TABLE client (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100),
    cin VARCHAR(20) UNIQUE
);

-- ==========================
-- TABLE NUMERO
-- ==========================
CREATE TABLE numero (
    id INT AUTO_INCREMENT PRIMARY KEY,

    numero VARCHAR(10) NOT NULL UNIQUE,

    solde DECIMAL(15,2) DEFAULT 0,

    etat ENUM('ACTIF','BLOQUE') DEFAULT 'ACTIF',

    id_client INT NOT NULL,

    id_operateur INT NOT NULL,

    FOREIGN KEY(id_client)
        REFERENCES client(id),

    FOREIGN KEY(id_operateur)
        REFERENCES operateur(id)
);

-- ==========================
-- TABLE TYPE OPERATION
-- ==========================
CREATE TABLE type_operation (

    id INT AUTO_INCREMENT PRIMARY KEY,

    nom VARCHAR(30) UNIQUE NOT NULL
);

-- ==========================
-- TABLE BAREME
-- ==========================
CREATE TABLE bareme (

    id INT AUTO_INCREMENT PRIMARY KEY,

    id_operation INT NOT NULL,

    montant_min DECIMAL(15,2),

    montant_max DECIMAL(15,2),

    frais DECIMAL(15,2) NOT NULL,

    FOREIGN KEY(id_operation)
        REFERENCES type_operation(id)
);

-- ==========================
-- TABLE TRANSACTION
-- ==========================
CREATE TABLE transaction_mm (

    id INT AUTO_INCREMENT PRIMARY KEY,

    date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP,

    montant DECIMAL(15,2) NOT NULL,

    frais DECIMAL(15,2) DEFAULT 0,

    statut ENUM('EN_ATTENTE','SUCCES','ECHEC','ANNULE')
        DEFAULT 'SUCCES',

    id_operation INT NOT NULL,

    id_numero_source INT NULL,

    id_numero_destination INT NULL,

    FOREIGN KEY(id_operation)
        REFERENCES type_operation(id),

    FOREIGN KEY(id_numero_source)
        REFERENCES numero(id),

    FOREIGN KEY(id_numero_destination)
        REFERENCES numero(id)
);

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
('034',3),
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


--Alea 