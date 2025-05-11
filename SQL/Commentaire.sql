CREATE TABLE Commentaire (
    Id_commentaire INTEGER(11) PRIMARY KEY,
    texte_com TEXT,
    date_com DATE,
    Id_etudiant INTEGER(11),
    Id_prof INTEGER(11),
    Id_reservation INTEGER(11),
    Id_salle INTEGER(11),
    FOREIGN KEY (Id_etudiant) REFERENCES Etudiant(Id_etudiant),
    FOREIGN KEY (Id_prof) REFERENCES Professeur(Id_prof),
    FOREIGN KEY (Id_reservation) REFERENCES Reservation(Id_reservation),
    FOREIGN KEY (Id_salle) REFERENCES Salles(Id_salle)
);