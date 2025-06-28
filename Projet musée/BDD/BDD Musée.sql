CREATE DATABASE IF NOT EXISTS utilisateurs_db;
USE utilisateurs_db;

DROP TABLE IF EXISTS Visit;
DROP TABLE IF EXISTS Visiteur;
DROP TABLE IF EXISTS Exposition;
DROP TABLE IF EXISTS Type_Entree;

CREATE TABLE IF NOT EXISTS Type_Entree (
    ID_Type_Entree INT AUTO_INCREMENT PRIMARY KEY,
    Type_Entree ENUM('Permanent', 'Temporaire', 'Les deux') NOT NULL
);

CREATE TABLE IF NOT EXISTS Visiteur (
    ID_Visiteur INT AUTO_INCREMENT PRIMARY KEY,
    Nom VARCHAR(40),
    Prenom VARCHAR(40),
    Heure_Arrivee DATETIME NOT NULL,
    Heure_Depart DATETIME,
    Date_Visite DATE NOT NULL,
    ID_Type_Entree INT,
    FOREIGN KEY (ID_Type_Entree) REFERENCES Type_Entree(ID_Type_Entree)
);

CREATE TABLE IF NOT EXISTS Exposition (
    ID_Exposition INT AUTO_INCREMENT PRIMARY KEY,
    Libelle_Court VARCHAR(100) NOT NULL,
    Descriptif_Detail TEXT,
    ID_Type_Entree INT,
    Date_Debut DATE NOT NULL,
    Date_Fin DATE NOT NULL DEFAULT '9999-12-31',
    FOREIGN KEY (ID_Type_Entree) REFERENCES Type_Entree(ID_Type_Entree) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS Visit (
    ID_Visiteur INT,
    ID_Exposition INT,
    PRIMARY KEY (ID_Visiteur, ID_Exposition),
    FOREIGN KEY (ID_Visiteur) REFERENCES Visiteur(ID_Visiteur) ON DELETE CASCADE,
    FOREIGN KEY (ID_Exposition) REFERENCES Exposition(ID_Exposition) ON DELETE CASCADE
);
