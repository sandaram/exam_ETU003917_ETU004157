-- =========================================================
-- base.sql — Mobile Money (Version 1) — VERSION SQLITE
-- =========================================================

-- ---------------------------------------------------------
-- 1. TABLES
-- ---------------------------------------------------------

CREATE TABLE prefixes_operateur (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe         VARCHAR(3) NOT NULL UNIQUE,
    actif           INTEGER NOT NULL DEFAULT 1
);

CREATE TABLE types_operation (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    code            VARCHAR(20) NOT NULL UNIQUE,
    libelle         VARCHAR(50) NOT NULL
);

CREATE TABLE baremes_frais (
    id                  INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id   INTEGER NOT NULL REFERENCES types_operation(id),
    montant_min         NUMERIC(12,2) NOT NULL,
    montant_max         NUMERIC(12,2) NOT NULL,
    frais               NUMERIC(12,2) NOT NULL,
    CHECK (montant_max > montant_min)
);

CREATE TABLE clients (
    id                  INTEGER PRIMARY KEY AUTOINCREMENT,
    numero_telephone    VARCHAR(15) NOT NULL UNIQUE,
    solde               NUMERIC(14,2) NOT NULL DEFAULT 0,
    date_creation       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE operations (
    id                      INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id               INTEGER NOT NULL REFERENCES clients(id),
    client_destinataire_id  INTEGER REFERENCES clients(id),
    type_operation_id       INTEGER NOT NULL REFERENCES types_operation(id),
    montant                 NUMERIC(14,2) NOT NULL,
    frais                   NUMERIC(12,2) NOT NULL DEFAULT 0,
    solde_apres              NUMERIC(14,2) NOT NULL,
    date_operation           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE administrateurs (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    nom_utilisateur VARCHAR(50) NOT NULL UNIQUE,
    mot_de_passe    VARCHAR(255) NOT NULL,
    role            VARCHAR(20) NOT NULL DEFAULT 'admin',
    actif           INTEGER NOT NULL DEFAULT 1,
    date_creation   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------
-- 2. VUES
-- ---------------------------------------------------------

CREATE VIEW vue_situation_gains AS
SELECT
    t.code                  AS type_operation,
    COUNT(o.id)              AS nombre_operations,
    SUM(o.frais)             AS total_frais_percus
FROM operations o
JOIN types_operation t ON t.id = o.type_operation_id
WHERE t.code IN ('RETRAIT', 'TRANSFERT')
GROUP BY t.code;

CREATE VIEW vue_situation_comptes_clients AS
SELECT
    c.id,
    c.numero_telephone,
    c.solde,
    c.date_creation,
    COUNT(o.id)              AS nombre_operations
FROM clients c
LEFT JOIN operations o ON o.client_id = c.id
GROUP BY c.id, c.numero_telephone, c.solde, c.date_creation;

CREATE VIEW vue_historique_client AS
SELECT
    o.id,
    o.client_id,
    t.code                  AS type_operation,
    o.montant,
    o.frais,
    o.solde_apres,
    o.date_operation
FROM operations o
JOIN types_operation t ON t.id = o.type_operation_id
ORDER BY o.date_operation DESC;

-- ---------------------------------------------------------
-- 3. DONNEES DE TEST
-- ---------------------------------------------------------

INSERT INTO prefixes_operateur (prefixe) VALUES
('033'),
('037');

INSERT INTO types_operation (code, libelle) VALUES
('DEPOT', 'Dépôt'),
('RETRAIT', 'Retrait'),
('TRANSFERT', 'Transfert');

-- Barème de frais (exemple donné dans le sujet)
-- Réécrit sans "VALUES ... AS v(...)" (non supporté par SQLite)
INSERT INTO baremes_frais (type_operation_id, montant_min, montant_max, frais)
SELECT id, 100, 1000, 50 FROM types_operation WHERE code IN ('RETRAIT','TRANSFERT')
UNION ALL
SELECT id, 1001, 5000, 50 FROM types_operation WHERE code IN ('RETRAIT','TRANSFERT')
UNION ALL
SELECT id, 5001, 10000, 100 FROM types_operation WHERE code IN ('RETRAIT','TRANSFERT')
UNION ALL
SELECT id, 10001, 25000, 200 FROM types_operation WHERE code IN ('RETRAIT','TRANSFERT')
UNION ALL
SELECT id, 25001, 50000, 400 FROM types_operation WHERE code IN ('RETRAIT','TRANSFERT')
UNION ALL
SELECT id, 50001, 100000, 800 FROM types_operation WHERE code IN ('RETRAIT','TRANSFERT')
UNION ALL
SELECT id, 100001, 250000, 1500 FROM types_operation WHERE code IN ('RETRAIT','TRANSFERT')
UNION ALL
SELECT id, 250001, 500000, 1500 FROM types_operation WHERE code IN ('RETRAIT','TRANSFERT')
UNION ALL
SELECT id, 500001, 1000000, 2500 FROM types_operation WHERE code IN ('RETRAIT','TRANSFERT')
UNION ALL
SELECT id, 1000001, 2000000, 3000 FROM types_operation WHERE code IN ('RETRAIT','TRANSFERT');

-- Quelques clients de test
INSERT INTO clients (numero_telephone, solde) VALUES
('0331234567', 50000),
('0372345678', 120000),
('0339876543', 0);

-- Compte operateur/admin de test : admin / admin123
INSERT INTO administrateurs (nom_utilisateur, mot_de_passe, role) VALUES
('admin', '$2y$10$QafOivLWNMDXrTfVrducfuZ0eknQc02YN9OOUsVdDhYpsuRqg7BR.', 'admin');

-- Quelques opérations de test
INSERT INTO operations (client_id, client_destinataire_id, type_operation_id, montant, frais, solde_apres)
VALUES
(1, NULL, (SELECT id FROM types_operation WHERE code = 'DEPOT'), 50000, 0, 50000),
(2, NULL, (SELECT id FROM types_operation WHERE code = 'DEPOT'), 150000, 0, 150000),
(2, NULL, (SELECT id FROM types_operation WHERE code = 'RETRAIT'), 30000, 400, 119600),
(1, 2,    (SELECT id FROM types_operation WHERE code = 'TRANSFERT'), 20000, 200, 29800);

-- =========================================================
-- FIN — Livraison 1 (v1)
-- =========================================================
