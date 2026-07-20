CREATE DATABASE produits;

CREATE TABLE produit (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(30),
    prix FLOAT
);

INSERT INTO produit(nom,prix) VALUES ("Burger",15000);
INSERT INTO produit(nom,prix) VALUES ("Pizza",23000);
INSERT INTO produit(nom,prix) VALUES ("Frites",4000);
INSERT INTO produit(nom,prix) VALUES ("Frites",4000);