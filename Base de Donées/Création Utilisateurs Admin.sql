use utilisateurs_db;
drop table if exists utilisateurs;

CREATE TABLE IF NOT EXISTS Utilisateurs (
    ID_Utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    Pseudo VARCHAR(50) NOT NULL UNIQUE,
    MDP VARCHAR(255) NOT NULL,
    permissions VARCHAR(255) NOT NULL
);

INSERT INTO Utilisateurs (Pseudo, MDP, permissions) VALUES
('gaspardt', 'Admin123!', 'Admin'),
('lukasr', 'Admin123!', 'Admin'),
('thomaso', 'Admin123!', 'Admin');

CREATE USER IF NOT exists 'gaspardt'@'localhost' IDENTIFIED BY 'Admin123!';
GRANT ALL PRIVILEGES ON utilisateurs_db.* TO 'gaspardt'@'localhost';
FLUSH PRIVILEGES;

CREATE USER IF NOT exists 'lukasr'@'localhost' IDENTIFIED BY 'Admin123!';
GRANT ALL PRIVILEGES ON utilisateurs_db.* TO 'lukasr'@'localhost';
FLUSH PRIVILEGES;

CREATE USER IF NOT exists 'thomaso'@'localhost' IDENTIFIED BY 'Admin123!';
GRANT ALL PRIVILEGES ON utilisateurs_db.* TO 'thomaso'@'localhost';
FLUSH PRIVILEGES;

-- SHOW GRANTS FOR 'gaspardt'@'localhost';
-- SHOW GRANTS FOR 'lukasr'@'localhost';
-- SHOW GRANTS FOR 'thomaso'@'localhost';