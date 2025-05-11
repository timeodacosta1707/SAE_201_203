CREATE TABLE Materiels (
    Id_M INTEGER(11) PRIMARY KEY,
    Nom VARCHAR(100),
    Quantite INTEGER(11),
    Img TEXT,
    Etat_global VARCHAR(50),
    Description TEXT,
    Lien_demonstratif TEXT,
    Date_reservation DATE,
    Type_materiel VARCHAR(100)
);