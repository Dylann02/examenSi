-- TABLE OPERATEUR
CREATE TABLE operateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

-- TABLE PREFIXE
CREATE TABLE prefixe (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prefixe VARCHAR(3) NOT NULL UNIQUE,
    id_operateur INT NOT NULL,

    FOREIGN KEY (id_operateur)
        REFERENCES operateur(id)
);

-- TABLE CLIENT
CREATE TABLE client (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100),
    cin VARCHAR(20) UNIQUE
);

-- TABLE NUMERO
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


-- TABLE TYPE OPERATION
CREATE TABLE type_operation (

    id INT AUTO_INCREMENT PRIMARY KEY,

    nom VARCHAR(30) UNIQUE NOT NULL
);

-- TABLE BAREME
CREATE TABLE bareme (

    id INT AUTO_INCREMENT PRIMARY KEY,

    id_operation INT NOT NULL,

    montant_min DECIMAL(15,2),

    montant_max DECIMAL(15,2),

    frais DECIMAL(15,2) NOT NULL,

    FOREIGN KEY(id_operation)
        REFERENCES type_operation(id)
);

-- TABLE TRANSACTION
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

