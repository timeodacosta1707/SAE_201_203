CREATE TABLE Personne (
    Id_user INTEGER(11) PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(150),
    telephone VARCHAR(20),
    date_naissance DATE,
    mot_de_passe VARCHAR(255),
    /* photo VARCHAR(255) */
);