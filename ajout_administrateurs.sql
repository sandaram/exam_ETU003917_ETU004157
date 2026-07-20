-- ---------------------------------------------------------
-- Table administrateurs (côté opérateur/back-office)
-- ---------------------------------------------------------

CREATE TABLE administrateurs (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    nom_utilisateur VARCHAR(50) NOT NULL UNIQUE,
    mot_de_passe    VARCHAR(255) NOT NULL,
    role            VARCHAR(20) NOT NULL DEFAULT 'admin',
    actif           INTEGER NOT NULL DEFAULT 1,
    date_creation   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Compte admin de test
-- Remplacez XXXXX par le hash généré avec :
-- php -r "echo password_hash('admin123', PASSWORD_DEFAULT);"
INSERT INTO administrateurs (nom_utilisateur, mot_de_passe, role) VALUES
('admin', '$2y$10$QafOivLWNMDXrTfVrducfuZ0eknQc02YN9OOUsVdDhYpsuRqg7BR.', 'admin');