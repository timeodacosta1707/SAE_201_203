CREATE TABLE Reservation (
    Id_reservation INTEGER(11) PRIMARY KEY,
    type_user VARCHAR(50),
    Id_salle INTEGER(11),
    Id_M INTEGER(11),
    date_reservation DATE,
    creneau VARCHAR(50),
    statut VARCHAR(50),
    signature_admin TEXT,
    date_verification DATE,
    reservation_materiel TEXT,
    reservation_salle TEXT,
    historique_reservation TEXT,
    modif_reservation TEXT,
    FOREIGN KEY (Id_salle) REFERENCES Salles(Id_salle),
    FOREIGN KEY (Id_M) REFERENCES Materiels(Id_M)
);